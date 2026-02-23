<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Upozila;
use App\Models\Zone;
use App\Models\District;
use App\Models\Division;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Image;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('zone-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fetch user id...
        $userId = CurrentUser::getUserId();
        $zoneData = Zone::orderBy('id', 'desc')->get();
        $allZoneCount = Zone::orderBy('id', 'desc')->count();
        $districtData = District::orderBy('name_en', 'asc')->get();
        $upozilaData = Upozila::orderBy('name_en', 'asc')->get();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Zone data fetched successfully.',
                'status_code' => 200,
                'zoneData' => $zoneData,
                'allZoneCount' => $allZoneCount,
                'districtData' => $districtData,
                'upozilaData' => $upozilaData,
            ], 200);
        }

        return view('backend.setting.zone.index', compact('zoneData','allZoneCount','districtData','upozilaData'));
    }
    
    
    public function upozilaList(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('zone-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fetch user id...
        $userId = CurrentUser::getUserId();
        $upozilaData = Upozila::orderBy('name_en', 'asc')->paginate(10);
        $allUpozilaCount = Upozila::orderBy('id', 'desc')->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Zone data fetched successfully.',
                'status_code' => 200,
                'upozilaData' => $upozilaData,
            ], 200);
        }

        return view('backend.setting.zone.upozila', compact('allUpozilaCount','upozilaData'));
    }
    
    public function districtList(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('zone-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fetch user id...
        $userId = CurrentUser::getUserId();
        $districtData = District::orderBy('name_en', 'asc')->paginate(10);
        $allDistrictCount = District::orderBy('id', 'desc')->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Zone data fetched successfully.',
                'status_code' => 200,
                'districtData' => $districtData,
            ], 200);
        }

        return view('backend.setting.zone.district', compact('allDistrictCount','districtData'));
    }
    
    public function divisionList(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('zone-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fetch user id...
        $userId = CurrentUser::getUserId();
        $divisionData = Division::orderBy('name_en', 'asc')->get();
        $allDivisionCount = Division::orderBy('id', 'desc')->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Division data fetched successfully.',
                'status_code' => 200,
                'divisionData' => $divisionData,
                'allDivisionCount' => $allDivisionCount,
            ], 200);
        }

        return view('backend.setting.zone.division', compact('allDivisionCount','divisionData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('zone-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'upozila_id'=> 'required',
            'name_en'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getUserId();
        $upozila = Upozila::where('id', $request->upozila_id)->first();
        $data = $request->all();
        $data['district_id'] = $upozila->district_id;
        $data['user_id'] = $userId;

        if(Zone::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Zone created successfully.',
                    'status_code' => 200,
                    'zoneData' => $data
                ], 200);
            }

            Toastr::success('Zone created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('zone-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleZoneData = Zone::where('id', $id)->first();
        if(isset($singleZoneData) && $singleZoneData != null){
            return response()->json([
                'message'   =>  'Zone loaded successfully.',
                'status_code'   => 200,
                'singleZoneData'   => $singleZoneData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Zone not found.!',
                'status_code'   => 500
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('zone-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'upozila_id'=> 'required',
            'name_en'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getUserId();
        $upozila = Upozila::where('id', $request->upozila_id)->first();
        $data = $request->all();
        $data['district_id'] = $upozila->district_id;
        $data['user_id'] = $userId;
        $singleZoneData = Zone::where('id', $id)->first();

        if($singleZoneData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Zone updated successfully.',
                    'status_code' => 200,
                    'singleZoneData' => $singleZoneData
                ], 200);
            }

            Toastr::success('Zone updated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('zone-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleZoneData = Zone::where('id', $id)->first();
        if (!$singleZoneData) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Data not found.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, Data not found.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        if($singleZoneData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Zone deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Zone deleted successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
