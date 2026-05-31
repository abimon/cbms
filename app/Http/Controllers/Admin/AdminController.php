<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodBank;
use App\Models\BloodInventory;
use Illuminate\Support\Arr as SupportArr;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // clear session bank_id to ensure admin sees all data
        session()->forget('bank_id');
        $banks = BloodBank::simplePaginate(request('per_page', 10));
        $bbanks = BloodBank::pluck('name')->toArray();
        $missing = BloodInventory::whereNotIn('collection_agency', $bbanks)->get();
        // return $missing;
        foreach($missing as $item){
            $item->collection_agency = SupportArr::random($bbanks);
            $item->update();
        }
        return view('admin.dashboard', compact('banks'));
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
