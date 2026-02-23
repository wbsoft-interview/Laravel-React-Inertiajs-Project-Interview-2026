@extends('backend.master')
@section('title') Expense Receipt | Amali @endsection
@section('expense-receipt') active @endsection
@section('expense-receipt.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Expense Receipt List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('expense-receipt.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allExpenseReceiptCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('expense-receipt-create'))
                    <a href="{{route('expense-receipt.create')}}"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                        <i class="fa fa-plus"></i>
                        <span class="">New Expense Receipt</span></a>
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
                        <th class="text-center" scope="col"><span>Expense Id</span></th>
                        <th class="text-center" scope="col"><span>Receipt By</span></th>
                        <th class="text-center" scope="col"><span>Created At</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($expenseReceiptData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">

                        <td>
                            <div class="row_title">
                                {{ $item->expense_receipt_id }}
                            </div>
                            <div class="row-actions mt-2">
                                @if (Auth::user()->can('expense-receipt-edit'))
                                @if($item->is_bill_pay != true)
                                <span><a class="edit_class_modal border-0 bg-transparent text-primary custom-invoice-service-details-sub"
                                        href="{{route('pending-all-expense-receipt', $item->id)}}"> Invoice View
                                    </a></span>
                                @else
                                <span><a class="edit_class_modal border-0 bg-transparent text-primary custom-invoice-service-details-sub"
                                        href="{{route('get-expense-receipt-payment-invoice', $item->id)}}"> Invoice View
                                    </a></span>
                                @endif
                                @endif
                            </div>
                        </td>

                        <td>
                            <span class="fw-bolder text-nomral">{{ Str::title($item->receipt_by) }}</span>
                        </td>

                        <td class="text-start">
                            <b>Date: </b> {{Carbon\Carbon::parse($item->created_at->toDateString())->format('d-m-Y')}} <br>
                            <b>Time: </b> {{Carbon\Carbon::create($item->created_at->toTimeString())->format('h:i')}}
                        </td>

                        <td>
                            @if($item->is_bill_pay == true)
                            <span class="badge custom-bg-green">Paid</span>
                            @else
                            <span class="badge bg-warning">Unpaid</span>
                            @endif
                        </td>
                    </tr>

                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allExpenseReceiptCount) && $allExpenseReceiptCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $expenseReceiptData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $expenseReceiptData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $expenseReceiptData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $expenseReceiptData->links('pagination::bootstrap-4') !!}
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