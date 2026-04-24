<?php

namespace App\Http\Controllers;

// use App\Models\BloodBank;
// use App\Models\BloodInventory;
use App\Models\BloodStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BloodStorageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloodbags = BloodStorage::select('bloodbag_id','bank_id','status')->get();
        if(request()->is('api/*')){
            foreach( $bloodbags as $bloodbag){
                $bloodbag->bag = $bloodbag->bloodbag;
                $bloodbag->b_bank = $bloodbag->bank;
            }
            return response()->json(['data' => $bloodbags]);
        }
        return view('blood_storage.index',compact('bloodbags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blood_storage.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        try {
            $validate= Validator::make(request()->all(),[
                'bloodbag_id'=> 'required|exists:blood_inventories,id',
                'bank_id'=> 'required|exists:blood_banks,id',
                'status'=>'required|in:transfered,available,expired'
            ]);
            if($validate->fails()){
                return response()->json(['message'=>$validate->errors()->first()],400);
            }
            BloodStorage::create([
                'bloodbag_id'=>request('bloodbag_id'),
                'user_id'=>Auth::id(),
                'bank_id'=>request('bank_id'),
                'status'=>request('status')
            ]);
            if(request()->is('api/*')){
                return response()->json(['message'=>'Blood Storage Created Successfully']);
            }
            return redirect()->route('blood_storage.index')->with('success','Blood Storage Created Successfully');
        } catch (\Throwable $th) {
            if(request()->is('api/*')){
                return response()->json(['message'=>$th->getMessage()],500);
            }
            return redirect()->route('blood_storage.index')->with('error',$th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodStorage $bloodStorage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodStorage $bloodStorage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BloodStorage $bloodStorage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodStorage $bloodStorage)
    {
        //
    }
}
