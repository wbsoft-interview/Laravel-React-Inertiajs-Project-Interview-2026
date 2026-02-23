<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\PhotoGallery;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Image;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('photo-gallery-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fetch user id...
        $userId = CurrentUser::getOwnerId();
        $galleryData = PhotoGallery::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allGalleryCount = PhotoGallery::orderBy('id', 'desc')->where('user_id', $userId)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Gallery photo fetched successfully.',
                'status_code' => 200,
                'galleryData' => $galleryData,
                'allGalleryCount' => $allGalleryCount
            ], 200);
        }


        return view('backend.setting.gallery.index',compact('galleryData','allGalleryCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        if (!auth()->user()->can('photo-gallery-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

       $request->validate([
            'title'=> 'nullable',
            'photo' => 'required|mimes:jpg,jpeg,png,gif,svg,webp',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To check logo image...
        foreach (['photo'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/galleryPhoto');
            }
        }

        if(PhotoGallery::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Gallery photo added successfully.',
                    'status_code' => 200,
                    'newsData' => $data
                ], 200);
            }

            Toastr::success('Gallery photo added successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('gallery.index');
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

    //To file upload...
    private function uploadFile($file, $path)
    {
        $fileName = now()->timestamp . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = "$path/$fileName";
        Storage::disk('public')->put($filePath, file_get_contents($file));
        return $fileName;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        if (!auth()->user()->can('photo-gallery-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'title'=> 'nullable',
            'photo' => 'nullable|mimes:jpg,jpeg,png,gif,svg,webp',
        ]);

        $singleGalleryData = PhotoGallery::where('id',$id)->first();
        //To fetch user id...
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To check logo image...
        foreach (['photo'] as $field) {
            if ($request->hasFile($field)) {
                if (!empty($singleGalleryData->$field)) {
                    $filePath = 'uploads/galleryPhoto/' . $singleGalleryData->$field;
                    Storage::disk('public')->delete($filePath);
                }
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/galleryPhoto');
            }
        }

        if($singleGalleryData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'gallery photo updated successfully.',
                    'status_code' => 200,
                    'singleGalleryData' => $singleGalleryData
                ], 200);
            }

            Toastr::success('gallery photo updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('photo-gallery-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleGalleryData = PhotoGallery::find($id);
        if (!$singleGalleryData) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Data not found.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, Data not found.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        //To check logo image...
        foreach (['photo'] as $field) {
            if (!empty($singleGalleryData->$field)) {
                $filePath = 'uploads/galleryPhoto/' . $singleGalleryData->$field;
                Storage::disk('public')->delete($filePath);
            }
        }

        if($singleGalleryData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Gallery photo deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Gallery photo deleted successfully.', 'Success', ["progressbar" => true]);
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

    //To active status...
    public function active(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('photo-gallery-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        PhotoGallery::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Gallery photo activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Gallery photo activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('photo-gallery-access', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        PhotoGallery::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Gallery photo activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Gallery photo activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

}
