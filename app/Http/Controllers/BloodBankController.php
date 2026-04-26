<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\BloodBank;
use App\Models\User_bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'SuperAdmin') {
            $banks = BloodBank::paginate(25);
        } else {
            $ids = Auth::user()->blood_banks->pluck('bank_id')->toArray();
            $banks = BloodBank::whereIn('id', $ids)->paginate(25);
        }
        if (request()->is('api/*')) {
            $_banks = [];
            foreach ($banks as $bank) {
                array_push($_banks, [
                    'id' => $bank->id,
                    'name' => $bank->name
                ]);
            }
            return response()->json(['_banks'=>$_banks,'banks'=>$banks]);
        }
        return view('bloodBank.index', compact('banks'));
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
        try {
            $validate = request()->validate([
                'name' => 'required|unique:blood_banks',
                'contact_person' => 'required',
                'location' => 'required',
                'contact_phone' => 'required',
                'email' => 'required|email|unique:blood_banks'
            ]);
            $bank = BloodBank::create($validate);

            if (Auth::user()->role == 'Admin') {
                User_bank::create([
                    'user_id' => Auth::user()->id,
                    'bank_id' => $bank->id
                ]);
            }
            Activity::create([
                'user_id' => Auth::user()->id,
                'description' => 'Added New Blood Bank ' . $bank->name
            ]);
            if (request()->is('api/*')) {
                return response()->json(['message' => 'Blood Bank Added Successfully']);
            }
            return redirect()->back()->with('success', 'Blood Bank Added Successfully');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodBank $bloodBank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodBank $bloodBank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BloodBank $bloodBank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodBank $bloodBank)
    {
        //
    }
}
