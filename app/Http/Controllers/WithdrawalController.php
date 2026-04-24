<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
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
            Withdrawal::create([
                'user_id' => Auth::id(),
                'bloodbag_id' => request('bloodbag_id'),
                'status' => 'requested'
            ]);
            if (request()->is('api/*')) {
                return response()->json(['message' => 'Withdrawal Requested Successfully']);
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
    public function show(Withdrawal $withdrawal)
    {
        //
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
    public function update(Request $request, Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdrawal $withdrawal)
    {
        //
    }
}
