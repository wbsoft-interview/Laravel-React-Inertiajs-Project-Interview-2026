@extends('backend.master')
@section('content')
@section('title') User SMS | ITDER - It Development Education & Research @endsection
@section('userSMS') active @endsection
@section('userSMS.index') active @endsection
@section('styles')
<style>
    .table td,
    .table th {
        vertical-align: middle;
        text-align: center;
    }
</style>
@endsection

@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span>User SMS</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{ route('user-sms.index') }}"
                            class="text-primary py-2 px-3 active">All({{$userBalanceCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('user-sms-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addUserSMS">
                        <i class="fa fa-plus"></i>
                        <span class="">New User SMS</span></a>
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
                    <th class="text-center" scope="col"><span>Details</span></th>
                    <th class="text-center" scope="col"><span>Year/Month</span></th>
                    <th class="text-center" scope="col"><span>Purchase Date</span></th>
                    <th class="text-center" scope="col"><span>Approved</span></th>
                    <th class="text-center" scope="col"><span>Status</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($userBalanceData as $key=>$item)
                        @if(isset($item) && $item != null)
                            <tr class="">
                                <td>
                                    <b>#{{$key+1}}</b>
                                </td>
                                <td class="text-start">
                                    <div class="row_title">
                                        <b>Owner ID: </b> {{$item->owner_id}} <br>
                                        <b>Recharge Amount: </b> {{$item->total_price}} tk <br>
                                        <b>Total SMS: </b> {{$item->total_sms}}(qty) <br>
                                    </div>
                                    <div class="row-actions mt-2">

                                        @if (Auth::user()->can('user-sms-edit'))

                                        @if($item->status == 0)
                                        <span><a class="text-success fw-bolder" href="{{route('user-sms-active', $item->id)}}">Approved</a></span>
                                        <span> | <a class="text-danger fw-bolder" href="{{route('user-sms-inactive', $item->id)}}">Rejected</a></span>
                                        @endif

                                        @endif
                                    </div>
                                </td>

                                <td class="text-start">
                                    <b>Year: </b> {{$item->purchase_year}} <br>
                                    <b>Month: </b> {{$item->purchase_month}} <br>
                                </td>

                                <td>
                                    <span class="text-normal">{{Carbon\Carbon::parse($item->purchase_date)->format('d-m-Y')}}</span>
                                </td>

                                <td>
                                    @if($item->status == 1)
                                    <span class="text-normal">{{Carbon\Carbon::parse($item->updated_at->toDateString())->format('d-m-Y')}}</span>
                                    <br> <span
                                        class="text-normal">{{Carbon\Carbon::parse($item->updated_at)->setTimezone('Asia/Dhaka')->format('h:i A')}}</span>
                                    @else
                                    --
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 1)
                                    <span class="badge bg-success">Confirmed</span>
                                    @elseif($item->status == 2)
                                    <span class="badge bg-danger">Rejected</span>
                                    @else
                                    <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                

                            </tr>
                        @endif
                    @endforeach

                </tbody>
            </table>
        </div>

        @if(isset($userBalanceCount) && $userBalanceCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $userBalanceData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $userBalanceData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $userBalanceData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $userBalanceData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- //Add new User SMS.. --}}
<div class="modal fade" id="addUserSMS" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New User SMS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user-sms.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">

                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="owner_id">Owner<span class="text-danger">*</span>
                                </label>
                                <select name="owner_id" id="owner_id" class="form-control form-control-solid form-select select2" required>
                                    <option value="" disabled selected>Select Owner</option>
                                    @foreach($userData as $owner)
                                        @if(isset($owner) && $owner != null)
                                        <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                            {{ Str::title($owner->name) }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            @error('owner_id')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="total_sms">Total SMS<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="total_sms" class="form-control form-control-solid"
                                    value="{{old('total_sms')}}" placeholder="Total SMS" required>
                            </div>

                            @error('total_sms')
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
    $("#owner_id").select2({
        dropdownParent: $('#addUserSMS')
    });

    $(document).ready(function(){
        $("#phone").on('input', function() {
            if ($(this).val().length > 11) {
                toastr.error("Phone number must be 11 digits.");
                $(this).val($(this).val().slice(0, 11));
            }
        });
    });

    function updateUserSMS(id) {
        $("#updateUserSMS"+id).modal('show');
    }
</script>
@endsection
