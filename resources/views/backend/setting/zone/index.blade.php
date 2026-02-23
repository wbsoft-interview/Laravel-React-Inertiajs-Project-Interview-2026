@extends('backend.master')
@section('title') Zone | Beauty Parlour @endsection
@section('zone') active @endsection
@section('zone.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Zone List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('zone.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allZoneCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('zone-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addZone">
                        <i class="fa fa-plus my-auto"></i>
                        <span class="">New Zone</span></a>
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
                        <th class="text-center" scope="col"><span>Details</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($zoneData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">

                        <td class="text-start">
                            <div class="row_title">
                                <b>Upozila: </b> {{ $item->upozilaData->name_en }} <br>
                                <b>Name: </b> {{ $item->name_en }} <br>
                                <b>Bangla: </b> {{ $item->name_bn }} <br>
                                <b>URL: </b> {{ $item->url }} <br>
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('zone-edit'))
                                <span><button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateZone({{$item->id}})"> Edit </button></span>
                                @endif

                                @if (Auth::user()->can('zone-delete'))
                                <span> | <a class="text-danger fw-bolder"
                                        href="{{route('zone-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>

                    </tr>

                    {{-- //Update zone.. --}}
                    <div class="modal fade" id="updateZone{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Branch</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('zone.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group custom-select2-form">
                                                    <label for="upozila_id">Upozila<span class=" text-danger">*</span></label>
                                            
                                                    <select name="upozila_id" id="upozila_id{{$item->id}}" class="form-control select2" required>
                                                        <option value="" selected disabled>Select A Upozila</option>
                                            
                                                        @foreach($upozilaData as $upozilaS)
                                                        <option value="{{$upozilaS->id}}"
                                                            {{$item->upozila_id == $upozilaS->id ? 'selected' : ''}}
                                                            >{{$upozilaS->name_en}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            
                                                @error('upozila_id')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="name_en">Zone Name English<span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="name_en" required class="form-control form-control-solid"
                                                        value="{{$item->name_en}}" placeholder="Name">
                                                </div>

                                                @error('name_en')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="name_bn">Zone Name Bangla<span class="text-danger"></span>
                                                    </label>
                                                    <input type="text" name="name_bn" class="form-control form-control-solid"
                                                        value="{{$item->name_bn}}" placeholder="Name">
                                                </div>

                                                @error('name_bn')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="url">URL<span class="text-danger"></span>
                                                    </label>
                                                    <input type="text" name="url" class="form-control form-control-solid"
                                                        value="{{$item->url}}" placeholder="URL">
                                                </div>

                                                @error('url')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success" onclick="return checkValidateForUpdate({{$item->id}})">Update</button>
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

        @if(isset($allZoneCount) && $allZoneCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $zoneData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $zoneData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $zoneData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $zoneData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>


{{-- //Add new zone.. --}}
<div class="modal fade" id="addZone" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Zone</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('zone.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="upozila_id">Upozila<span class=" text-danger">*</span></label>
                        
                                <select name="upozila_id" id="upozila_id" class="form-control select2" required>
                                    <option value="" selected disabled>Select A Upozila</option>
                        
                                    @foreach($upozilaData as $upozila)
                                    <option value="{{$upozila->id}}">{{$upozila->name_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            @error('upozila_id')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="name_en">Zone Name English<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name_en" required class="form-control form-control-solid" value=""
                                    placeholder="Name">
                            </div>

                            @error('name_en')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="name_bn">Zone Name Bangla<span class="text-danger"></span>
                                </label>
                                <input type="text" name="name_bn" class="form-control form-control-solid" value=""
                                    placeholder="Name">
                            </div>

                            @error('name_bn')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="url">URL<span class="text-danger"></span>
                                </label>
                                <input type="text" name="url" class="form-control form-control-solid" value=""
                                    placeholder="URL">
                            </div>

                            @error('url')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" onclick="return checkValidate()">Save</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function(){
        $("#upozila_id").select2({
        dropdownParent: $('#addZone')
        });
    });

    function checkValidate() {
        var mobileNumber1 = $("#phone1").val();
        var mobileNumber2 = $("#phone2").val();

        if (mobileNumber1 != '' && mobileNumber1.length != 11) {
            event.preventDefault();
            toastr.error("Mobile number 1 must be 11 digit.");
        }

        if (mobileNumber2 != '' && mobileNumber2.length != 11) {
            event.preventDefault();
            toastr.error("Mobile number 2 must be 11 digit.");
        }

        return true;
    }

    //For update...
    function checkValidateForUpdate(id) {
        var mobileNumber1 = $("#phone1"+id).val();
        var mobileNumber2 = $("#phone2"+id).val();

        if (mobileNumber1 != '' && mobileNumber1.length != 11) {
            event.preventDefault();
            toastr.error("Mobile number 1 must be 11 digit.");
        }

        if (mobileNumber2 != '' && mobileNumber2.length != 11) {
            event.preventDefault();
            toastr.error("Mobile number 2 must be 11 digit.");
        }

        return true;
    }

    //To show update modal...
    function updateZone(id) {
        $("#updateZone"+id).modal('show');
        $("#upozila_id"+id).select2({
        dropdownParent: $('#updateZone'+id)
        });
    }
</script>
@endsection
