<?php

namespace App\Http\Controllers\Backend\DocumentationManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use App\Models\DocumentationCategory;
use App\Models\Documentation;
use App\Models\DocumentationTag;
use App\Models\User;
use App\Models\PhotoGallery;
use App\Models\Upozila;
use App\Models\District;
use App\Models\Division;
use App\Helpers\CurrentUser;
use App\Helpers\DocumentationType;
use App\Helpers\VisibilityType;
use App\Helpers\DocumentationLayout;
use App\Helpers\BanglaSlug;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Image;

class DocumentationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-list', 'web')) {
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
        $newsData = Documentation::orderBy('id', 'desc')->where('user_id', $userId)->paginate(10);
        $allNewsCount = Documentation::orderBy('id', 'desc')->where('user_id', $userId)->count();
        $newsCategoryData = DocumentationCategory::orderBy('category_name_en', 'asc')->get();
        $newsTypeData = DocumentationType::getDocumentationTypeData();

        $publishedNewsCount = Documentation::where('is_published',1)->count();
        $unpublishedNewsCount = Documentation::where('is_published',2)->count();
        $draftNewsCount = Documentation::where('is_published',3)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'News data fetched successfully.',
                'status_code' => 200,
                'newsData' => $newsData,
                'allNewsCount' => $allNewsCount,
                'publishedNewsCount' => $publishedNewsCount,
                'unpublishedNewsCount' => $unpublishedNewsCount,
                'draftNewsCount' => $draftNewsCount,
                'newsCategoryData' => $newsCategoryData,
                'newsTypeData' => $newsTypeData,
            ], 200);
        }

        return view('backend.documentationManage.news.index',compact('newsData','allNewsCount','publishedNewsCount','unpublishedNewsCount','draftNewsCount','newsCategoryData','newsTypeData'));
    }
    
    //To get bangla list...
    public function banglaListPB(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-list', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        if ($request->ajax()) {
            $query = Documentation::orderBy('id', 'desc')->where('is_published', 1);

            return DataTables::of($query)
                ->addColumn('photo', function($row) {
                    $img = $row->photo ? asset('storage/uploads/newsImg/'.$row->photo) 
                                    : asset('backend/template-assets/images/img_preview.png');
                    return '<a href="'.$img.'" data-rel="lightcase">
                                <img src="'.$img.'" height="100" width="100" alt="News Image"/>
                            </a>';
                })
                ->addColumn('title_bn', function($row){
                    $actions = '';
                    if(auth()->user()->can('documentation-edit')){
                        $actions .= '<a class="text-info fw-bolder" href="'.route('news.edit', $row->id).'">Edit</a> | ';
                    }
                    if(auth()->user()->can('documentation-delete')){
                        $actions .= '<a class="text-danger fw-bolder" href="'.route('documentation-delete', $row->id).'">Delete</a>';
                    }
                    return '<b>Title: </b>'.$row->title_bn.'<br><div class="row-actions">'.$actions.'</div>';
                })
                ->filterColumn('title_bn', function($query, $keyword) {
                    $query->where('title_bn', 'like', "%{$keyword}%");
                })
                ->addColumn('is_published', function($row){
                    if($row->is_published == 1) return '<span class="badge bg-success">Published</span>';
                    if($row->is_published == 2) return '<span class="badge bg-danger">Unpublished</span>';
                    return '<span class="badge bg-info">Draft</span>';
                })
                ->addColumn('created_at', function($row){
                    return '<b>Date: </b>'.$row->created_at->format('d-m-Y').'<br><b>Time: </b>'.$row->created_at->format('h:i A');
                })
                ->rawColumns(['photo','title_bn','is_published','created_at'])
                ->make(true);
        }

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'News data fetched successfully.',
                'status_code' => 200,
                'allNewsCount' => Documentation::count(),
                'publishedNewsCount' => Documentation::where('is_published',1)->count(),
                'unpublishedNewsCount' => Documentation::where('is_published',2)->count(),
                'draftNewsCount' => Documentation::where('is_published',3)->count()
            ], 200);
        }

        return view('backend.documentationManage.news.banglaListPB', [
            'allNewsCount' => Documentation::count(),
            'publishedNewsCount' => Documentation::where('is_published',1)->count(),
            'unpublishedNewsCount' => Documentation::where('is_published',2)->count(),
            'draftNewsCount' => Documentation::where('is_published',3)->count()
        ]);
    }
    
    //To get bangla banglaListUP list...
    public function banglaListUP(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-list', 'web')) {
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
        $newsData = Documentation::orderBy('id', 'desc')->where('is_published', 2)->get();
        $allNewsCount = Documentation::orderBy('id', 'desc')->count();
        $publishedNewsCount = Documentation::orderBy('id', 'desc')->where('is_published', 1)->count();
        $unpublishedNewsCount = Documentation::orderBy('id', 'desc')->where('is_published', 2)->count();
        $draftNewsCount = Documentation::orderBy('id', 'desc')->where('is_published', 3)->count();

        $newsCategoryData = DocumentationCategory::orderBy('category_name_en', 'asc')->get();
        $newsTypeData = DocumentationType::getDocumentationTypeData();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'News data fetched successfully.',
                'status_code' => 200,
                'newsData' => $newsData,
                'allNewsCount' => $allNewsCount,
                'publishedNewsCount' => $publishedNewsCount,
                'unpublishedNewsCount' => $unpublishedNewsCount,
                'draftNewsCount' => $draftNewsCount,
                'newsCategoryData' => $newsCategoryData,
                'newsTypeData' => $newsTypeData,
            ], 200);
        }

        return view('backend.documentationManage.news.banglaListUP',compact('newsData','allNewsCount','publishedNewsCount','unpublishedNewsCount','draftNewsCount','newsCategoryData','newsTypeData'));
    }
    
    //To get bangla banglaListDN list...
    public function banglaListDN(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-list', 'web')) {
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
        $newsData = Documentation::orderBy('id', 'desc')->where('is_published', 3)->get();
        $allNewsCount = Documentation::orderBy('id', 'desc')->count();
        $publishedNewsCount = Documentation::orderBy('id', 'desc')->where('is_published', 1)->count();
        $unpublishedNewsCount = Documentation::orderBy('id', 'desc')->where('is_published', 2)->count();
        $draftNewsCount = Documentation::orderBy('id', 'desc')->where('is_published', 3)->count();

        $newsCategoryData = DocumentationCategory::orderBy('category_name_en', 'asc')->get();
        $newsTypeData = DocumentationType::getDocumentationTypeData();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'News data fetched successfully.',
                'status_code' => 200,
                'newsData' => $newsData,
                'allNewsCount' => $allNewsCount,
                'publishedNewsCount' => $publishedNewsCount,
                'unpublishedNewsCount' => $unpublishedNewsCount,
                'draftNewsCount' => $draftNewsCount,
                'newsCategoryData' => $newsCategoryData,
                'newsTypeData' => $newsTypeData,
            ], 200);
        }

        return view('backend.documentationManage.news.banglaListDN',compact('newsData','allNewsCount','publishedNewsCount','unpublishedNewsCount','draftNewsCount','newsCategoryData','newsTypeData'));
    }
    
    //To get english list...
    public function englishList(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-list', 'web')) {
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
        $newsData = Documentation::orderBy('id', 'desc')->paginate(10);
        $allNewsCount = Documentation::orderBy('id', 'desc')->count();
        $publishedNewsCount = Documentation::orderBy('id', 'desc')->where('is_published', 1)->count();
        $unpublishedNewsCount = Documentation::orderBy('id', 'desc')->where('is_published', 2)->count();
        $draftNewsCount = Documentation::orderBy('id', 'desc')->where('is_published', 3)->count();

        $newsCategoryData = DocumentationCategory::orderBy('category_name_en', 'asc')->get();
        $newsTypeData = DocumentationType::getDocumentationTypeData();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'News data fetched successfully.',
                'status_code' => 200,
                'newsData' => $newsData,
                'allNewsCount' => $allNewsCount,
                'publishedNewsCount' => $publishedNewsCount,
                'unpublishedNewsCount' => $unpublishedNewsCount,
                'draftNewsCount' => $draftNewsCount,
                'newsCategoryData' => $newsCategoryData,
                'newsTypeData' => $newsTypeData,
            ], 200);
        }

        return view('backend.documentationManage.news.englishList',compact('newsData','allNewsCount','publishedNewsCount','unpublishedNewsCount','draftNewsCount','newsCategoryData','newsTypeData'));
    }
    
    //To get english englishListPB list...
    public function englishListPB(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-list', 'web')) {
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
        $newsData = Documentation::orderBy('id', 'desc')->where('user_id', $userId)->where('is_published', 1)->paginate(10);
        $allNewsCount = Documentation::orderBy('id', 'desc')->where('user_id', $userId)->count();
        $newsCategoryData = DocumentationCategory::orderBy('category_name_en', 'asc')->get();
        $newsTypeData = DocumentationType::getDocumentationTypeData();

        $publishedNewsCount = Documentation::where('is_published',1)->count();
        $unpublishedNewsCount = Documentation::where('is_published',2)->count();
        $draftNewsCount = Documentation::where('is_published',3)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'News data fetched successfully.',
                'status_code' => 200,
                'newsData' => $newsData,
                'allNewsCount' => $allNewsCount,
                'publishedNewsCount' => $publishedNewsCount,
                'unpublishedNewsCount' => $unpublishedNewsCount,
                'draftNewsCount' => $draftNewsCount,
                'newsCategoryData' => $newsCategoryData,
                'newsTypeData' => $newsTypeData,
            ], 200);
        }

        return view('backend.documentationManage.news.englishListPB',compact('newsData','allNewsCount','publishedNewsCount','unpublishedNewsCount','draftNewsCount','newsCategoryData','newsTypeData'));
    }
    
    //To get english englishListUP list...
    public function englishListUP(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-list', 'web')) {
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
        $newsData = Documentation::orderBy('id', 'desc')->where('user_id', $userId)->where('is_published', 2)->paginate(10);
        $allNewsCount = Documentation::orderBy('id', 'desc')->where('user_id', $userId)->count();
        $newsCategoryData = DocumentationCategory::orderBy('category_name_en', 'asc')->get();
        $newsTypeData = DocumentationType::getDocumentationTypeData();

        $publishedNewsCount = Documentation::where('is_published',1)->count();
        $unpublishedNewsCount = Documentation::where('is_published',2)->count();
        $draftNewsCount = Documentation::where('is_published',3)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'News data fetched successfully.',
                'status_code' => 200,
                'newsData' => $newsData,
                'allNewsCount' => $allNewsCount,
                'publishedNewsCount' => $publishedNewsCount,
                'unpublishedNewsCount' => $unpublishedNewsCount,
                'draftNewsCount' => $draftNewsCount,
                'newsCategoryData' => $newsCategoryData,
                'newsTypeData' => $newsTypeData,
            ], 200);
        }

        return view('backend.documentationManage.news.englishListUP',compact('newsData','allNewsCount','publishedNewsCount','unpublishedNewsCount','draftNewsCount','newsCategoryData','newsTypeData'));
    }
    
    //To get english englishListDN list...
    public function englishListDN(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-list', 'web')) {
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
        $newsData = Documentation::orderBy('id', 'desc')->where('user_id', $userId)->where('is_published', 3)->paginate(10);
        $allNewsCount = Documentation::orderBy('id', 'desc')->where('user_id', $userId)->count();
        $newsCategoryData = DocumentationCategory::orderBy('category_name_en', 'asc')->get();
        $newsTypeData = DocumentationType::getDocumentationTypeData();

        $publishedNewsCount = Documentation::where('is_published',1)->count();
        $unpublishedNewsCount = Documentation::where('is_published',2)->count();
        $draftNewsCount = Documentation::where('is_published',3)->count();

        //To API response...
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'News data fetched successfully.',
                'status_code' => 200,
                'newsData' => $newsData,
                'allNewsCount' => $allNewsCount,
                'publishedNewsCount' => $publishedNewsCount,
                'unpublishedNewsCount' => $unpublishedNewsCount,
                'draftNewsCount' => $draftNewsCount,
                'newsCategoryData' => $newsCategoryData,
                'newsTypeData' => $newsTypeData,
            ], 200);
        }

        return view('backend.documentationManage.news.englishListDN',compact('newsData','allNewsCount','publishedNewsCount','unpublishedNewsCount','draftNewsCount','newsCategoryData','newsTypeData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-create', 'web')) {
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
        $newsCategoryData = DocumentationCategory::orderBy('category_name_en','asc')->where('user_id', $userId)->where('status',true)->get();
        $newsTagData = DocumentationTag::orderBy('tag_name_en', 'asc')->where('user_id', $userId)->where('status',true)->get();
        $userData = User::orderBy('name','asc')->where('status',true)->whereNotIn('role', ['superadmin'])->get();
        $photoGalleryData = PhotoGallery::orderBy('id','desc')->where('user_id', $userId)->get();
        $newsTypeData = DocumentationType::getDocumentationTypeData();
        $visibilityData = VisibilityType::getVisibilityTypeData();
        $layoutData = DocumentationLayout::getDocumentationLayoutData();
        $todayDate = Carbon::now()->toDateString();

        return view('backend.documentationManage.news.create', compact('newsCategoryData', 'newsTagData','userData','newsTypeData','todayDate'
            ,'visibilityData','layoutData','photoGalleryData'));
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
        if (!auth()->user()->can('documentation-create', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'title_en' => 'required|string|max:255',
            'documentation_category_id' => 'required|array',
            'post_en' => 'required',
            'photo' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $data['documentation_category_id'] = json_encode($request->documentation_category_id);
        $tagIds = [];
        if ($request->has('documentation_tag_id')) {
            foreach ($request->documentation_tag_id as $tag) {
                $tag = trim($tag);
                if ($tag === '') continue;

                if (is_numeric($tag)) {
                    $tagIds[] = (string) $tag;
                } else {
                    $existing = DocumentationTag::whereRaw('LOWER(tag_name_en) = ?', [Str::lower($tag)])->first();
                    if ($existing) {
                        $tagIds[] = (string) $existing->id;
                    } else {
                        $newTag = DocumentationTag::create(['user_id' => $userId,'tag_name_en' => $tag]);
                        $tagIds[] = (string) $newTag->id;
                    }
                }
            }
        }
        $data['documentation_tag_id'] = json_encode(array_values(array_unique($tagIds)));

        

        //For slug...
        $slugEn = Str::slug($request->title_en);
        $baseSlugEn = $slugEn;
        $count = 2;
        while (Documentation::where('slug_en', $slugEn)->exists()) {
            $slugEn = $baseSlugEn . '-' . $count;
            $count++;
        }
        $data['slug_en'] = $slugEn;
        $data['permalink_slug'] = url('/documentation/' . $slugEn);
        

        if (isset($request->publish_date) && $request->publish_date != null) {
            $data['publish_date'] = Carbon::parse($request->publish_date)->format('Y-m-d');
        }

        //To check logo image...
        foreach (['photo'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/documentationImg');
            }
        }

        if($request->is_published == 'Published'){
            $data['is_published'] = 1;
        }elseif($request->is_published == 'Un Published'){
            $data['is_published'] = 2;
        }else{
            $data['is_published'] = 3;
        }

        if (isset($request->publish_date) && $request->publish_date != null) {
            $data['publish_date'] = Carbon::parse($request->publish_date)->format('Y-m-d');
        }
        
        if(Documentation::create($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Documentation created successfully.',
                    'status_code' => 200,
                    'documentationData' => $data
                ], 200);
            }

            Toastr::success('Documentation created successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('documentation-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleDocumentationDataData = Documentation::where('id', $id)->first();
        if(isset($singleDocumentationDataData) && $singleDocumentationDataData != null){
            return response()->json([
                'message'   =>  'Documentation loaded successfully.',
                'status_code'   => 200,
                'singleDocumentationDataData'   => $singleDocumentationDataData
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Sorry, Documentation not found.!',
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
        if (!auth()->user()->can('documentation-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }
        $userId = CurrentUser::getSuperadminId();

        //To get photo gallery & single News data...
        $singleDocumentationData = Documentation::find($id);
        $newsCategoryData = DocumentationCategory::orderBy('category_name_en','asc')->where('user_id', $userId)->where('status',true)->get();
        $newsTagData = DocumentationTag::orderBy('tag_name_en', 'asc')->where('user_id', $userId)->where('status',true)->get();
        $userData = User::orderBy('name','asc')->where('status',true)->whereNotIn('role', ['superadmin'])->get();
        $newsTypeData = DocumentationType::getDocumentationTypeData();
        $visibilityData = VisibilityType::getVisibilityTypeData();
        $layoutData = DocumentationLayout::getDocumentationLayoutData();
        if($singleDocumentationData->publish_date != null){
            $todayDate = $singleDocumentationData->publish_date;
        }else{
            $todayDate = Carbon::now()->toDateString();
        }
        $tagIds = json_decode($singleDocumentationData->documentation_tag_id);
        $categoryIds = json_decode($singleDocumentationData->documentation_category_id);

        // dd($tagIds);
        return view('backend.documentationManage.news.edit', compact('singleDocumentationData','newsCategoryData', 'newsTagData','userData','newsTypeData','todayDate'
                ,'visibilityData','layoutData','tagIds','categoryIds'));
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
        if (!auth()->user()->can('documentation-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $request->validate([
            'title_en' => 'nullable|string|max:255',
            'documentation_category_id' => 'required|array',
            'post_en' => 'nullable',
            'photo' => 'nullable|mimes:jpg,jpeg,png,gif,svg,webp',
        ]);

        //To fetch user id...
        $userId = CurrentUser::getSuperadminId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $data['documentation_category_id'] = json_encode($request->documentation_category_id);
        $tagIds = [];
        if ($request->has('documentation_tag_id')) {
            foreach ($request->documentation_tag_id as $tag) {
                $tag = trim($tag);
                if ($tag === '') continue;

                if (is_numeric($tag)) {
                    $tagIds[] = (string) $tag;
                } else {
                    $existing = DocumentationTag::whereRaw('LOWER(tag_name_en) = ?', [Str::lower($tag)])->first();
                    if ($existing) {
                        $tagIds[] = (string) $existing->id;
                    } else {
                        $newTag = DocumentationTag::create(['user_id' => $userId,'tag_name_en' => $tag]);
                        $tagIds[] = (string) $newTag->id;
                    }
                }
            }
        }
        $data['documentation_tag_id'] = json_encode(array_values(array_unique($tagIds)));

        //For slug...
        $slugEn = Str::slug($request->title_en);
        $baseSlugEn = $slugEn;
        $count = 2;
        while (Documentation::where('slug_en', $slugEn)->exists()) {
            $slugEn = $baseSlugEn . '-' . $count;
            $count++;
        }
        $data['slug_en'] = $slugEn;
        
        $singleDocumentationData = Documentation::where('id', $id)->first();
        if(isset($request->permalink) && $request->permalink != $singleDocumentationData->permalink_slug){
            $data['permalink_slug'] = $request->permalink;
        }else{
            $data['permalink_slug'] = url('/documentation/' . $slugEn);
        }

        //To check logo image...
        foreach (['photo'] as $field) {
            if ($request->hasFile($field)) {
                if (!empty($singleDocumentationData->$field)) {
                    $filePath = 'uploads/documentationImg/' . $singleDocumentationData->$field;
                    Storage::disk('public')->delete($filePath);
                }
                $data[$field] = $this->uploadFile($request->file($field), 'uploads/documentationImg');
            }
        }

        if (isset($request->publish_date) && $request->publish_date != null) {
            $data['publish_date'] = Carbon::parse($request->publish_date)->format('Y-m-d');
        }
 
        if($singleDocumentationData->update($data)){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Documentation updated successfully.',
                    'status_code' => 200,
                    'singleDocumentationData' => $singleDocumentationData
                ], 200);
            }
         
            Toastr::success('Documentation updated successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('documentation-delete', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        $singleDocumentationData = Documentation::where('id', $id)->first();
        if (!$singleDocumentationData) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sorry, Data not found.',
                    'status_code' => 500
                ], 500);
            }

            Toastr::error('Sorry, Data not found.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        // Delete files from storage
        foreach (['photo'] as $field) {
            if (!empty($singleDocumentationData->$field)) {
                $filePath = 'uploads/documentationImg/' . $singleDocumentationData->$field;
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }

        if($singleDocumentationData->delete()){
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Documentation deleted successfully.',
                    'status_code' => 200
                ], 200);
            }

            Toastr::success('Documentation deleted successfully.', 'Success', ["progressbar" => true]);
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
        if (!auth()->user()->can('documentation-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Documentation::where('id', $id)->update(['status' => true]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Documentation activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    //To inactive status...
    public function inactive(Request $request, $id)
    {
        //To check user permission...
        if (!auth()->user()->can('documentation-edit', 'web')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Access Denied.',
                    'status_code' => 403
                ], 403);
            }

            return redirect()->route('error.accessDenied');
        }

        Documentation::where('id', $id)->update(['status' => false]);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Documentation activated successfully.',
                'status_code' => 200
            ], 200);
        }

        Toastr::success('Documentation activated successfully.', 'Success', ["progressbar" => true]);
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');

        $tags = DocumentationTag::when($q, function($qBuilder) use ($q){
            $qBuilder->where('tag_name_en', 'LIKE', "%{$q}%");
        })
        ->select('id','tag_name_en')
        ->limit(20)
        ->get();

        return response()->json($tags);
    }
}
