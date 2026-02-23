@extends('backend.master')
@section('title') Documentation Category | Sangbad Protikhon @endsection
@section('documentation-category') active @endsection
@section('documentation-category.index') active @endsection
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
                    <p class="mb-0"><a href="{{route('documentation-category.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allNewsCategoryCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('documentation-category-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addNewsCategory">
                        <i class="fa fa-plus"></i>
                        <span class="">New Category</span></a>
                    @endif
                </div>
            </div>
        </div>
    </div>



    <div class="row px-3">
        <div class="table-container table-responsive">
            <table id="" class="table table-bordered datatable-common-padding">
                <thead class="text-uppercase">
                    <tr class="me-3">
                        <th class="text-center d-none" scope="col"><span>Id</span></th>
                        <th class="text-center" scope="col"><span>Category</span></th>
                        <th class="text-center" scope="col"><span>Sub Category</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($newsCategoryData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr>
                        <td class="d-none">{{ $item->id }}</td>
                        <td class="text-start">
                            <div class="row_title">
                                @if(isset($item->parent_category_id) && $item->parent_category_id != null)
                                   {{ $item->parentCategory->category_name_en ?? '' }}
                                @else
                                    {{ $item->category_name_en ?? '' }}
                                @endif
                            </div>
                    
                            <div class="row-actions mt-2">
                                @if (Auth::user()->can('documentation-category-edit'))
                                <span>
                                    <button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateNewsCategory({{ $item->id }})">Edit</button>
                                </span>
                    
                                @if($item->status)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{ route('documentation-category-inactive', $item->id) }}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{ route('documentation-category-active', $item->id) }}">Active</a></span>
                                @endif
                                @endif
                    
                                @if (Auth::user()->can('documentation-category-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{ route('documentation-category-delete', $item->id) }}">Delete</a></span>
                                @endif
                            </div>
                        </td>
                    
                        @if(isset($item->parent_category_id) && $item->parent_category_id != null)
                        <td>
                                {{ $item->category_name_en ?? '' }}
                        </td>
                        @else
                        <td>
                            <span>--</span>
                        </td>
                        @endif
                    
                        <td>
                            @if($item->status)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>

                    {{-- //Update ServiceCategory.. --}}
                    <div class="modal fade" id="updateNewsCategory{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Category</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('documentation-category.update', $item->id) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                
                                            {{-- News Type --}}
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group custom-select2-form">
                                                    <label for="documentation_type{{$item->id}}">News Type <span class="text-danger">*</span></label>
                                                    <select name="documentation_type" id="documentation_type{{$item->id}}" class="form-select select2" 
                                                        onchange="checkTypeDataFU({{$item->id}})" required>
                                                        <option value="" disabled>Select Type</option>
                                                        @foreach ($newsTypeData as $singleNTData)
                                                        <option value="{{ $singleNTData }}" {{ $item->documentation_type == $singleNTData ? 'selected' : '' }}>
                                                            {{ $singleNTData }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('documentation_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                
                                            {{-- Category Name English --}}
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="category_name_en">Category Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="category_name_en" required class="form-control form-control-solid"
                                                        value="{{ $item->category_name_en }}" placeholder="Category Name in English">
                                                    @error('category_name_en')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Parent Category --}}
                                            <div class="col-md-12 mb-3" id="parentCategoryWrapper{{$item->id}}"
                                                style="{{ $item->parent_category_id ? '' : 'display: none;' }}">
                                                <div class="form-group custom-select2-form">
                                                    <label for="parent_category_id{{$item->id}}">Parent Category <span class="text-danger">*</span></label>
                                                    <select name="parent_category_id" id="parent_category_id{{$item->id}}" class="form-select select2">
                                                        <option value="" disabled>Select Parent Category</option>
                                                        @foreach ($getNewsCategoryData as $singleNTData)
                                                        @if(isset($singleNTData))
                                                        <option value="{{ $singleNTData->id }}"
                                                            {{ $item->parent_category_id == $singleNTData->id ? 'selected' : '' }}>
                                                            {{ $singleNTData->category_name_en }}
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                    @error('parent_category_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                
                                            {{-- Details English --}}
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label for="details_en">Details (English)</label>
                                                    <textarea rows="3" name="details_en" class="form-control form-control-solid"
                                                        placeholder="Details in English">{{ $item->details_en }}</textarea>
                                                    @error('details_en')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
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

        @if(isset($allNewsCategoryCount) && $allNewsCategoryCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $newsCategoryData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $newsCategoryData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $newsCategoryData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $newsCategoryData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>


{{-- //Add new documentation-category.. --}}
<div class="modal fade" id="addNewsCategory" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('documentation-category.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
            
                        {{-- News Type --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="documentation_type">Documentation Type <span class="text-danger">*</span></label>
                                <select name="documentation_type" id="documentation_type" class="form-select select2"
                                    onchange="checkTypeData()" required>
                                    <option value="" disabled selected>Select Type</option>
                                    @foreach ($newsTypeData as $singleNTData)
                                    <option value="{{ $singleNTData }}">{{ $singleNTData }}</option>
                                    @endforeach
                                </select>
                                @error('documentation_type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
            
                        {{-- Category Name English --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="category_name_en">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="category_name_en" required class="form-control form-control-solid"
                                    value="{{ old('category_name_en') }}" placeholder="Category Name in English">
                                @error('category_name_en')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Parent Category --}}
                        <div class="col-md-12 mb-3" id="parentCategoryWrapper" style="display: none;">
                            <div class="form-group custom-select2-form">
                                <label for="parent_category_id">Parent Category <span class="text-danger">*</span></label>
                                <select name="parent_category_id" id="parent_category_id" class="form-select select2">
                                    <option value="" disabled selected>Select Parent Category</option>
                                    @foreach ($getNewsCategoryData as $singleNTData)
                                    @if(isset($singleNTData))
                                    <option value="{{ $singleNTData->id }}">
                                        {{ $singleNTData->category_name_en }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('parent_category_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
            
                        {{-- Details English --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="details_en">Details</label>
                                <textarea rows="3" name="details_en" class="form-control form-control-solid"
                                    placeholder="Details in English">{{ old('details_en') }}</textarea>
                                @error('details_en')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
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
    $("#newsCategoryTable").DataTable({
    order: [[0, "desc"]], // hidden id column দিয়ে sort করবে
    columnDefs: [
    { targets: 0, visible: false, searchable: false } // প্রথম column (id) hide থাকবে
    ]
    });

    $("#documentation_type").select2({
    dropdownParent: $('#addNewsCategory')
    });
    $("#parent_category_id").select2({
    dropdownParent: $('#addNewsCategory')
    });

    //To show update modal...
    function updateNewsCategory(id) {
        $("#updateNewsCategory"+id).modal('show');

        $("#documentation_type"+id).select2({
        dropdownParent: $('#updateNewsCategory'+id)
        });
        $("#parent_category_id"+id).select2({
        dropdownParent: $('#updateNewsCategory'+id)
        });
    }
</script>

<script>
    function checkTypeData() {
        const newsType = document.getElementById("documentation_type").value;
        const parentWrapper = document.getElementById("parentCategoryWrapper");
        const parentSelect = document.getElementById("parent_category_id");

        if (newsType === "Sub Category") {
            parentWrapper.style.display = "block";
            parentSelect.setAttribute("required", "required");
        } else {
            parentWrapper.style.display = "none";
            parentSelect.removeAttribute("required");
            parentSelect.value = "";
        }
    }
    
    function checkTypeDataFU(id) {
        const newsType = document.getElementById("documentation_type"+id).value;
        const parentWrapper = document.getElementById("parentCategoryWrapper"+id);
        const parentSelect = document.getElementById("parent_category_id"+id);

        if (newsType === "Sub Category") {
            parentWrapper.style.display = "block";
            parentSelect.setAttribute("required", "required");
        } else {
            parentWrapper.style.display = "none";
            parentSelect.removeAttribute("required");
            parentSelect.value = "";
        }
    }
</script>
@endsection
