@extends('backend.master')
@section('title') Blog Category | Master Template @endsection
@section('blog-category') active @endsection
@section('blog-category.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Category List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('blog-category.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allBlogCategoryCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('blog-category-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addBlogCategory">
                        <i class="fa fa-plus"></i>
                        <span class="">New Category</span></a>
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
                        <th class="text-center" scope="col"><span>Name</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($blogCategoryData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">

                        <td>
                            <div class="row_title">
                                {{ $item->category_name }}
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('blog-category-edit'))
                                <span><button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateBlogCategory({{$item->id}})"> Edit </button></span>
                                

                                @if($item->status == true)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{route('blog-category-inactive', $item->id)}}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{route('blog-category-active', $item->id)}}">Active</a>
                                </span>
                                @endif

                                @endif
                                @if (Auth::user()->can('blog-category-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('blog-category-delete', $item->id)}}">Delete</a>
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

                    </tr>

                    {{-- //Update ServiceCategory.. --}}
                    <div class="modal fade" id="updateBlogCategory{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Blog Category</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('blog-category.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label for="category_name">Category Name<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="category_name" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->category_name}}" placeholder="Name">
                                                </div>

                                                @error('category_name')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allBlogCategoryCount) && $allBlogCategoryCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $blogCategoryData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $blogCategoryData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $blogCategoryData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $blogCategoryData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>


{{-- //Add new blog-category.. --}}
<div class="modal fade" id="addBlogCategory" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Blog Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('blog-category.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="category_name">Category Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="category_name" required class="form-control form-control-solid"
                                    value="" placeholder="Name">
                            </div>

                            @error('category_name')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    //To show update modal...
    function updateBlogCategory(id) {
        $("#updateBlogCategory"+id).modal('show');
    }
</script>
@endsection