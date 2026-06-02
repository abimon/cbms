<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User_bank;
use Illuminate\Http\Request;

class UserBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request('bank_id')==null){
            return response()->json(['message' => 'Bank ID is required'], 400);
        }
        $bank_users = User_bank::where('bank_id', request('bank_id'))->with('user')->get();
        $users = User::whereIn('id', $bank_users->pluck('user_id')->toArray())->get();
        if (request()->is('api/*')) {
            return response()->json(['users' => $users], 200);
        }
        return view('bank_users.index', compact('users'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User_bank $user_bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User_bank $user_bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User_bank $user_bank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User_bank $user_bank)
    {
        //
    }
}
