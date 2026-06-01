<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodBank;
use App\Models\BloodInventory;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {

        if (!request()->is('api/*')) {
            session()->forget('bank_id');
        }
        // foreach(BloodInventory::all() as $item){
        //     $item->volume += 13;
        //     $item->update();
        // }
        $_banks = BloodBank::simplePaginate(25);
        $status = ['available', 'tested', 'not_tested'];
        $bloodgroups = ['A', 'B', 'O', 'AB'];
        $banks = collect();
        foreach ($_banks as $bank) {
            $inventories = [];
            foreach ($bloodgroups as $group) {
                $qp = BloodInventory::where([
                    ['collection_agency', $bank->name],
                    ['blood_type', $group],['rhesus', 'Positive']])->whereIn('status',$status)->sum('volume');
                $qn = BloodInventory::where([
                    ['collection_agency', $bank->name],
                    ['blood_type', $group],['rhesus', 'Negative']])->whereIn('status',$status)->sum('volume');
                array_push($inventories, [
                    'blood_group' => $group . '+',
                    'quantity' => $qp,
                    'threshold' => collect(json_decode($bank->threshold))->where('blood_group', $group . '+')->first()->threshold
                ]);
                array_push($inventories, [
                    'blood_group' => $group . '-',
                    'quantity' => $qn,
                    'threshold' => collect(json_decode($bank->threshold))->where('blood_group', $group . '-')->first()->threshold
                ]);
            }
            array_push($inventories, [
                'blood_group' => 'NT',
                'quantity' => $bank->inventories->where('blood_type', 'NT')->sum('volume'),
                'threshold' => 1
            ]);
            $data = [
                'id' => $bank->id,
                'name' => $bank->name,
                'location' => $bank->location,
                'contact_phone' => $bank->contact_phone,
                'requests' => $bank->requests->count(),
                'users' => $bank->users->count(),
                'withdrawals' => $bank->withdrawals->count(),
                'inventory' => $inventories,
            ];
            $banks->push($data);
        }
        if (request()->is('api/*')) {
            return response()->json(['banks' => $banks]);
        }
        return view('admin.dashboard', compact('banks','_banks'));
    }

    public function loginAs(int $bankId)
    {
        $user = Auth::user();
        if (! $user || ! $user->is_admin) {
            abort(403);
        }
        session(['bank_id' => (int) $bankId]);
        return redirect()->route('dashboard');
    }
}
