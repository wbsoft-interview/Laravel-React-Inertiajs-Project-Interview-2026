@extends('backend.master')
@section('title') Documentation Tag | Sangbad Protikhon @endsection
@section('documentation-tag') active @endsection
@section('documentation-tag.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Tag List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('documentation-tag.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allNewsTagCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('documentation-tag-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addNewsTag">
                        <i class="fa fa-plus"></i>
                        <span class="">New Tag</span></a>
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

                    @foreach($newsTagData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td class="text-center">
                            <div class="row_title">
                                {{ $item->tag_name_en }}
                            </div>
                            <div class="row-actions mt-2">
                    
                                @if (Auth::user()->can('documentation-tag-edit'))
                                <span>
                                    <button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateNewsTag({{ $item->id }})"> Edit </button>
                                </span>
                    
                                @if($item->status)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{ route('documentation-tag-inactive', $item->id) }}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{ route('documentation-tag-active', $item->id) }}">Active</a></span>
                                @endif
                                @endif
                    
                                @if (Auth::user()->can('documentation-tag-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{ route('documentation-tag-delete', $item->id) }}">Delete</a></span>
                                @endif
                            </div>
                        </td>
                    
                        <td>
                            @if($item->status)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>

                    {{-- //Update ServiceTag.. --}}
                    <div class="modal fade" id="updateNewsTag{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Tag</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('documentation-tag.update', $item->id) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                
                                            <!-- English Tag Name -->
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label for="tag_name_en">Tag Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="tag_name_en" required class="form-control form-control-solid"
                                                        value="{{ $item->tag_name_en }}" placeholder="Name">
                                                    @error('tag_name_en')
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

        @if(isset($allNewsTagCount) && $allNewsTagCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $newsTagData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $newsTagData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $newsTagData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $newsTagData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>


{{-- //Add new documentation-Tag.. --}}
<div class="modal fade" id="addNewsTag" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Tag</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('documentation-tag.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
            
                        <!-- English Tag Name -->
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="tag_name_en">Tag Name <span class="text-danger">*</span></label>
                                <input type="text" name="tag_name_en" required class="form-control form-control-solid"
                                    value="{{ old('tag_name_en') }}" placeholder="Name">
                                @error('tag_name_en')
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
    //To show update modal...
    function updateNewsTag(id) {
        $("#updateNewsTag"+id).modal('show');
    }
</script>
@endsection
