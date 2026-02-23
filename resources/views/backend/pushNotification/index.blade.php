@extends('backend.master')
@section('content')
@section('title') Notification | ITDER - It Development Education & Research @endsection
@section('push-notification') active @endsection
@section('push-notification.index') active @endsection
@section('styles')
@endsection

@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span>Notification</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{ route('push-notification.index') }}"
                            class="text-primary py-2 px-3 active">All({{$pushNotificationCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('push-notification-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0"
                        data-bs-toggle="modal" data-bs-target="#addSMSTemplate">
                        <i class="fa fa-plus"></i>
                        <span class="">New Notification</span></a>
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

                    @foreach($pushNotificationData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <b>#{{$key+1}}</b>
                        </td>
                        <td class="text-center">
                            <div class="row_title">
                                <span>{{ $item->smsTemplateData->sms_title }}</span><br>
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('push-notification-edit'))

                                <span><button class="text-info border-0 bg-transparent fw-bolder"
                                        value="{{ $item->id }}" onclick="updateGroup({{$item->id}})"> Edit
                                    </button></span>

                                @endif
                                @if (Auth::user()->can('push-notification-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('push-notification-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif

                            </div>
                        </td>


                        <td>
                             {{$item->smsTemplateData->sms_details}} 
                        </td>
                        
                        <td>
                            @if($item->status == true)
                            <span class="badge bg-success">Sent</span>
                            @else
                            <span class="badge bg-danger">Pending</span>
                            @endif
                        </td>

                        <td>
                            <b>Date: </b> <span>{{Carbon\Carbon::parse($item->sending_date)->format('d-m-Y')}}</span><br>
                            <b>Time: </b><span>{{$item->sending_time}}</span>
                        </td>

                    </tr>


                    {{-- //Update ServiceCategory.. --}}
                    <div class="modal fade" id="updateGroup{{$item->id}}" tabindex="-1" aria-labelledby="oneInputModalLabel"
                        aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Notification</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{route('push-notification.update', $item->id)}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="sending_date">Date <span class=" text-danger">*</span></label>
                                                    <input type="text" class=" form-control flatpickr-basic custom-date-picker" name="sending_date"
                                                        id="sending_date{{$item->id}}" placeholder="DD-MM-YYYY" value="{{Carbon\Carbon::parse($item->sending_date)->format('d-m-Y')}}" required>
                                                </div>
                                            
                                                @error('sending_date')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="sending_date">Time <span class=" text-danger">*</span></label>
                                                    <input type="text" class=" form-control flatpickr-time custom-time-picker" name="sending_time" id="sending_time{{$item->id}}"
                                                        placeholder="HH:MM" value="{{$item->sending_time}}" required>
                                                </div>
                                            
                                                @error('sending_time')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group custom-select2-form">
                                                    <label for="role_id">Role<span class="text-danger">*</span></label>
                                                    <select name="role_id" id="role_id{{$item->id}}" class="form-control form-control-solid form-select select2" required>
                                                        <option value="" disabled selected>Select Role</option>
                                                        @foreach($roleData as $role)
                                                        <option value="{{ $role->id }}"
                                                            {{$item->role_id == $role->id ? 'selected' : ''}}
                                                            >{{ Str::title($role->display_name) }}</option>
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
                                                    <select name="sms_template_id" id="sms_template_id{{$item->id}}" class="form-control form-control-solid" required>
                                                        <option value="" selected disabled>Select Template</option>
                                                        @foreach($smsTemplateMData as $singleSTData)
                                                        <option value="{{ $singleSTData->id }}"
                                                            {{$item->sms_template_id == $singleSTData->id ? 'selected' : ''}}
                                                            >{{ $singleSTData->sms_title }}</option>
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
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endif
                    @endforeach

                </tbody>
            </table>
        </div>

        @if(isset($pushNotificationCount) && $pushNotificationCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $pushNotificationData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $pushNotificationData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $pushNotificationData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $pushNotificationData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- //Add new counter.. --}}
<div class="modal fade" id="addSMSTemplate" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true"
    data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Notification</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('push-notification.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="sending_date">Date <span class=" text-danger">*</span></label>
                                <input type="text" class=" form-control flatpickr-basic custom-date-picker" name="sending_date"
                                    id="sending_date" placeholder="DD-MM-YYYY" required>
                            </div>
                        
                            @error('sending_date')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="sending_date">Time <span class=" text-danger">*</span></label>
                                <input type="text" class=" form-control flatpickr-time custom-time-picker" name="sending_time" id="sending_time"
                                    placeholder="HH:MM" required>
                            </div>
                        
                            @error('sending_time')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        
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
    $("#sending_date").flatpickr({
		allowInput: true,
        dateFormat: "d-m-Y",
	});

	$("#sending_time").flatpickr({
		enableTime: true,
		noCalendar: true,
		allowInput: true
	});

    $("#role_id").select2({
        dropdownParent: $('#addSMSTemplate')
    });
    $("#sms_template_id").select2({
        dropdownParent: $('#addSMSTemplate')
    });

    //To show update modal...
    function updateGroup(id) {
        $("#updateGroup"+id).modal('show');

        $("#sending_date"+id).flatpickr({
        allowInput: true,
        dateFormat: "d-m-Y",
        });
        
        $("#sending_time"+id).flatpickr({
        enableTime: true,
        noCalendar: true,
        allowInput: true
        });

        $("#role_id"+id).select2({
        dropdownParent: $('#updateGroup'+id)
        });
        $("#sms_template_id"+id).select2({
        dropdownParent: $('#updateGroup'+id)
        });
    }
</script>
@endsection
