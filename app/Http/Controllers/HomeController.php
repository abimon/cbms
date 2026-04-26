<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\BloodInventory;
use App\Models\BloodBank;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\Withdrawal;

class HomeController extends Controller
{

    public function index()
    {
        // Get available blood inventory grouped by bank and type
        $banks = BloodBank::all();
        $requests = BloodRequest::all();
        $bloodTypes = ['A', 'B', 'AB', 'O', 'NT'];
        $chartData = [];
       $status = ['available', 'tested', 'not_tested'];
        foreach ($banks as $bank) {
            $bankData = ['label' => $bank->name, 'data' => []];
            foreach ($bloodTypes as $type) {
                $quantity = 0;
                $blood = BloodInventory::where([['collection_agency', $bank->name], ['blood_type', $type]])->get();
                foreach ($blood as $bag) {
                    if (in_array($bag->status, $status)) {
                        $quantity += $bag->volume;
                    }
                }
                $bankData['data'][] = $quantity;
            }
            $chartData[] = $bankData;
        }
        $typeLabels = $bloodTypes;
        $totalInventory = BloodInventory::whereIn('status', $status)->sum('volume');
        $pendingRequests = BloodRequest::where('status', 'pending')->count();
        $totalUsers = User::count();
        if (request()->is('api/*')) {
            return response()->json([
                'chartData' => $chartData,
                'typeLabels' => $typeLabels,
                'totalInventory' => $totalInventory,
                'pendingRequests' => $pendingRequests,
                'banks'=>$banks,
                'totalUsers' => $totalUsers
            ]);
        }
        // return [$chartData];
        return view('home', compact('chartData', 'typeLabels', 'totalInventory', 'pendingRequests','banks','requests','totalUsers'));
    }
    public function report(){
        $bags = BloodInventory::whereIn('status', ['available', 'tested', 'not_tested'])->get();
        $withdrawals = Withdrawal::orderBy('created_at', 'desc')->take(10)->get();
        $activities=Activity::orderBy('created_at', 'desc')->take(10)->get();
        foreach($activities as $activity){
            $activity->user_name = $activity->user ? $activity->user->name : 'Unknown';
        }
        foreach($withdrawals as $withdrawal){
            $withdrawal->user_name = $withdrawal->user ? $withdrawal->user->name : 'Unknown';
            $withdrawal->din = $withdrawal->bloodbag ? $withdrawal->bloodbag->din : 'Unknown';
            $withdrawal->bank_name = $withdrawal->bank ? $withdrawal->bank->name : 'Unknown';
        }
        foreach($bags as $bag){
            $bag->bank_name = $bag->bank ? $bag->bank->name : 'Unknown';
        }
        if(request()->is('api/*')){
            return response()->json([
                'bags'=>$bags,
                'withdrawals'=>$withdrawals,
                'activities'=>$activities
            ]);
        }
        return view('report', compact('bags', 'withdrawals','activities'));
    }
    
}
