@extends('backend.master')
@section('content')
@section('title') SMS Template | ITDER - It Development Education & Research @endsection
@section('sms-template') active @endsection
@section('sms-template.index') active @endsection
@section('styles')
@endsection

@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span>SMS Template</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{ route('sms-template.index') }}"
                            class="text-primary py-2 px-3 active">All({{$allSMSTemplateCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('sms-template-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addSMSTemplate">
                        <i class="fa fa-plus"></i>
                        <span class="">New Template</span></a>
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
                        <th class="text-center" scope="col"><span>SMS Ttitle</span></th>
                        <th class="text-center" scope="col"><span>SMS Details</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                        <th class="text-center" scope="col"><span>Date/Time</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($smsTemplateData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <b>#{{$key+1}}</b>
                        </td>
                        <td class="text-center">
                            <div class="row_title">

                                 {{ $item->sms_title }}
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('sms-template-edit'))

                                <span><button class="text-info border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateGroup({{$item->id}})"> Edit </button></span>

                                @endif
                                @if (Auth::user()->can('sms-template-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('sms-template-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>



                        <td>
                            <p>{{$item->sms_details}}</p>
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
                    <div class="modal fade" id="updateGroup{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Template</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('sms-template.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label for="sms_title">SMS Ttitle<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="sms_title" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->sms_title}}" placeholder="SMS Ttitle" required>
                                                </div>

                                                @error('sms_title')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>

                                            <!-- SMS Details -->
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label for="sms_details">SMS Details<span class="text-danger">*</span></label>
                                                    <textarea id="sms_details{{$item->id}}" rows="3" name="sms_details" required class="form-control form-control-solid"
                                                        placeholder="SMS Details">{{$item->sms_details}}</textarea>
                                                </div>
                                                @error('sms_details')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>
                                            
                                            <!-- Dynamic Tags -->
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label>Dynamic Tag</label>
                                                    <div class="body-section d-flex gap-1 my-2">
                                                        <button type="button" class="btn border" onclick="insertTagFU('{name}',{{$item->id}})">{name}</button>
                                                        <button type="button" class="btn border" onclick="insertTagFU('{email}',{{$item->id}})">{email}</button>
                                                        <button type="button" class="btn border" onclick="insertTagFU('{mobile_no}',{{$item->id}})">{mobile_no}</button>
                                                        <button type="button" class="btn border" onclick="insertTagFU('{class}',{{$item->id}})">{class}</button>
                                                        <button type="button" class="btn border" onclick="insertTagFU('{marksheet}',{{$item->id}})">{marksheet}</button>
                                                        <button type="button" class="btn border" onclick="insertTagFU('{marks}',{{$item->id}})">{marks}</button>
                                                        <button type="button" class="btn border" onclick="insertTagFU('{grade}',{{$item->id}})">{grade}</button>
                                                        <button type="button" class="btn border" onclick="insertTagFU('{gpa}',{{$item->id}})">{gpa}</button>
                                                        <button type="button" class="btn border" onclick="insertTagFU('{position}',{{$item->id}})">{position}</button>
                                                    </div>
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

                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allSMSTemplateCount) && $allSMSTemplateCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $smsTemplateData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $smsTemplateData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $smsTemplateData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $smsTemplateData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- //Add new counter.. --}}
<div class="modal fade" id="addSMSTemplate" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Template</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sms-template.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <!-- SMS Title -->
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="sms_title">SMS Title<span class="text-danger">*</span></label>
                                <input type="text" name="sms_title" required class="form-control form-control-solid"
                                    value="{{old('sms_title')}}" placeholder="SMS Title">
                            </div>
                            @error('sms_title')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
            
                        <!-- SMS Details -->
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="sms_details">SMS Details<span class="text-danger">*</span></label>
                                <textarea id="sms_details" rows="3" name="sms_details" required
                                    class="form-control form-control-solid"
                                    placeholder="SMS Details">{{old('sms_details')}}</textarea>
                            </div>
                            @error('sms_details')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
            
                        <!-- Dynamic Tags -->
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>Dynamic Tag</label>
                                <div class="body-section d-flex flex-wrap gap-2 my-2">
                                    <button type="button" class="btn border" onclick="insertTag('{name}')">{name}</button>
                                    <button type="button" class="btn border" onclick="insertTag('{email}')">{email}</button>
                                    <button type="button" class="btn border" onclick="insertTag('{mobile_no}')">{mobile_no}</button>
                                    <button type="button" class="btn border" onclick="insertTag('{class}')">{class}</button>
                                    <button type="button" class="btn border" onclick="insertTag('{marksheet}')">{marksheet}</button>
                                    <button type="button" class="btn border" onclick="insertTag('{marks}')">{marks}</button>
                                    <button type="button" class="btn border" onclick="insertTag('{grade}')">{grade}</button>
                                    <button type="button" class="btn border" onclick="insertTag('{gpa}')">{gpa}</button>
                                    <button type="button" class="btn border" onclick="insertTag('{position}')">{position}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Form Footer -->
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
    function updateGroup(id) {
        $("#updateGroup"+id).modal('show');
    }

    //To add tags data...
    function insertTag(tag) {
        const textarea = document.getElementById("sms_details");
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const before = text.substring(0, start);
        const after  = text.substring(end, text.length);

        textarea.value = before + tag + after;
        textarea.selectionStart = textarea.selectionEnd = start + tag.length;
        textarea.focus();
    }
    
    //To add tags data...
    function insertTagFU(tag, id) {
        const textarea = document.getElementById("sms_details"+id);
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const before = text.substring(0, start);
        const after  = text.substring(end, text.length);

        textarea.value = before + tag + after;
        textarea.selectionStart = textarea.selectionEnd = start + tag.length;
        textarea.focus();
    }
</script>
@endsection

