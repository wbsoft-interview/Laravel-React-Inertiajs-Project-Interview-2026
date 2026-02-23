@extends('backend.master')
@section('title') Account Details | Master Template @endsection
@section('purchase-account-profile') active @endsection
@section('styles')
<style>
    .setting-custom-select2-form .select2-container--default .select2-selection--single {
        padding-top: 5px;
        padding-bottom: 5px;
    }

</style>
@endsection

@section('main_content_section')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class=" d-flex align-items-center justify-content-between my-3">
        <div class="align-items-center">
            <h3 class="mb-0 pb-0">Account Details<span class="divider"></span></h3>
        </div>
    </div>

    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <section class="app-user-view-account">
                <div class="row">

                    <!-- User Content -->
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <div class=" d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0 pb-0">Package Details</h4>
                                    <div class="d-sm-block">
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-container table-responsive">
                                    <table id="" class="table table-bordered">
                                        <thead class="text-uppercase">
                                            <tr class="me-3">
                                                <th class="text-center" scope="col"><span>Package Owner</span></th>
                                                <th class="text-center" scope="col"><span>Package Name</span></th>
                                                <th class="text-center" scope="col"><span>Category</span></th>
                                                <th class="text-center" scope="col"><span>Validity</span></th>
                                                <th class="text-center" scope="col"><span>Opening Date</span></th>
                                                <th class="text-center" scope="col"><span>Expire Date</span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">

                                            <tr class="">

                                                <td>
                                                    <b>{{ Str::title($singleAdminPackageData->packageByData->name) }}</b>
                                                </td>

                                                <td>
                                                    {{ $singleAdminPackageData->packageData->package_name}}
                                                </td>

                                                <td>
                                                    {{ $singleAdminPackageData->packageData->packageCategoryData->category_name}}
                                                </td>

                                                <td>
                                                    {{ $singleAdminPackageData->packageData->package_validity}}
                                                </td>

                                                <td class="text-center">
                                                    <b></b><span
                                                        class="text-normal">{{Carbon\Carbon::parse($singleAdminPackageData->start_date)->format('d-m-Y')}}</span>
                                                </td>
                                                
                                                <td class="text-center">
                                                    <b></b><span
                                                        class="text-normal">{{Carbon\Carbon::parse($singleAdminPackageData->end_date)->format('d-m-Y')}}</span>
                                                </td>

                                            </tr>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ User Pills -->

                    <!-- User Content -->
                    <div class="col-md-12">
                        <h4 class="my-3 pb-0">Purchase Details</h4>
                        <div class="card shadow">
                            <div class="card-header">
                                <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0"><a href="{{route('purchase-account-profile', $singleAdminPackageData->id)}}"
                                                class="text-primary py-2 px-3 active">All({{$allAdminPackageHistoryCount}})</a>
                                        </p>
                                    </div>
                                    <div class="d-sm-block">
                                        <a href="javascript:void(0)" class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                                            <i class="fa fa-list"></i>
                                            <span class="">Purchase List</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-container table-responsive">
                                    <table id="" class="table table-bordered">
                                        <thead class="text-uppercase">
                                            <tr class="me-3">
                                                <th class="text-center" scope="col"><span>Purchase Date</span></th>
                                                <th class="text-center" scope="col"><span>Package</span></th>
                                                <th class="text-center" scope="col"><span>Category</span></th>
                                                <th class="text-center" scope="col"><span>Amount</span></th>
                                                <th class="text-center" scope="col"><span>By</span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">

                                            @foreach($adminPackageHistoryData as $key=>$item)
                                            @if(isset($item) && $item != null)
                                            <tr class="">
                                                <td>
                                                   <span class="text-normal fw-bolder">{{Carbon\Carbon::parse($item->start_date)->format('d-m-Y')}}</span>
                                                </td>
                                                <td> {{ $item->packageData->package_name }}</td>
                                                <td> {{ $item->packageData->packageCategoryData->category_name }}</td>

                                                <td>
                                                    {{ $item->packageData->package_price }}Tk
                                                </td>

                                                <td>
                                                    {{ Str::title($item->assignedData->name) }}
                                                </td>

                                            </tr>

                                            @endif
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>

                                @if(isset($allAdminPackageHistoryCount) && $allAdminPackageHistoryCount > 10)
                                <div class="clearfix d-flex">
                                    <div class="float-left">
                                        <p class="text-muted">
                                            {{ __('Showing') }}
                                            <span class="font-weight-bold">{{ $adminPackageHistoryData->firstItem() }}</span>
                                            {{ __('to') }}
                                            <span class="font-weight-bold">{{ $adminPackageHistoryData->lastItem() }}</span>
                                            {{ __('of') }}
                                            <span class="font-weight-bold">{{ $adminPackageHistoryData->total() }}</span>
                                            {{ __('results') }}
                                        </p>
                                    </div>

                                    <div class="float-right custom-table-pagination">
                                        {!! $adminPackageHistoryData->links('pagination::bootstrap-4') !!}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--/ User Pills -->

                </div>
                <!--/ User Content -->
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection

@section('scripts')
@endsection
