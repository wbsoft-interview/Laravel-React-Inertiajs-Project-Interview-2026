<?php

namespace App\Http\Controllers\Backend\DocumentationManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\DocumentationCategory;
use App\Helpers\BanglaSlug;
use App\Helpers\CurrentUser;
use App\Helpers\DocumentationCategoryType;
use Validator;

class DocumentationCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-category-list', 'web')) {
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
        $newsCategoryData = DocumentationCategory::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allNewsCategoryCount = DocumentationCategory::orderBy('id', 'desc')->where('user_id', $userId)->count();
        $newsTypeData = DocumentationCategoryType::getDocumentationTypeData();
        $getNewsCategoryData = DocumentationCategory::orderBy('id', 'desc')->where('user_id', $userId)->where('documentation_type','Category')->where('status', true)->get();
   
        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation categories fetched successfully.',
                'status_code' => 200,
                'newsCategoryData' => $newsCategoryData,
                'allNewsCategoryCount' => $allNewsCategoryCount,
                'newsTypeData' => $newsTypeData,
                'getNewsCategoryData' => $getNewsCategoryData,
            ], 200);
        }

        return view('backend.documentationManage.category.index',compact('newsCategoryData','allNewsCategoryCount','newsTypeData','getNewsCategoryData'));
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
        // Permission check
        if (!auth()->user()->can('documentation-category-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }
            return redirect()->route('error.accessDenied');
        }

        // Validation
        $request->validate([
            'documentation_type' => 'required|string',
            'category_name_en' => [
                'required',
                'string',
                Rule::unique('documentation_categories', 'category_name_en')
                    ->where(function ($query) use ($request) {
                        if ($request->parent_category_id) {
                            return $query->where('parent_category_id', $request->parent_category_id);
                        } else {
                            return $query->whereNull('parent_category_id');
                        }
                    }),
            ],
            'details_en' => 'nullable|string',
            'parent_category_id' => 'nullable|exists:documentation_categories,id',
        ]);


        // Fetch user id
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $slugEn = Str::slug($request->category_name_en);

        // Generate slug using **English category name only**
        $baseSlug = $slugEn;
        $count = 2;
        while (DocumentationCategory::where('slug_en', $slugEn)->exists()) {
            $slugEn = $baseSlug . '-' . $count;
            $count++;
        }

        $category = DocumentationCategory::create([
            'user_id' => $userId,
            'parent_category_id' => $request->parent_category_id,
            'documentation_type' => $request->documentation_type,
            'category_name_en' => $request->category_name_en,
            'slug_en' => $slugEn,
            'details_en' => $request->details_en,
            'status' => true,
        ]);


        if ($category) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Documentation category created successfully.',
                    'status_code' => 200,
                    'categoryData' => $category
                ], 200);
            }

            Toastr::success('Documentation category created successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('documentation-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleDocumentationCategoryData = DocumentationCategory::where('id', $id)->first();
        if(isset($singleDocumentationCategoryData) && $singleDocumentationCategoryData != null){
            return response()->json([
                'message'   =>  'Documentation category loaded successfully.',
                'status_code'   => 200,
                'singleDocumentationCategoryData'   => $singleDocumentationCategoryData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Documentation category not found.!',
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
        if (!auth()->user()->can('documentation-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'documentation_type' => 'required|string',
            'category_name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('documentation_categories', 'category_name_en')
                    ->ignore($id)
                    ->where(function ($query) use ($request) {
                        if ($request->parent_category_id) {
                            return $query->where('parent_category_id', $request->parent_category_id);
                        } else {
                            return $query->whereNull('parent_category_id');
                        }
                    }),
            ],
            'details_en' => 'nullable|string',
            'parent_category_id' => 'nullable|exists:documentation_categories,id',
        ]);

        // Fetch user id
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $slugEn = Str::slug($request->category_name_en);

        $baseSlug = $slugEn;
        $count = 2;
        while (DocumentationCategory::where('slug_en', $slugEn)->where('id', '!=', $id)->exists()) {
            $slugEn = $baseSlug . '-' . $count;
            $count++;
        }

        $parentCategoryId = $request->documentation_type === 'Category' ? null : $request->parent_category_id;
        $singleDocumentationCategoryData = DocumentationCategory::where('id', $id)->first();

        $singleDocumentationCategoryData->update([
            'user_id' => $userId,
            'parent_category_id' => $parentCategoryId,
            'documentation_type' => $request->documentation_type,
            'category_name_en' => $request->category_name_en,
            'slug_en' => $slugEn,
            'details_en' => $request->details_en ?? '',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation category updated successfully.',
                'status_code' => 200,
                'singleDocumentationCategoryData' => $singleDocumentationCategoryData
            ], 200);
        }

        Toastr::success('Documentation category updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('documentation-category-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleDocumentationCategoryData = DocumentationCategory::where('id', $id)->first();
        if($singleDocumentationCategoryData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Documentation category deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Documentation category deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('documentation-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        DocumentationCategory::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation category activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Documentation category activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-category-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        DocumentationCategory::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation category in-activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Documentation category in-activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }
}
