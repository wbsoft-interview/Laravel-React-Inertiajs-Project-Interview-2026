@extends('backend.master')
@section('title') User Activity | Master Template @endsection
@section('user-activity') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Activity Logs</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('user-activity')}}"
                            class="text-primary py-2 px-3 active">All({{$allActivityLogCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0"
                        data-bs-toggle="modal" data-bs-target="#addPackageTag">
                        <i class="fa fa-list"></i>
                        <span class="">Contact List</span></a>
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
                        <th class="text-center" scope="col"><span>User</span></th>
                        <th class="text-center" scope="col">Module</th>
                        <th class="text-center" scope="col">Message</th>
                        <th class="text-center" scope="col">IP Address</th>
                        <th class="text-center" scope="col">Log Time</th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($activityLogData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">   
                        <td>
                            <b>#{{$key+1}}</b>
                        </td>

                        <td class="text-center">
                            <div class="row_title">
                                <b>{{ Str::title($item->accessByData->name) }}</b>
                            </div>
                            <div class="row-actions mt-2">
                            </div>
                        </td>

                        <td class="text-center">
                            {{ $item->module }}
                        </td>

                        <td>
                            {{ $item->description }}
                        </td>

                        <td class="text-center">
                            {{ $item->ip_address ?? 'N/A' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('d-m-Y \a\t h:i A') }}
                        </td>

                    </tr>

                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allActivityLogCount) && $allActivityLogCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $activityLogData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $activityLogData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $activityLogData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $activityLogData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')

<script>
    //To show update modal...
    function updatePackageTag(id) {
        $("#updatePackageTag"+id).modal('show');
    }
</script>
@endsection