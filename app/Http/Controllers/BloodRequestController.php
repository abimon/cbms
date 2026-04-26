<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;

class BloodRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloodRequests = BloodRequest::with('bloodType')->paginate(25);
        return view('blood_requests.index', compact('bloodRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodTypes = ['A-', 'A+', 'B-', 'B+', 'AB-', 'AB+', 'O-', 'O+'];
        return view('blood_requests.create', compact('bloodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $validated= request()->validate([
            'request_type' => 'required|in:component,whole_blood',
            'blood_type' => 'required|in:A-,A+,B-,B+,AB-,AB+,O-,O+',
            'quantity' => 'required|integer|min:1',
            'hospital' => 'required|string',
            'contact_phone' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        BloodRequest::create($validated);

        if(request()->is('api/*')){
            return response()->json(['message' => 'Blood request created successfully.'], 201);
        }else{
            return redirect()->route('blood-requests.index')->with('success', 'Blood request created successfully.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        if(request()->is('api/*')){
            return response()->json($bloodRequest);
        }else{
            return view('blood_requests.show', compact('bloodRequest'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        $bloodTypes = ['A-', 'A+', 'B-', 'B+', 'AB-', 'AB+', 'O-', 'O+'];
        return view('blood_requests.edit', compact('bloodRequest', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BloodRequest $bloodRequest)
    {
        $validated=request()->validate([
            'request_type' => 'nullable|in:component,whole_blood',
            'blood_type' => 'nullable|in:A-,A+,B-,B+,AB-,AB+,O-,O+',
            'quantity' => 'nullable|integer|min:1',
            'hospital' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'reason' => 'nullable|string',
            'status' => 'nullable|in:pending,approved,fulfilled,rejected',
        ]);

        // update where not null
        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $bloodRequest->$key = $value;
            }
        }
        $bloodRequest->update();

        if(request()->is('api/*')){
            return response()->json(['message' => 'Blood request updated successfully.'], 200);
        }else{
            return redirect()->route('blood-requests.index')->with('success', 'Blood request updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        BloodRequest::destroy($id);
        if(request()->is('api/*')){
            return response()->json(['message' => 'Blood request deleted successfully.'], 200);
        }else{
            return redirect()->route('blood-requests.index')->with('success', 'Blood request deleted successfully.');
        }
    }
}
