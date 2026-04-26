<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\BagTimeline;
use App\Models\BloodInventory;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $withdrawals = Withdrawal::all();
        } else {
            $withdrawals = Withdrawal::where('user_id', Auth::id())->get();
        }
        if (request()->is('api/*')) {
            return response()->json($withdrawals);
        }
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        try {
            $bag = BloodInventory::where('din',request('din'))->firstOrFail();
            Withdrawal::create([
                'user_id' => Auth::id(),
                'bloodbag_id' => $bag->id,
                'status' => 'requested',
                'bank_id' => request('bank_id')
            ]);
            $bag->status = 'requested';
            $bag->update();
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'Requested withdrawal of blood bag with DIN: '.$bag->din
            ]);
            BagTimeline::create([
                'bag_id' => $bag->id,
                'user_id' => Auth::id(),
                'description' => 'Blood bag withdraw requested by '.Auth::user()->name
            ]);
            if (request()->is('api/*')) {
                return response()->json(['message' => 'Withdrawal Requested Successfully'],201);
            }
            return redirect()->back()->with('success', 'Withdrawal Requested Successfully');
        } catch (\Throwable $th) {
            if (request()->is('api/*')) {
                return response()->json(['message' => $th->getMessage()]);
            }
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        try{
            $withdrawal = Withdrawal::findOrFail($id);
            
            $withdrawal->update([
                'status' => request('status')
            ]);
            if(request('status') == 'approved'){
                $withdrawal->bloodbag->status = 'withdrawn';
                $withdrawal->bloodbag->update();
            }elseif(request('status') == 'rejected'){
                $withdrawal->bloodbag->status = 'available';
                $withdrawal->bloodbag->update();
            }
            Activity::create([
                'user_id' => Auth::id(),
                'activity' => ucfirst(request('status')).'ed withdrawal of blood bag with DIN: ' . $withdrawal->bloodbag->din
            ]);
            BagTimeline::create([
                'bag_id' => $withdrawal->bloodbag_id,
                'user_id' => Auth::id(),
                'description' => 'Blood bag withdraw ' .request('status').' by ' . Auth::user()->name
            ]);
            if(request()->is('api/*')) {
                return response()->json(['message' => 'Withdrawal '.request('status').' Successfully']);
            }
            return redirect()->back()->with('success', 'Withdrawal '.request('status').' Successfully');
        }catch (\Throwable $th) {
            if (request()->is('api/*')) {
                return response()->json(['message' => $th->getMessage()]);
            }
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdrawal $withdrawal)
    {
        //
    }
}
