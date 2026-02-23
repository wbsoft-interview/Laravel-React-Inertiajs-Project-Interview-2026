<?php

namespace App\Http\Controllers\Backend\DocumentationManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use App\Models\DocumentationTag;
use App\Helpers\BanglaSlug;
use App\Helpers\CurrentUser;
use Validator;

class DocumentationTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-tag-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To fetch user id...
        $userId = CurrentUser::getSuperadminId();
        $newsTagData = DocumentationTag::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allNewsTagCount = DocumentationTag::orderBy('id', 'desc')->where('user_id', $userId)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation tags fetched successfully.',
                'status_code' => 200,
                'newsTagData' => $newsTagData,
                'allNewsTagCount' => $allNewsTagCount,
            ]);
        }

        return view('backend.documentationManage.tag.index',compact('newsTagData','allNewsTagCount'));
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
        if (!auth()->user()->can('documentation-tag-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'tag_name_en' => 'required|string|max:255',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;

        $slugEn = Str::slug($request->tag_name_en);

        $baseSlugEn = $slugEn;
        $count = 2;
        while (DocumentationTag::where('slug_en', $slugEn)->exists()) {
            $slugEn = $baseSlugEn . '-' . $count;
            $count++;
        }

        $tag = DocumentationTag::create([
            'user_id' => $userId,
            'tag_name_en' => $request->tag_name_en,
            'slug_en' => $slugEn,
        ]);

        if ($tag) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Documentation tag created successfully.',
                    'status_code' => 200,
                    'accountData' => $tag
                ], 200);
            }

            Toastr::success('Documentation tag created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        } else {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Something went wrong.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, Something went wrong!', 'Error', ["progressbar" => true]);
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
        if (!auth()->user()->can('documentation-tag-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleDocumentationTagData = DocumentationTag::where('id', $id)->first();
        if(isset($singleDocumentationTagData) && $singleDocumentationTagData != null){
            return response()->json([
                'message'   =>  'Documentation tag loaded successfully.',
                'status_code'   => 200,
                'singleDocumentationTagData'   => $singleDocumentationTagData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Documentation tag not found.!',
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
        if (!auth()->user()->can('documentation-tag-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'tag_name_en' => 'required|string|max:255',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;

        $slugEn = Str::slug($request->tag_name_en);

        $baseSlugEn = $slugEn;
        $count = 2;
        while (DocumentationTag::where('slug_en', $slugEn)->where('id', '!=', $id)->exists()) {
            $slugEn = $baseSlugEn . '-' . $count;
            $count++;
        }

        $singleDocumentationTagData = DocumentationTag::where('id', $id)->first();
        $singleDocumentationTagData->update([
            'user_id' => $userId,
            'tag_name_en' => $request->tag_name_en,
            'slug_en' => $slugEn,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation tag updated successfully.',
                'status_code' => 200,
                'singleDocumentationTagData' => $singleDocumentationTagData
            ], 200);
        }

        Toastr::success('Documentation tag updated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
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
        if (!auth()->user()->can('documentation-tag-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleDocumentationTagData = DocumentationTag::where('id', $id)->first();
        if($singleDocumentationTagData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Documentation tag deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Documentation tag deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('documentation-tag-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        DocumentationTag::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation tag activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Documentation tag activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-tag-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        DocumentationTag::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation  tag in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Documentation  tag in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
