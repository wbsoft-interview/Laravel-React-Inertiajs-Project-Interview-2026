<?php

namespace App\Http\Controllers\Backend\BlogManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Validator;
use Image;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('blog-list', 'web')) {
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
        $blogData = Blog::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allBlogCount = Blog::orderBy('id', 'desc')->where('user_id', $userId)->count();
        $blogCategoryData = BlogCategory::orderBy('category_name', 'asc')->where('user_id', $userId)->where('status', true)->get();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'Success',
                'data' => [
                    'blogData' => $blogData,
                    'allBlogCount' => $allBlogCount,
                    'blogCategoryData' => $blogCategoryData,
                ],
                'message' => 'Blog categories fetched successfully.',
            ]);
        }

        return view('backend.blogManage.blog.index',compact('blogData','allBlogCount','blogCategoryData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('blog-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $blogCategoryData = BlogCategory::orderBy('category_name','asc')->where('status',true)->get();

        return view('backend.blogManage.blog.create', compact('blogCategoryData'));
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
        if (!auth()->user()->can('blog-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'title'=> 'required',
            'blog_category_id'=> 'required',
            'post'=> 'required',
            'photo' => 'required|mimes:jpg,jpeg,png,gif,svg,webp',
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To check logo image...
        foreach (['photo'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/blogImg');
            }
        }

        if(Blog::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Blog created successfully.',
                    'status_code' => 200,
                    'blogData' => $data
                ], 200);
            }
            
            Toastr::success('Blog created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('blog.index');
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
        $fileName = now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
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
    public function show(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('blog-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleBlogDataData = Blog::where('id', $id)->first();
        if(isset($singleBlogDataData) && $singleBlogDataData != null){
            return response()->json([
                'message'   =>  'Blog loaded successfully.',
                'status_code'   => 200,
                'singleBlogDataData'   => $singleBlogDataData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Blog not found.!',
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
     public function edit(Request $request, $id)
     {
         //To check user permission...
        if (!auth()->user()->can('blog-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        //To get photo gallery & single blog data...
        $singleBlogData = Blog::find($id);
        $blogCategoryData = BlogCategory::orderBy('category_name','asc')->where('status',true)->get();

        return view('backend.blogManage.blog.edit' , compact('singleBlogData','blogCategoryData'));
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
        if (!auth()->user()->can('blog-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'title'=> 'required',
            'blog_category_id'=> 'required',
            'post'=> 'required',
            'photo' => 'required|mimes:jpg,jpeg,png,gif,svg,webp',
        ]);

        //To fetch userId..
        $userId = CurrentUser::getOwnerId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $singleBlogData = Blog::where('id', $id)->first();

        //To check logo image...
        foreach (['photo'] as $field) {
            if ($request->hasFile($field)) {
                if (!empty($singleBlogData->$field)) {
                    $filePath = 'uploads/blogImg/' . $singleBlogData->$field;
                    Storage::disk('public')->delete($filePath);
                }
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/blogImg');
            }
        }

        if($singleBlogData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Blog updated successfully.',
                    'status_code' => 200,
                    'singleBlogData' => $singleBlogData
                ], 200);
            }

            Toastr::success('Blog updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('blog-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleBlogData = Blog::where('id', $id)->first();
        // Delete files from storage
        foreach (['photo'] as $field) {
            if (!empty($singleBlogData->$field)) {
                $filePath = 'uploads/blogImg/' . $singleBlogData->$field;
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }
        
        if($singleBlogData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Blog deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Blog deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('blog-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Blog::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Blog activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Blog activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
    
    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('blog-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Blog::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Blog activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Blog activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
