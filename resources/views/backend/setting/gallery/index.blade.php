@extends('backend.master')
@section('title') Gallery || Master Template @endsection
@section('gallery') active @endsection
@section('gallery.index') active @endsection
@section('styles')
<style>
    .select_imgWith_preview {
        height: 140px !important;
    }

    .custom-soft-setting .select_imgWith_preview input[type="file"] {
        max-width: 250px !important;
    }

    .custom-soft-setting .select_imgWith_preview span#image_remove_buttton{
        right: auto !important;
    }
</style>
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Gallery List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('gallery.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allGalleryCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('photo-gallery-access'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0"
                        data-bs-toggle="modal" data-bs-target="#addGallery">
                        <i class="fa fa-plus"></i>
                        <span class="">New Photo</span></a>
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
                        <th class="text-center" scope="col"><span>Title</span></th>
                        <th class="text-center" scope="col"><span>Photo</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                        <th class="text-center" scope="col"><span>Date/Time</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($galleryData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <b>#{{$key+1}}</b>
                        </td>
                        <td class="text-start">
                            <div class="row_title">
                               {{ $item->title }}
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('photo-gallery-access'))

                                <span><button class="text-info border-0 bg-transparent fw-bolder"
                                        value="{{ $item->id }}" onclick="updateGallery({{$item->id}})"> Edit
                                    </button></span>


                                @if($item->status == true)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{route('gallery-inactive', $item->id)}}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{route('gallery-active', $item->id)}}">Active</a>
                                </span>
                                @endif

                                @endif
                                @if (Auth::user()->can('photo-gallery-access'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('gallery-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if(isset($item->photo) && $item->photo != null)
                            <img src="{{ $item->photo ? asset('storage/uploads/galleryPhoto/'.$item->photo) : asset('backend/template-assets/images/img_preview.png') }}" height="70" width="100" />
                            @else
                            <img src="{{ asset('backend/template-assets/') }}/images/img_preview.png" height="50" width="80" />
                            @endif
                        </td>

                        <td>
                            @if($item->status == true)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <span class="text-normal">{{Carbon\Carbon::parse($item->created_at->toDateString())->format('d-m-Y')}}</span>
                            <br> <span
                                class="text-normal">{{Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('h:i A')}}</span>
                        </td>

                    </tr>

                    {{-- //Update ServiceCategory.. --}}
                    <div class="modal fade" id="updateGallery{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Photo</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('gallery.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label for="title">Title<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="title" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->title}}" placeholder="Name">
                                                </div>

                                                @error('title')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mb-3 custom-soft-setting">
                                                <div>
                                                    <div id="card_body_5" class=" mb-3 position-relative">
                                                        <div class="select_imgWith_preview">
                                                            <label for="name">Select Image<span class=" text-danger"></span></label> <br>
                                                            @if(isset($item->photo))
                                                            <img id="uploadPreview{{$item->id}}" src="{{ $item->photo ? asset('storage/uploads/galleryPhoto/'.$item->photo) : asset('backend/template-assets/images/img_preview.png') }}">
                                                            @else
                                                            <img id="uploadPreview{{$item->id}}"
                                                                src="{{ asset('backend/template-assets/') }}/images/img_preview.png">
                                                            @endif
                                                            <input id="uploadImage{{$item->id}}" type="file" name="photo"
                                                                onchange="PreviewImage('uploadImage{{$item->id}}','uploadPreview{{$item->id}}');" />
                                                            <span onclick="cancelPreview('uploadImage{{$item->id}}','uploadPreview{{$item->id}}')"
                                                                class="material-symbols-outlined" id="image_remove_buttton">
                                                                close </span>
                                                        </div>
                                                    </div>
                                            
                                                    @error('photo')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </div>
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

        @if(isset($allGalleryCount) && $allGalleryCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $galleryData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $galleryData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $galleryData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $galleryData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>


{{-- //Add new gallery.. --}}
<div class="modal fade" id="addGallery" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true"
    data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Photo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('gallery.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="title">Title<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" required class="form-control form-control-solid"
                                    value="{{old('title')}}" placeholder="Name">
                            </div>

                            @error('title')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3 custom-soft-setting">
                            <div>
                                <div id="card_body_5" class=" mb-3 position-relative">
                                    <div class="select_imgWith_preview">
                                        <label for="name">Select Image<span class=" text-danger"></span></label> <br>
                                        <img id="uploadPreview" src="{{ asset('backend/template-assets/') }}/images/img_preview.png">
                                        <input id="uploadImage" type="file" name="photo"
                                            onchange="PreviewImage('uploadImage','uploadPreview');" />
                                        <span onclick="cancelPreview('uploadImage','uploadPreview')" class="material-symbols-outlined"
                                            id="image_remove_buttton">
                                            close </span>
                                    </div>
                                </div>
                        
                                @error('photo')
                                <span class=text-danger>{{$message}}</span>
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
    function updateGallery(id) {
        $("#updateGallery"+id).modal('show');
    }
</script>
@endsection