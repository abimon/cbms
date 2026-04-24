<?php

namespace App\Http\Controllers;

use App\Models\BloodInventory;
use App\Models\BloodBank;
use App\Models\BloodRequest;
use App\Models\User;

class HomeController extends Controller
{

    public function index()
    {
        // Get available blood inventory grouped by bank and type
        $banks = BloodBank::all();
        $requests = BloodRequest::all();
        $bloodTypes = ['A', 'B', 'AB', 'O', 'NT'];
        $chartData = [];
       
        foreach ($banks as $bank) {
            $bankData = ['label' => $bank->name, 'data' => []];
            foreach ($bloodTypes as $type) {
                $quantity = BloodInventory::where([['status','available'],['collection_agency',$bank->name],['blood_type',$type]])->sum('volume');
                $bankData['data'][] = $quantity;
            }
            $chartData[] = $bankData;
        }

        $typeLabels = $bloodTypes;
        $totalInventory = BloodInventory::where('status', 'available')->sum('volume');
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
    
}
