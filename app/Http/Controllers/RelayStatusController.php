<?php

namespace App\Http\Controllers;

use App\Models\RelayStatus;
use Illuminate\Http\Request;

class RelayStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $code = strtoupper(substr(uniqid(), 0, 8));
        RelayStatus::create([
            'relay_id'=>request('relay_id'),
            'status'=>request('status'),
            'isDone'=>request('isDone'),
            "code"=>$code
        ]);
        return response()->json([
            'message'=>'Relay Status Created Successfully',
            'code'=>$code
        ],201);
    }
    public function actuate(){
        $relayStatus = RelayStatus::where('board_id',request('board_id'))->first();
        // check if the relay status is done
        if($relayStatus->isDone){
            return response()->json([
                'message'=>'Relay Status is already done'
            ],400);
        }
        // check if the code is expired
        if($relayStatus->created_at < now()->subMinutes(5)){
            return response()->json([
                'message'=>'Response time has expired'
            ],400);
        }
        return [$relayStatus->id,$relayStatus->status];
    }
    public function updateStatus(){
        $relayStatus = RelayStatus::where('code',request('code'))->first();
        $relayStatus->update([
            'isDone'=>true
        ]);
        return response()->json([
            'message'=>'Relay Status Updated Successfully'
        ],200);
    }
    /**
     * Display the specified resource.
     */
    public function show(RelayStatus $relayStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RelayStatus $relayStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RelayStatus $relayStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RelayStatus $relayStatus)
    {
        //
    }
}
