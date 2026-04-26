<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\BagTimeline;
use App\Models\BloodInventory;
use App\Models\BloodBank;
use App\Models\BloodStorage;
use App\Models\User_bank;
use Illuminate\Support\Facades\Auth;

class BloodInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloodInventories = BloodInventory::paginate(25);
        if (request()->is('api/*')) {
            return response()->json([
                'banks' => $bloodInventories,
                'message' => 'Blood Inventories Retrieved Successfully',
                'status' => 'success'
            ], 200);
        }
        return view('blood_inventories.index', compact('bloodInventories'));
    }
    public function dashboard()
    {
        $bloodgroups = ['A', 'B', 'AB', 'O'];
        $data = [];

        $status = ['available', 'tested', 'not_tested'];
        foreach ($bloodgroups as $bloodgroup) {
            $neg=0;
            $pos=0;
            $blood = BloodInventory::where('blood_type', $bloodgroup)->get();
            foreach ($blood as $bag) {
                if (in_array($bag->status, $status)) {
                    if ($bag->rhesus == 'Negative') {
                        $neg += $bag->volume;
                    } else if ($bag->rhesus == 'Positive') {
                        $pos += $bag->volume;
                    }
                }
            }
            array_push($data, [ 'group' => $bloodgroup.'-', 'units' => $neg]);
            array_push($data, ['group' => $bloodgroup.'+', 'units' => $pos]);
        }
        $nt = BloodInventory::where('blood_type', 'NT')->sum('volume');
        array_push($data, ['group' => 'NT', 'units' => $nt]);
        if (request()->is('api/*')) {
            return response()->json(['data' => $data]);
        }

        return view('dashboard', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodBanks = BloodBank::all();
        return view('blood_inventories.create', compact('bloodBanks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        try {

            // if a scan payload was submitted, try to decode and merge it so normal validation can apply
            if (request()->filled('scan_data')) {
                $payload = request()->input('scan_data');
                // assume JSON, but be tolerant of simple key=value pairs if required
                try {
                    $decoded = json_decode($payload, true);
                    if (is_array($decoded)) {
                        request()->merge($decoded);
                    }
                } catch (\Throwable $e) {
                    // ignore, will validate using whatever fields are present
                }
            }

            $val = request()->validate([
                'din' => 'required|string|max:255|unique:blood_inventories,din',
                'type' => 'required|string',
                'volume' => 'required|string',
                'blood_type' => 'required|string|in:A,B,AB,O,NT',
                'rhesus' => 'required|string|in:Positive,Negative,NT',
                'date_collected' => 'required|date',
                'location' => 'required|string',
                'collection_agency' => 'required|string',
                'HIV' => 'required|in:Negative,Positive,NT',
                'HCV' => 'required|in:Negative,Positive,NT',
                'HBV' => 'required|in:Negative,Positive,NT',
                'Syphilis' => 'required|in:Negative,Positive,NT',
                'Malaria' => 'required|in:Negative,Positive,NT',
                'status' => 'nullable|in:tested,not_tested,available,used,expired',
            ]);
            if (!$val) {
                if (request()->is('api/*')) {
                    return response()->json(['message' => 'Validation failed', 'errors' => $val], 400);
                }
                return back()->withErrors($val)->withInput();
            }
            $user =User_bank::where([['user_id', Auth::id()],['bank_id', BloodBank::where('name', request('collection_agency'))->first()->id],['status', 'approved']])->firstOrFail();
            if(!$user){
                if (request()->is('api/*')) {
                    return response()->json(['message' => 'You are not associated to this collection agency'], 404);
                }
                return redirect()->route('blood-inventories.index')->with('error', 'You are not associated to this collection agency.');
            }
            // add 35 days to date collected
            $val['expiry_date'] = date('Y-m-d', strtotime($val['date_collected'] . ' + 35 days'));
            $val['status'] = request('status') ?? 'not_tested';
            $inv = BloodInventory::create($val);
            BloodStorage::create([
                'bloodbag_id' => $inv->id,
                'bank_id' => BloodBank::where('name', $inv->collection_agency)->first()->id
            ]);
            BagTimeline::create([
                'bag_id' => $inv->id,
                'user_id' => Auth::id(),
                'description' => 'Blood bag created and stored in ' . $inv->collection_agency
            ]);
            if (request()->is('api/*')) {
                return response()->json([
                    'message' => 'Blood inventory created successfully',
                    'status' => 'success',
                ], 201);
            }
            return redirect()->route('blood-inventories.index')->with('success', 'Blood inventory created successfully.');
        } catch (\Throwable $th) {
            if (request()->is('api/*')) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bloodInventory = BloodInventory::findOrFail($id);
        if (!$bloodInventory) {
            if (request()->is('api/*')) {
                return response()->json(['message' => 'Blood record not found'], 404);
            }
            return redirect()->route('blood-inventories.index')->with('error', 'Blood record not found.');
        }
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $bloodInventory->getQrPayloadAttribute();
        if (request()->is('api/*')) {
            return response()->json([
                'data' => $bloodInventory,
                'message' => 'Blood inventory retrieved successfully',
                'status' => 'success'
            ], 200);
        }
        return view('blood_inventories.show', compact('bloodInventory', 'qrCodeUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bloodInventory = BloodInventory::findOrFail($id);
        $bloodBanks = BloodBank::all();
        return view('blood_inventories.edit', compact('bloodInventory', 'bloodBanks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        try {
            $bloodInventory = BloodInventory::findOrFail($id);
            $message = '';
            if (request()->filled('scan_data')) {
                $payload = request()->input('scan_data');
                try {
                    $decoded = json_decode($payload, true);
                    if (is_array($decoded)) {
                        request()->merge($decoded);
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }
            if (request('din') != null && request('din') != $bloodInventory->din) {
                $bloodInventory->din = request('din');
                $message = $message . ', din';
            }
            if (request('type') != null && request('type') != $bloodInventory->type) {
                $bloodInventory->type = request('type');
                $message = $message . ', type';
            }
            if (request('volume') != null && request('volume') != $bloodInventory->volume) {
                $bloodInventory->volume = request('volume');
                $message = $message . ', volume';
            }
            if (request('blood_type') != null && request('blood_type') != $bloodInventory->blood_type) {
                $bloodInventory->blood_type = request('blood_type');
                $message = $message . ', blood_type';
            }
            if (request('rhesus') != null && request('rhesus') != $bloodInventory->rhesus) {
                $bloodInventory->rhesus = request('rhesus');
                $message = $message . ', rhesus';
            }
            if (request('date_collected') != null && request('date_collected') != $bloodInventory->date_collected) {
                $bloodInventory->date_collected = request('date_collected');
                $message = $message . ', date_collected';
            }
            if (request('location') != null && request('location') != $bloodInventory->location) {
                $bloodInventory->location = request('location');
                $message = $message . ', location';
            }
            if (request('collection_agency') != null && request('collection_agency') != $bloodInventory->collection_agency) {
                $bloodInventory->collection_agency = request('collection_agency');
                $message = $message . ', collection_agency';
            }
            if (request('HIV') != null && request('HIV') != $bloodInventory->HIV) {
                $bloodInventory->HIV = request('HIV');
                $message = $message . ', HIV';
            }
            if (request('HCV') != null && request('HCV') != $bloodInventory->HCV) {
                $bloodInventory->HCV = request('HCV');
                $message = $message . ', HCV';
            }
            if (request('HBV') != null && request('HBV') != $bloodInventory->HBV) {
                $bloodInventory->HBV = request('HBV');
                $message = $message . ', HBV';
            }
            if (request('Syphilis') != null && request('Syphilis') != $bloodInventory->Syphilis) {
                $bloodInventory->Syphilis = request('Syphilis');
                $message = $message . ', Syphilis';
            }
            if (request('Malaria') != null && request('Malaria') != $bloodInventory->Malaria) {
                $bloodInventory->Malaria = request('Malaria');
                $message = $message . ', Malaria';
            }
            if (request('status') != null && request('status') != $bloodInventory->status) {
                $bloodInventory->status = request('status');
                $message = $message . ', status';
            }
            if (request('release_date') != null && request('release_date') != $bloodInventory->release_date) {
                $bloodInventory->release_date = request('release_date');
                $message = $message . ', release date';
            }
            if ($message != '') {
                $bloodInventory->update();
                BagTimeline::create([
                    'bag_id' => $id,
                    'user_id' => Auth::id(),
                    'description' => 'Blood bag' . $message . ' updated'
                ]);
                Activity::create([
                    'user_id' => Auth::id(),
                    'description' => 'Updated blood bag with DIN: ' . $bloodInventory->din . ' (' . $message . ')'
                ]);
                $response = 'Blood inventory updated successfully.';
            } else {
                $response = 'No changes made.';
            }
            if (request()->is('api/*')) {
                return response()->json(['message' => $response]);
            } else {
                return redirect()->route('blood-inventories.index')->with('success', $response);
            }
        } catch (\Throwable $th) {
            if (request()->is('api/*')) {
                return response()->json(['message' => $th->getMessage()]);
            }
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        BloodInventory::destroy($id);
        return redirect()->route('blood-inventories.index')->with('success', 'Blood inventory deleted successfully.');
    }
    public function query()
    {

        $bloodInventories = BloodInventory::where('blood_type', request('blood_type'))->get();
        if (request()->is('api/*')) {
            return response()->json(['data' => $bloodInventories]);
        } else {
            return view('blood-inventories.index', compact('bloodInventories'));
        }
    }
}
