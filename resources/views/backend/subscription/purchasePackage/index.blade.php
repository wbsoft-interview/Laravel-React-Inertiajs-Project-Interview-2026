@extends('backend.master')
@section('title') Package | Master Template @endsection
@section('purchase-account-list') active @endsection
@section('purchase-account-list') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Package List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('purchase-account-list')}}"
                            class="text-primary py-2 px-3 active">All({{$allAdminPackageCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                        <i class="fa fa-list"></i>
                        <span class="">Purchase Accounts</span></a>
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
                        <th class="text-center" scope="col"><span>Account</span></th>
                        <th class="text-center" scope="col"><span>Package Basic</span></th>
                        <th class="text-center" scope="col"><span>Package Details</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                        <th class="text-center" scope="col"><span>Start/Date</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($adminPackageData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <b>#{{$key+1}}</b>
                        </td>
                        <td class="text-start">  
                            <div class="row_title">
                                <b>Name: </b> {{ Str::title($item->packageByData->name) }} <br>
                                <b>Mobile: </b> {{ $item->packageByData->mobile }} <br>
                                <b>Email: </b> {{ $item->packageByData->email }} <br>
                            </div>
                            <div class="row-actions mt-2">
                                <span><a class="text-primary fw-bolder"
                                        href="{{route('purchase-account-profile', $item->id)}}">Account Details</a>
                                </span>
                            </div>
                        </td>

                        <td class="text-start">
                            <b>Name: </b> {{ $item->packageData->package_name }} <br>
                            <b>Validity: </b> {{ $item->packageData->package_validity }} (Days) <br>
                            <b>Category: </b> {{ $item->packageData->packageCategoryData->category_name }}
                        </td>

                        <td class="text-start">
                            <b>Price: </b>{{ $item->packageData->package_price }} <br>
                            <b>SMS Qty: </b>{{ $item->packageData->sms_qty > 0 ? $item->packageData->sms_qty.' qty': '0 qty' }} <br>
                            <b>Student Qty: </b>{{ $item->packageData->student_qty > 0 ? $item->packageData->student_qty.' qty': '0 qty' }}
                        </td>
                        
                        <td>
                            @php
                            $status = strtolower($item->status);
                            $badgeClass = match($status) {
                            'active' => 'bg-success',
                            'expired' => 'bg-danger',
                            'upgraded' => 'bg-warning',
                            default => 'bg-secondary',
                            };
                            @endphp
                        
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <td class="text-start">
                            <b>Start: </b><span class="text-normal">{{Carbon\Carbon::parse($item->start_date)->format('d-m-Y')}}</span>
                            <br> 
                            <b>End: </b><span class="text-normal">{{Carbon\Carbon::parse($item->end_date)->format('d-m-Y')}}</span>
                            
                        </td>

                    </tr>

                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allAdminPackageCount) && $allAdminPackageCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $adminPackageData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $adminPackageData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $adminPackageData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $adminPackageData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')

<script>
</script>
@endsection