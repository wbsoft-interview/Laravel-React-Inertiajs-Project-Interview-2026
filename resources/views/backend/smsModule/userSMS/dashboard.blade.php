@extends('backend.master')
@section('content')
@section('title') SMS Dashboard | ITDER - It Development Education & Research @endsection
@section('sms-dashboard') active @endsection
@section('sms-dashboard.index') active @endsection
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
        <h3 class="mb-0"><span>SMS Dashboard</span></h3>
    </div>
</div>

<div class="row">

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">Total Purchase SMS</h6>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="card-text">All Time</p>
                    <h6>{{ number_format($totalSMSCount, 2) }} (qty)</h6>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">This Month Purchase</h6>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="card-text">Total</p>
                    <h6>{{ number_format($thisMonthSMS, 2) }} (qty)</h6>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">This Year Purchase</h6>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="card-text">Total</p>
                    <h6>{{ number_format($thisYearSMS, 2) }} (qty)</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">Available SMS</h6>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="card-text">Total</p>
                    <h6>{{ number_format($availableSMSCount, 2) }} (qty)</h6>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">Cost This Month</h6>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="card-text">Total</p>
                    <h6>{{ number_format($thisMonthCost, 2) }} tk</h6>
                </div>
            </div>
        </div>
    </div>
    
    {{-- <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">This Year SMS</h6>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="card-text">Total</p>
                    <h6>{{ number_format($thisYearSMS, 2) }} (qty)</h6>
                </div>
            </div>
        </div>
    </div> --}}
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">Cost This Year</h6>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="card-text">Total</p>
                    <h6>{{ number_format($thisYearCost, 2) }} tk</h6>
                </div>
            </div>
        </div>
    </div>
    
    {{-- <div class="col-md-12 mb-4">
        <div class="table_wrapper py-1 card">
            <h4 class="my-3 px-3"><span>SMS Recharge History</span></h4>
            <div class="row my-2 aggregate-section-div">
                <div class="px-3 ">
                    <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                        <div class="d-flex align-items-center">
                            <p class="mb-0"><a href="{{ route('sms-dashboard.index') }}"
                                    class="text-primary py-2 px-3 active">All({{$userBalanceCount}})</a>
                            </p>
                        </div>
                        <div class="d-sm-block">
                            @if(Auth::user()->can('user-sms-create'))
                            <a href="javascript:void(0)"
                                class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0"
                                data-bs-toggle="modal" data-bs-target="#addUserSMS">
                                <i class="fa fa-plus"></i>
                                <span class="">New SMS Recharge</span></a>
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
                                    </div>
                                </td>
        
                                <td class="text-start">
                                    <b>Year: </b> {{$item->purchase_year}} <br>
                                    <b>Month: </b> {{$item->purchase_month}} <br>
                                </td>
        
                                <td>
                                    <span
                                        class="text-normal">{{Carbon\Carbon::parse($item->purchase_date)->format('d-m-Y')}}</span>
                                </td>
        
                                <td>
                                    @if($item->status == 1)
                                    <span
                                        class="text-normal">{{Carbon\Carbon::parse($item->updated_at->toDateString())->format('d-m-Y')}}</span>
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
    </div> --}}

    <div class="col-md-12 mb-4">
        <h4 class="mb-3"><span>SMS Reports</span></h4>
        <div class="card shadow-sm h-100">
            {{-- <div class="card-header">
                <div class="icon icon-primary d-flex">
                    <span class="material-symbols-outlined my-auto">
                        message
                    </span>
                    <p class="category custom-card-header-title my-auto"><strong>This Year SMS Reports</strong></p>
                </div>
            </div> --}}
            <div class="card-body">
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr class="text-center">
                            <th>Month</th>
                            <th>Notice SMS</th>
                            <th>Total SMS</th>
                            <th>Cost (BDT)</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        @foreach($smsReports as $row)
                        <tr class="text-center">
                            <td>{{ $row['month'] }}</td>
                            <td>{{ number_format($row['total'], 0) }}</td>
                            <td><strong>{{ number_format($row['total'], 0) }}</strong></td>
                            <td>{{ number_format($row['cost'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- //Add new User SMS.. --}}
<div class="modal fade" id="addUserSMS" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New SMS Recharge</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sms-dashboard.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="owner_id" value="{{$adminID}}">
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="total_price">Amount<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="total_price" id="total_price" class="form-control form-control-solid"
                                    value="{{old('total_price')}}" placeholder="Amount" step="0.01" required>
                            </div>

                            @error('total_price')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="total_sms">Total SMS<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="total_sms" id="total_sms" class="form-control form-control-solid custom-readonly-color"
                                    value="{{old('total_sms')}}" placeholder="Total SMS" readonly required>
                            </div>

                            @error('total_sms')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="purchase_date">Purchae Date<span class="text-danger"></span>
                                </label>
                                <input type="text" class=" form-control flatpickr-basic custom-date-picker" name="purchase_date"
                                    id="purchase_date" placeholder="DD-MM-YYYY" value="{{old('purchase_date')}}">
                            </div>
                        
                            @error('purchase_date')
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
    $(document).ready(function(){
        $("#purchase_date").flatpickr({
            allowInput: true,
            dateFormat: "d-m-Y",
        });
    });

    $(document).ready(function(){
        $('input[name="total_price"]').on('input', function() {
            let price = parseFloat($(this).val());
            let perSMS = 0.40;

            if (!isNaN(price) && price > 0) {
                let totalSMS = Math.floor(price / perSMS);
                $('input[name="total_sms"]').val(totalSMS);
            } else {
                $('input[name="total_sms"]').val('');
            }
        });
    });

    function updateUserSMS(id) {
        $("#updateUserSMS"+id).modal('show');
    }
</script>
@endsection
