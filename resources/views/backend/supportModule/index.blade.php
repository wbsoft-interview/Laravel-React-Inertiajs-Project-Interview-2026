@extends('backend.master')
@section('content')
@section('title') Support Ticket | ITDER - It Development Education & Research @endsection
@section('ticket-support') active @endsection
@section('ticket-support.index') active @endsection
@section('styles')
@endsection

@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span>Support Ticket</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{ route('ticket-support.index') }}"
                            class="text-primary py-2 px-3 active">All({{$allSupportTicketCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('ticket-support-create'))
                    <a href="{{ route('ticket-support.create') }}"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                        <i class="fa fa-plus"></i>
                        <span class="">New Tickets</span></a>
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
                        <th class="text-center" scope="col"><span>Support ID</span></th>
                        <th class="text-center" scope="col"><span>Subject</span></th>
                        <th class="text-center" scope="col"><span>Type</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                        <th class="text-center" scope="col"><span>Last Updated</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($supportTicketData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <b>#{{$item->ticket_number}}</b>
                        </td>
                        <td class="text-center">
                            <div class="row_title">
                                 {{ $item->subject }}
                            </div>
                            <div class="row-actions mt-2">
                                @if (Auth::user()->can('ticket-support-edit'))
                                
                                <span><a class="text-primary fw-bolder"
                                        href="{{route('ticket-support-details', $item->id)}}">View Details</a>
                                </span>

                                @if($item->status != 2)
                                @if(Auth::user()->role == 'superadmin')
                                <span> | <a class="text-danger fw-bolder" href="{{route('ticket-support-close', $item->id)}}">Close Ticket</a>
                                </span>
                                @endif
                                @endif
                                @endif
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-success">{{$item->support_type}}</span>
                        </td>
                       
                        <td>
                            @if($item->status == 0)
                            <span class="badge bg-warning">Pending</span>
                            @elseif($item->status == 1)
                            <span class="badge bg-success">Answered</span>
                            @else
                            <span class="badge bg-secondary">Closed</span>
                            @endif
                        </td>

                        <td>
                            <span class="text-normal">{{Carbon\Carbon::parse($item->updated_at->toDateString())->format('d-m-Y')}}</span>
                            <br> <span
                                class="text-normal">{{Carbon\Carbon::parse($item->updated_at)->setTimezone('Asia/Dhaka')->format('h:i A')}}</span>
                        </td>

                    </tr>

                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allSupportTicketCount) && $allSupportTicketCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $supportTicketData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $supportTicketData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $supportTicketData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $supportTicketData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
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

