@extends('backend.master')
@section('title') Contact From | Master Template @endsection
@section('contact-form-data') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Contact From List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('contact-form-data')}}"
                            class="text-primary py-2 px-3 active">All({{$allContactFormCount}})</a>
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
                        <th class="text-center" scope="col"><span>Details</span></th>
                        <th class="text-center" scope="col"><span>Message</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                        <th class="text-center" scope="col"><span>Date/Time</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($contactFormData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">   
                        <td>
                            <b>#{{$key+1}}</b>
                        </td>

                        <td class="text-start">
                            <div class="row_title">
                                <b>Name: </b>{{ $item->name }} <br>
                                <b>Phone: </b>{{ $item->phone }} <br>
                                <b>Email: </b>{{ $item->email }} <br>
                            </div>
                            <div class="row-actions mt-2">
                                @if($item->status == 1)
                                <span><a class="text-danger fw-bolder" href="{{route('contact-form-unsolved', $item->id)}}">Unsolved</a></span>
                                @else
                                <span><a class="text-success fw-bolder" href="{{route('contact-form-solved', $item->id)}}">Solved</a></span>
                                @endif
                            </div>
                        </td>

                        <td class="text-start">
                           {{ $item->message }}
                        </td>

                        <td>
                            @if($item->status == 0)
                            <span class="badge bg-warning">Pending</span>
                            @elseif($item->status == 1)
                            <span class="badge bg-success">Solved</span>
                            @else
                            <span class="badge bg-danger">Unsolved</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <span class="text-normal">{{Carbon\Carbon::parse($item->created_at->toDateString())->format('d-m-Y')}}</span>
                            <br> <span
                                class="text-normal">{{Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('h:i A')}}</span>
                        </td>

                    </tr>

                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allContactFormCount) && $allContactFormCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $contactFormData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $contactFormData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $contactFormData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $contactFormData->links('pagination::bootstrap-4') !!}
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