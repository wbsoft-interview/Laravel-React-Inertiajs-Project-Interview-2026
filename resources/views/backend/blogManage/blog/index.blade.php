@extends('backend.master')
@section('title') Blog | Master Template @endsection
@section('blog') active @endsection
@section('blog.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Blog List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('blog.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allBlogCount}})</a>
                    </p>
                </div>
                <div class="d-none d-sm-block">
                    @if (Auth::user()->can('blog-create')) 
                        <a href="{{route('blog.create')}}"
                            class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                            <i class="fa fa-plus"></i>
                            <span class="">New Blog</span></a>
                    @endif
                </div>
            </div>
        </div>
    </div>



    <div class="row px-3">
        <div class="table-container table-responsive">
            <table id="" class="table table-bordered">
                <thead class="text-uppercase">
                    <tr class="me-3">
                        <th class="text-center" scope="col"><span>Blog Image</span></th>
                        <th class="text-center" scope="col"><span>Blog Description</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                        <th class="text-center" scope="col"><span>Created</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($blogData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <a href="{{ $item->photo ? Storage::url('uploads/blogImg/' . $item->photo) : asset('backend/template-assets/images/img_preview.png') }}"
                                data-rel="lightcase">
                                <img src="{{ $item->photo ? Storage::url('uploads/blogImg/' . $item->photo) : asset('backend/template-assets/images/img_preview.png') }}"
                                    height="100" width="100" alt="">
                            </a>
                        </td>
                        <td class="text-start">
                            <div class="row_title">
                                <b>Category: </b> <span>{{$item->blogCategoryData->category_name}} </span><br>
                                <b>Title: </b> <span>{{$item->title}} </span><br>
                                <b>Description: </b><span class="pl-1 ml-2">{!! Str::limit(preg_replace('/<img[^>]*>/', '', $item->post), 100) !!}</span>
                            </div>
                            <div class="row-actions">
                                @if (Auth::user()->can('blog-edit')) 
                                    <span><button class="edit_class_modal border-0 bg-transparent fw-bolder" 
                                        value="{{ $item->id }}"> <a class="text-primary" href="{{route('blog.edit', $item->id)}}">Edit</a> </button> | </span>
                                @endif

                                @if($item->status == true)
                                <span><a class="text-warning fw-bolder"
                                        href="{{route('blog-inactive', $item->id)}}">Inactive</a></span>
                                @else
                                <span><a class="text-success fw-bolder"
                                        href="{{route('blog-active', $item->id)}}">Active</a></span>
                                @endif

                                @if (Auth::user()->can('blog-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete" href="{{route('blog-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($item->status == true)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                        <td class="text-start">
                            <b>Date: </b><span class="text-normal">{{Carbon\Carbon::parse($item->created_at->toDateString())->format('d-m-Y')}}</span><br>
                            <b>Time: </b><span class="text-normal">{{Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('h:i A')}}</span>
                        </td>

                    </tr>
                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allBlogCount) && $allBlogCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $blogData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $blogData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $blogData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>
        
            <div class="float-right custom-table-pagination">
                {!! $blogData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif

    </div>


</div>
@endsection

@section('scripts')
@endsection