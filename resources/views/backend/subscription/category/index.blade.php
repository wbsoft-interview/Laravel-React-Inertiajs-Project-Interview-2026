@extends('backend.master')
@section('title') Package Category | Master Template @endsection
@section('package-category') active @endsection
@section('package-category.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Package Category</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{ route('package-category.index') }}"
                            class="text-primary py-2 px-3 active">All({{$allPackageCategoryCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('package-category-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addAccount">
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
                        <th class="text-center" scope="col"><span>Serial</span></th>
                        <th class="text-center" scope="col"><span>Category Name</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                        <th class="text-center" scope="col"><span>Date/Time</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($packageCategoryData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <b>#{{$key+1}}</b>
                        </td>
                        <td class="text-center">
                            <div class="row_title">

                                 {{ $item->category_name }}
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('account-edit'))

                                <span>  <button class="text-info border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateAccount({{$item->id}})"> Edit </button></span>


                                @if($item->status == true)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{route('package-category-inactive', $item->id)}}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{route('package-category-active', $item->id)}}">Active</a>
                                </span>
                                @endif

                                @endif
                                @if (Auth::user()->can('account-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('package-category-delete', $item->id)}}">Delete</a>
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

                        <td>
                            <span class="text-normal">{{Carbon\Carbon::parse($item->created_at->toDateString())->format('d-m-Y')}}</span>
                            <br> <span
                                class="text-normal">{{Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('h:i A')}}</span>
                        </td>

                    </tr>

                    {{-- //Update ServiceCategory.. --}}
                    <div class="modal fade" id="updateAccount{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Category</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('package-category.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label for="account_name">Package Category Name<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="category_name" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->category_name}}" placeholder="Package category Name" required>
                                                </div>

                                                @error('category_name')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Save</button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                        </div>
                    </div>

                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allPackageCategoryCount) && $allPackageCategoryCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $packageCategoryData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $packageCategoryData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $packageCategoryData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $packageCategoryData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>


{{-- //Add new account.. --}}
<div class="modal fade" id="addAccount" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('package-category.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="account_name">Package Category Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="category_name" required class="form-control form-control-solid"
                                    value="{{old('category_name')}}" placeholder="Category Name" required>
                            </div>

                            @error('account_name')
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
    function updateAccount(id) {
        $("#updateAccount"+id).modal('show');
    }
</script>
@endsection
