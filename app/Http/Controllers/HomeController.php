<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\BloodInventory;
use App\Models\BloodBank;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\User_bank;
use App\Models\Withdrawal;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    public function index()
    {
        $bankId = session('bank_id')??request('bank_id');
        $user = Auth::user();
        $bank = BloodBank::find($bankId);
        $requests = BloodRequest::where('donor_hospital', $bank->name)->orderBy('created_at', 'desc')->take(10)->get();
        $bloodTypes = ['A', 'B', 'AB', 'O', 'NT'];
        $chartData = [];
        $status = ['available', 'tested', 'not_tested'];
        $errors = [];
        $bankData = ['label' => $bank->name, 'data' => []];
        foreach ($bloodTypes as $type) {
            if ($bankId && $bank->id != $bankId && !($user && $user->is_admin)) {
                $blood = collect();
            } else {
                $bp = BloodInventory::where([['collection_agency', $bank->name], ['blood_type', $type],['rhesus','Positive']])->whereIn('status',$status)->sum('volume');
                $bn = BloodInventory::where([['collection_agency', $bank->name], ['blood_type', $type], ['rhesus',  'Negative']])->whereIn('status',$status)->sum('volume');
            }
            
            $thresholds = json_decode($bank->threshold);

            foreach($thresholds as $threshold){
                $message = '';
                 if($threshold->blood_group == $type.'-' && $bn<$threshold->threshold){
                    $message = "Blood group $type- is below threshold  with quantity $bn pints.";
                    if (!in_array($message, $errors)) {
                        $errors[] = $message;
                    }
                 };
                if ($threshold->blood_group == $type . '+' && $bp < $threshold->threshold) {
                    $message = "Blood group $type+ is below threshold  with quantity $bp pints.";
                    if (!in_array($message, $errors)) {
                        $errors[] = $message;
                    }
                };
            }
            $bankData['data'][] = $bp;
            $bankData['data'][] = $bn;
        }
        $chartData[] = $bankData;
        $typeLabels = ['A+','A-','B+','B-','AB+','AB-', 'O+','O-', 'NT'];
        $totalInventory = BloodInventory::where('collection_agency', $bank->name)->whereIn('status', $status)->sum('volume');
        $pendingRequests = BloodRequest::where([['status', 'pending'], ['donor_hospital', $bank->name]])->count();
        $totalUsers = User_bank::where('bank_id', $bankId)->count();
        if (request()->is('api/*')) {
            return response()->json([
                'chartData' => $chartData,
                'typeLabels' => $typeLabels,
                'totalInventory' => $totalInventory,
                'pendingRequests' => $pendingRequests,
                'totalUsers' => $totalUsers,'errors' => $errors
            ],200);
        }
        // return [$totalInventory];
        return view('home', compact('chartData', 'typeLabels', 'totalInventory', 'pendingRequests', 'requests', 'totalUsers','errors'));
    }
    public function reportsPage()
    {
        $selectedBank = BloodBank::findOrFail(session('bank_id')??request('bank_id'));
        $banks = BloodBank::orderBy('name')->get();

        $status = ['available', 'tested', 'not_tested'];
        $bloodGroups = ['A+','A-','B+','B-','AB+','AB-','O+','O-','NT'];

        $inventoryByGroup = BloodInventory::where('collection_agency',$selectedBank->name)->whereIn('status', $status)
            ->selectRaw("CASE WHEN blood_type = 'NT' THEN 'NT' ELSE blood_type|| rhesus END as blood_group")
            ->selectRaw('SUM(volume) as total')
            ->groupBy('blood_group')
            ->pluck('total', 'blood_group')
            ->toArray();

        $requestStats = BloodRequest::where('donor_hospital',$selectedBank->name)->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $withdrawalStats = Withdrawal::where('bank_id',$selectedBank->id)->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $bankSummaries = BloodBank::withCount(['users', 'requests', 'withdrawals'])->get();

        $selectedBankInventory = array_fill_keys($bloodGroups, 0);
        $selectedBankRequests = [];
        $selectedBankWithdrawals = [];
        $selectedBankTotals = null;

        

        if (request()->is('api/*')) {
            return response()->json([
                'banks' => $banks,
                'inventoryByGroup' => $inventoryByGroup,
                'requestStats' => $requestStats,
                'withdrawalStats' => $withdrawalStats,
                'bankSummaries' => $bankSummaries,
                'selectedBank' => $selectedBank,
                'selectedBankInventory' => $selectedBankInventory,
                'selectedBankRequests' => $selectedBankRequests,
                'selectedBankWithdrawals' => $selectedBankWithdrawals,
                'selectedBankTotals' => $selectedBankTotals,
            ]);
        }

        return view('reports', compact(
            'banks',
            'bloodGroups',
            'inventoryByGroup',
            'requestStats',
            'withdrawalStats',
            'bankSummaries',
            'selectedBank',
            'selectedBankInventory',
            'selectedBankRequests',
            'selectedBankWithdrawals',
            'selectedBankTotals'
        ));
    }

    public function report()
    {
        $bags = BloodInventory::whereIn('status', ['available', 'tested', 'not_tested'])->get();
        $withdrawals = Withdrawal::orderBy('created_at', 'desc')->take(10)->get();
        $activities = Activity::orderBy('created_at', 'desc')->take(10)->get();
        foreach ($activities as $activity) {
            $activity->user_name = $activity->user ? $activity->user->name : 'Unknown';
        }
        foreach ($withdrawals as $withdrawal) {
            $withdrawal->user_name = $withdrawal->user ? $withdrawal->user->name : 'Unknown';
            $withdrawal->din = $withdrawal->bloodbag ? $withdrawal->bloodbag->din : 'Unknown';
            $withdrawal->bank_name = $withdrawal->bank ? $withdrawal->bank->name : 'Unknown';
        }
        foreach ($bags as $bag) {
            $bag->bank_name = $bag->bank ? $bag->bank->name : 'Unknown';
        }
        if (request()->is('api/*')) {
            return response()->json([
                'bags' => $bags,
                'withdrawals' => $withdrawals,
                'activities' => $activities
            ]);
        }
        return view('report', compact('bags', 'withdrawals', 'activities'));
    }

    public function banks()
    {
        $banks = BloodBank::select('id', 'name')->get();
        if (request()->is('api/*')) {
            return response()->json([
                'banks' => $banks
            ]);
        }
    }
    public function exportReport(){
        $selectedBank = BloodBank::findOrFail(session('bank_id')??request('bank_id'));
        $status = ['available', 'tested', 'not_tested'];

        $inventoryByGroup = BloodInventory::where('collection_agency', $selectedBank->name)->whereIn('status', $status)
            ->selectRaw("CASE WHEN blood_type = 'NT' THEN 'NT' ELSE blood_type||rhesus END as blood_group")
            ->selectRaw('SUM(volume) as total')
            ->groupBy('blood_group')
            ->pluck('total', 'blood_group')
            ->toArray();
        $pdf= Pdf::loadView('reports.inventory',compact('inventoryByGroup','selectedBank'));
        $pdf->setPaper('A4', 'landscape');
        $data = ['report'=>'Inventory Report'];
        if(request('action') == 'mail'){
            $user = User::findOrFail(Auth::id());
            Mail::send(
                'reports.message',
                $data,
                function ($message) use($user,$pdf) {
                    $message->to($user->email, $user->name)->subject('Inventory Report as at ' . date('jS F Y H:i:s'))->attachData($pdf->output(), 'inventory_report_' . date('Y_m_d_H_i_s') . '.pdf');
                }
            );
            return back()->with('success', 'Report Mailed successfully');
        }else if(request('action') == 'download'){
            return $pdf->download('inventory_report_' . date('Y_m_d_H_i_s') . '.pdf');
        }
        
    }
}
