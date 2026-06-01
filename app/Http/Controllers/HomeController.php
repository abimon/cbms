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
            $quantity = 0;
            if ($bankId && $bank->id != $bankId && !($user && $user->is_admin)) {
                $blood = collect();
            } else {
                $blood = BloodInventory::where([['collection_agency', $bank->name], ['blood_type', $type],['rhesus','!=','NT']])->get();
            }
            foreach ($blood as $bag) {
                if (in_array($bag->status, $status)) {
                    $quantity += $bag->volume;
                }
            }
            $thresholds = json_decode($bank->threshold);

            foreach($thresholds as $threshold){
                $q= 0;
                if (str_contains($threshold->blood_group, $type)){
                    $q += $threshold->threshold;
                }
                // return [$q,$quantity];
                $message = "Blood group $type is below threshold  with quantity $quantity pints.";
                if(str_contains($threshold->blood_group,$type) && $quantity < $q && !in_array($message,$errors)){
                    $errors[] = $message;
                }
            }
            $bankData['data'][] = $quantity;
        }
        $chartData[] = $bankData;
        $typeLabels = $bloodTypes;
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
            ]);
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
