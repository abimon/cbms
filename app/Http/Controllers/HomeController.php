<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\BloodInventory;
use App\Models\BloodBank;
use App\Models\BloodRequest;
use App\Models\User_bank;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index()
    {
        $bankId = session('bank_id');
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
}
