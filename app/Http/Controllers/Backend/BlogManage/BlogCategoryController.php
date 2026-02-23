<?php

namespace App\Http\Controllers\Backend\BlogManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\BlogCategory;
use App\Helpers\CurrentUser;
use Validator;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('blog-category-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To get current user...
        $userId = CurrentUser::getOwnerId();
        $blogCategoryData = BlogCategory::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allBlogCategoryCount = BlogCategory::orderBy('id', 'desc')->where('user_id', $userId)->count();
        
        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'Success',
                'data' => [
                    'blogCategoryData' => $blogCategoryData,
                    'allBlogCategoryCount' => $allBlogCategoryCount,
                ],
                'message' => 'Blog categories fetched successfully.',
            ]);
        }

        return view('backend.blogManage.category.index',compact('blogCategoryData','allBlogCategoryCount'));
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
        if (!auth()->user()->can('blog-category-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'category_name'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(BlogCategory::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Blog category created successfully.',
                    'status_code' => 200,
                    'accountData' => $data
                ], 200);
            }
            
            Toastr::success('Blog category created successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('blog-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleBlogCategoryData = BlogCategory::where('id', $id)->first();
        if(isset($singleBlogCategoryData) && $singleBlogCategoryData != null){
            return response()->json([
                'message'   =>  'Blog category loaded successfully.',
                'status_code'   => 200,
                'singleBlogCategoryData'   => $singleBlogCategoryData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Blog category not found.!',
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
        if (!auth()->user()->can('blog-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'category_name'=> 'required',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singleBlogCategoryData = BlogCategory::where('id', $id)->first();

        if($singleBlogCategoryData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Blog category updated successfully.',
                    'status_code' => 200,
                    'singleBlogCategoryData' => $singleBlogCategoryData
                ], 200);
            }

            Toastr::success('Blog category updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('blog-category-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleBlogCategoryData = BlogCategory::where('id', $id)->first();
        if($singleBlogCategoryData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Blog category deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Blog category deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('blog-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        BlogCategory::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Blog category activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Blog category activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
    
    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('blog-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        BlogCategory::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Blog category in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Blog category in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
