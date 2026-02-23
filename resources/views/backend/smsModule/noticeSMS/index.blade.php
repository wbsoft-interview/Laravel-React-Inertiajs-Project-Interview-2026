@extends('backend.master')
@section('content')
@section('title') Notice SMS | ITDER - It Development Education & Research @endsection
@section('noticeSMS') active @endsection
@section('noticeSMS.index') active @endsection
@section('styles')
@endsection

@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span>Notice SMS</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{ route('notice-sms.index') }}"
                            class="text-primary py-2 px-3 active">All({{$noticeSMSCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('notice-sms-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addNoticeSMS">
                        <i class="fa fa-plus"></i>
                        <span class="">New Notice SMS</span></a>
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
                        <th class="text-center" scope="col"><span>User Details</span></th>
                        <th class="text-center" scope="col"><span>Details</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($noticeSMSMData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <b>#{{$key+1}}</b>
                        </td>
                        <td class="text-start">
                            <b>Name: </b> {{Str::title($item->smsToData->name)}} <br>
                            <b>Phone: </b> {{$item->smsToData->mobile}} <br>
                            <b>Email: </b> {{Str::title($item->smsToData->email)}} <br>
                        </td>
                        <td class="text-start">
                            <div class="row_title">
                                <b>Title: </b> {{Str::title($item->title)}} <br>
                                <b>Message: </b> {{$item->details}} <br>
                            </div>
                            <div class="row-actions mt-2">

                                {{-- @if (Auth::user()->can('notice-sms-edit'))

                                <span><a class="text-primary fw-bolder" href="{{route('notice-sms-profile', $item->id)}}">Details</a></span>
                                <span> | <button class="text-info border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateNoticeSMS({{$item->id}})"> Edit </button></span>

                                @if($item->status == true)
                                <span> | <a class="text-warning fw-bolder" href="{{route('noticeSMS-inactive', $item->id)}}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder" href="{{route('noticeSMS-active', $item->id)}}">Active</a></span>
                                @endif

                                @endif
                                @if (Auth::user()->can('notice-sms-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('noticeSMS-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif --}}
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
                    @endif
                    @endforeach

                </tbody>
            </table>
        </div>

        @if(isset($noticeSMSCount) && $noticeSMSCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $noticeSMSMData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $noticeSMSMData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $noticeSMSMData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $noticeSMSMData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- //Add new Notice SMS.. --}}
<div class="modal fade" id="addNoticeSMS" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Notice SMS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('notice-sms.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">

                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="role_id">Role<span class="text-danger">*</span></label>
                                <select name="role_id" id="role_id" class="form-control form-control-solid form-select select2" required>
                                    <option value="" disabled selected>Select Role</option>
                                    @foreach($roleData as $role)
                                        <option value="{{ $role->id }}">{{ Str::title($role->display_name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @error('role_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="sms_template_id">SMS Template <span class="text-danger">*</span></label>
                                <select name="sms_template_id" id="sms_template_id" class="form-control form-control-solid" required>
                                    <option value="" selected disabled>Select Template</option>
                                    @foreach($smsTemplateMData as $singleSTData)
                                    <option value="{{ $singleSTData->id }}">{{ $singleSTData->sms_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            @error('sms_template_id')
                            <span class="text-danger">{{ $message }}</span>
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

    $("#role_id").select2({
        dropdownParent: $('#addNoticeSMS')
    });
    $("#sms_template_id").select2({
        dropdownParent: $('#addNoticeSMS')
    });

    $(document).ready(function(){
        $("#title").on('input', function() {
            if ($(this).val().length > 100) {
                toastr.error("Title must not exceed 100 characters.");
                $(this).val($(this).val().slice(0, 100));
            }
        });
    });

    function updateNoticeSMS(id) {
        $("#updateNoticeSMS"+id).modal('show');
    }
</script>
@endsection
