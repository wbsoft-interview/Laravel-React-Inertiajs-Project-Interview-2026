@extends('backend.master')
@section('title') Expense Receipt | Amali @endsection
@section('get-client-due-bill-payment-invoice') active @endsection
@section('get-client-due-bill-payment-invoice') active @endsection
@section('styles')
<style>
    .custom-billing-invoice-logo img {
        height: auto !important;
        width: auto !important;
    }
</style>
@endsection


@section('main_content_section')

<div class="row min-vh-100">
    <!-- all content will be here. start -->
    <form action="{{route('save-all-expense-receipt')}}" method="POST" enctype="multipart/form-data"
        id="billingRootForm" class="p-0">
        @csrf
        <input type="hidden" name="expense_receipt_id" id="expense_receipt_id"
            value="{{$singleExpenseReceiptData->id}}">

        <div class="row invoice p-0 m-0">
            <div class="col-md-9 p-0 m-0">
                <div class="shadow rounded m-3">

                    @php
                    ///To get logo...
                    $softLogo = App\Models\InvoiceLogo::getSoftwareInvoiceLogo();
                    @endphp
                    <div class="d-md-flex justify-content-between custom-billing-invoice-header">
                        <div class="custom-invoice-logo-section">
                            <div class="d-flex align-items-center  custom-billing-invoice-logo">
                                @if(isset($softLogo) && $softLogo->logo_image != null)
                                <img src="{{ asset('backend/uploads/invoiceLogoImg/'.$softLogo->logo_image) }}"
                                    class="mt-4" alt="logo">
                                @else
                                <img src="{{asset('backend')}}/billing_invoice_logo.png" class="mt-4" alt="logo">
                                @endif
                            </div>
                        </div>
                        <div class="custom-issue-due-date-section">

                            <div class="d-flex align-items-center gap-2">
                                <div
                                    class="d-flex flex-column justify-content-end custom-issue-due-date-text-area gap-2">
                                    <p class="">Receipt Id: </p>
                                    <p class="">Date: </p>
                                    <p class="">Reciever: </p>
                                </div>

                                <div class="d-flex flex-column gap-2 custom-billing-invoice-header-right">
                                    <input type="text" class="form-control custom-readonly-bg-color flatpickr-basic custom-date-picker"
                                        value="#{{$singleExpenseReceiptData->expense_receipt_id}}" readonly>
                                    <input type="text"
                                        class=" form-control custom-readonly-bg-color flatpickr-basic custom-date-picker"
                                        name="date_issue" id="date_issue" placeholder="DD-MM-YYYY"
                                        value="{{Carbon\Carbon::parse($singleExpenseReceiptData->created_at->toDateString())->format('d-m-Y')}}"
                                        readonly>
                                    <input type="text"
                                        class="form-control custom-readonly-bg-color flatpickr-basic custom-date-picker"
                                        value="{{Str::title(Auth::user()->name)}}" readonly>
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>

                    {{-- //To get update expense details page... --}}
                    <span>

                        <div class="row px-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <h5>Expense Receipt List</h5>
                                    <table class="table table-bordered py-3" id="updateExpenseDetails">
                                        <!-- <hr class="mb-1"> -->
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col"><span>SN</span></th>
                                                <th class="text-center" scope="col"><span>Expense</span></th>
                                                <th class="text-center" scope="col"><span>Details</span></th>
                                                <th class="text-center" scope="col"><span>Amount</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($unpaidExpenseServiceData as $key=>$item)
                                            @if(isset($item) && $item != null)
                                            <tr class="text-center">
                                                <td>
                                                    <b>{{$key+1}}</b>
                                                </td>
                                                <td class="text-start">
                                                    <div class="row_title">
                                                        <b>Category: </b>{{$item->expenseCategoryData->category_name}} <br>
                                                        <b>Expense: </b>{{$item->expenseData->expense_name}}
                                                    </div>
                                                    <div class="row-actions mt-2">
                                                        @if (Auth::user()->can('expense-receipt-edit'))
                                                        <span><a class="text-primary fw-bolder" href="javascript(void.0)"
                                                                onclick="editExpenseReceipt({{$item->id}})">Edit</a>
                                                        </span>
                                                        @endif
                                                
                                                        @if (Auth::user()->can('expense-receipt-delete'))
                                                        <span> | <a class="text-danger fw-bolder" href="javascript(void.0)"
                                                                onclick="deleteExpenseServiceData({{$item->id}})">Delete</a>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <td class="text-start w-50">
                                                    <b>Payee: </b>{{$item->payeeData->payee_name}}
                                                    <br>
                                                    <b>Details: </b>{{$item->expense_details}}
                                                </td>

                                                <td class="text-center">
                                                    <span
                                                        class="fw-bolder text-success">{{$item->expense_amount}}</span>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="editExpenseReceipt{{$item->id}}" tabindex="-1" aria-labelledby="oneInputModalLabel"
                                                aria-hidden="true" data-bs-backdrop='static'>
                                                <div class="modal-dialog modal-dialog-centered max-width-900px">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Expense Receipt</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="#" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" id="expense_receipt_id{{$item->id}}" value="{{$item->expense_receipt_id}}">
                                                            <div class="modal-body p-0">
                                                                <div class="row px-4 my-4">
                                            
                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-group custom-select2-form">
                                                                            <label for="expense_category_id">Expense Category <span
                                                                                    class=" text-danger">*</span>
                                                                            </label>
                                                                            <select name="expense_category_id" id="expense_category_id{{$item->id}}"
                                                                                class="form-select select2" onchange="getExpenseDataWithCateFU({{$item->id}})"
                                                                                required>
                                                                                <option value="" selected disabled>Select Category</option>
                                                                                @foreach($expenseCategoryData as $singleECD)
                                                                                <option value="{{$singleECD->id}}"
                                                                                    {{$singleECD->id == $item->expense_category_id ? 'selected' : ''}}>
                                                                                    {{$singleECD->category_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                            
                                                                        @error('expense_category_id')
                                                                        <span class=text-danger>{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                            
                                                                    @php
                                                                    //To get all the expense data with category...
                                                                    $getExpenseData = App\Models\Expense::getExpenseDataWithCategory($item->expense_category_id);
                                                                    // dd($getExpenseData);
                                                                    @endphp
                                            
                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-group custom-select2-form">
                                                                            <label for="expense_id">Expense <span class=" text-danger">*</span>
                                                                            </label>
                                                                            <select name="expense_id" id="expense_id{{$item->id}}" class="form-select select2"
                                                                                required>
                                                                                <option value="" selected disabled>Select Expense</option>
                                            
                                                                                @foreach($getExpenseData as $singleED)
                                                                                <option value="{{$singleED->id}}"
                                                                                    {{$singleED->id == $item->expense_id ? 'selected' : ''}}>
                                                                                    {{$singleED->expense_name}}</option>
                                                                                @endforeach
                                            
                                                                            </select>
                                                                        </div>
                                            
                                                                        @error('expense_id')
                                                                        <span class=text-danger>{{$message}}</span>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-group custom-select2-form">
                                                                            <label for="payee_id">Payee <span class=" text-danger">*</span>
                                                                            </label>
                                                                            <select name="payee_id" id="payee_id{{$item->id}}" class="form-select select2" required>
                                                                                <option value="" selected disabled>Select Payee</option>
                                                                                @foreach($payeeData as $singlePD)
                                                                                <option value="{{$singlePD->id}}" {{$singlePD->id == $item->payee_id ? 'selected' : ''}}>
                                                                                    {{$singlePD->payee_name}} / {{$singlePD->payee_phone}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    
                                                                        @error('payee_id')
                                                                        <span class=text-danger>{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                            
                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-group">
                                                                            <label for="expense_amount">Expense Amount<span class="text-danger">*</span>
                                                                            </label>
                                                                            <input type="number" name="expense_amount" id="expense_amount{{$item->id}}" required
                                                                                class="form-control form-control-solid" value="{{$item->expense_amount}}"
                                                                                step="0.01" placeholder="Expense Amount">
                                                                        </div>
                                            
                                                                        @error('expense_amount')
                                                                        <span class=text-danger>{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                            
                                                                    <div class="col-md-12 mb-3">
                                                                        <div class="form-group">
                                                                            <label for="expense_details">Expense Details<span class="text-danger">*</span>
                                                                            </label>
                                                                            <textarea rows="3" cols="3" name="expense_details" id="expense_details{{$item->id}}"
                                                                                required class="form-control form-control-solid" value=""
                                                                                placeholder="Expense Details">{{$item->expense_details}}</textarea>
                                                                        </div>
                                            
                                                                        @error('expense_details')
                                                                        <span class=text-danger>{{$message}}</span>
                                                                        @enderror
                                                                    </div>
                                            
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success"
                                                                    onclick="updateExpenseReceipt({{$item->id}})">Update</button>
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="custom-invoice-common-padding pb-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="">

                                        {{-- <p class="mb-1">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nisi, illo!
                                        </p> --}}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <div class="w-100 d-flex gap-2">
                                        <div class="text-end w-50 custom-footer-right-text-block">
                                            <p>Expense Qty:</p>
                                            <p>Total Amount:</p>
                                        </div>
                                        <div class="d-flex flex-column gap-2 w-50">
                                            <input type="text" name="sub_total_expense_quantity"
                                                id="sub_total_expense_quantity"
                                                class="form-control custom-cursor-pointer custom-readonly-bg-color"
                                                value="{{ $totalExpenseServiceQty > 0 ? $totalExpenseServiceQty : 0}}" readonly>
                                            <input type="text" name="sub_total_expense_amount"
                                                id="sub_total_expense_amount"
                                                class="form-control custom-cursor-pointer custom-readonly-bg-color"
                                                value="{{ $totalExpenseServiceAmount > 0 ? $totalExpenseServiceAmount : 0}}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </span>

                </div>
            </div>
            <div class="col-md-3 p-0 m-0">
                <div class="row p-3">
                    <div class="col-12 mb-4">
                        <div class="">
                            <div class="card card-stats">
                                <div class="card-header custom-payment-section-header">
                                    <div class="icon icon-warning d-flex">
                                        <span class="material-symbols-outlined">equalizer</span>
                                        <p
                                            class="category custom-card-header-title custom-payment-section-heading-title">
                                            <strong>Expense Receipt</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="card-footer p-0 dashboard-card-footer">
                                    <div class="stats">
                                        <div class="row">
                                            <a href="javascript(void.0)" class="text-white btn" data-bs-toggle="modal"
                                                data-bs-target="#addExpenseReceipt">
                                                <div class="col-12 invoice-print-button text-center py-2">
                                                    <i class="fa fa-plus"></i>
                                                    <span class="pl-4">New Receipt</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="">
                            <div class="card card-stats">
                                <div class="card-header custom-payment-section-header">
                                    <div class="icon icon-warning d-flex">
                                        <span class="material-symbols-outlined">equalizer</span>
                                        <p
                                            class="category custom-card-header-title custom-payment-section-heading-title">
                                            <strong>Receipt Notes</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="card-content px-3 custom-payment-section-body">

                                    <div class="mb-2">
                                        <div class="form-group custom-select2-form">
                                            <label for="receipt_notes"> Notes <span
                                                    class=" text-danger">*</span>
                                            </label>

                                            <textarea placeholder="Enter Notes" class="form-control"
                                                name="receipt_notes" id="receipt_notes" cols="2" rows="2"
                                                required>{{$singleExpenseReceiptData->receipt_notes}}</textarea>
                                        </div>

                                        @error('receipt_notes')
                                        <span class=text-danger>{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer p-0 dashboard-card-footer">
                                    <div class="stats">
                                        <div class="row">
                                            <button type="submit" class="text-white btn">
                                                <div class="col-12 bg-success text-center py-2">Save</div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </form>
    <!-- all content will be here. end -->

    {{-- //Add new expense.. --}}
    <div class="modal fade" id="addExpenseReceipt" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true"
        data-bs-backdrop='static'>
        <div class="modal-dialog modal-dialog-centered max-width-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="oneInputModalLabel">New Expense Receipt</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post" enctype="multipart/form-data" onsubmit="return addNewExpenseReceipt()">
                    @csrf

                    <div class="modal-body p-0">
                        <div class="row px-4 my-4">

                            <div class="col-md-6 mb-3">
                                <div class="form-group custom-select2-form 
                                {{$expenseCategoryData->count() > 0 ? '' : 'custom-null-select2-body'}}
                                ">
                                    <label for="expense_category_id">Expense Category <span
                                            class=" text-danger">*</span>
                                    </label>
                                    <select name="expense_category_id" id="expense_category_id"
                                        class="form-select select2" onchange="getExpenseDataWithCate()" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach($expenseCategoryData as $singleECD)
                                        <option value="{{$singleECD->id}}">{{$singleECD->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('expense_category_id')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group custom-select2-form">
                                    <label for="expense_id">Expense <span class=" text-danger">*</span>
                                    </label>
                                    <select name="expense_id" id="expense_id" class="form-select select2" required>
                                        <option value="" selected disabled>Select Expense</option>

                                    </select>
                                </div>

                                @error('expense_id')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group custom-select2-form">
                                    <label for="payee_id">Payee <span class=" text-danger">*</span>
                                    </label>
                                    <select name="payee_id" id="payee_id" class="form-select select2" required>
                                        <option value="" selected disabled>Select Payee</option>
                                        @foreach($payeeData as $singlePD)
                                        <option value="{{$singlePD->id}}">{{$singlePD->payee_name}} / {{$singlePD->payee_phone}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                @error('payee_id')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="expense_amount">Expense Amount<span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="expense_amount" id="expense_amount" required
                                        class="form-control form-control-solid" value="" step="0.01"
                                        placeholder="Expense Amount">
                                </div>

                                @error('expense_amount')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="expense_details">Expense Details<span
                                            class="text-danger">*</span>
                                    </label>
                                    <textarea rows="3" cols="3" name="expense_details" id="expense_details" required
                                        class="form-control form-control-solid" value=""
                                        placeholder="Expense Details"></textarea>
                                </div>

                                @error('expense_details')
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


</div>

@endsection

@section('scripts')
<script>
    $("#expense_category_id").select2({
        dropdownParent: $('#addExpenseReceipt')
    });
    $("#expense_id").select2({
        dropdownParent: $('#addExpenseReceipt')
    });
    $("#payee_id").select2({
        dropdownParent: $('#addExpenseReceipt')
    });

    $(document).ready(function(){
        $("#project_id").select2();
    });
    
    $(document).ready(function(){
        $("#employee_id").select2();
    });

    //To add new expense receipt...
    function addNewExpenseReceipt() {
        event.preventDefault();
        var expense_receipt_id = $("#expense_receipt_id").val();
        var expense_category_id = $("#expense_category_id").val();
        var expense_id = $("#expense_id").val();
        var payee_id = $("#payee_id").val();
        var expense_amount = $("#expense_amount").val();
        var expense_details = $("#expense_details").val();
        var url = "{{ route('expense-receipt.store') }}";
        if(expense_receipt_id != ''){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    expense_receipt_id: expense_receipt_id,
                    expense_category_id: expense_category_id,
                    expense_id: expense_id,
                    payee_id: payee_id,
                    expense_amount: expense_amount,
                    expense_details: expense_details
                },
                success: function (data) {
                    if(data.error){
                        toastr.error(data.error);
                    }else{
                        $('#addExpenseReceipt').modal('hide');
                        toastr.success('ExpenseReceipt created successfully.');
                        $('#expense_category_id').val(null).trigger("change");
                        $('#expense_id').val(null).trigger("change");
                        $('#payee_id').val(null).trigger("change");
                        $('#expense_amount').val('');
                        $('#expense_details').val('');
                        $('#updateExpenseDetails').html(data);

                        let updateServiceQty = $('#update_expense_service_qty').val();
                        let updateServiceAmount = $('#update_expense_service_amount').val();
                        $('#sub_total_expense_quantity').val(updateServiceQty);
                        $('#sub_total_expense_amount').val(updateServiceAmount);
                    }
                }
            });
        }
    }

    //To edit expense service data...
    function editExpenseReceipt(id) {
        event.preventDefault(); 
        $('#editExpenseReceipt'+id).modal('show');
        $("#expense_category_id"+id).select2({
            dropdownParent: $('#editExpenseReceipt'+id)
        });
        $("#expense_id"+id).select2({
            dropdownParent: $('#editExpenseReceipt'+id)
        });
        $("#payee_id"+id).select2({
            dropdownParent: $('#editExpenseReceipt'+id)
        });

    }

    //To update expense receipt...
    function updateExpenseReceipt(id) {
        event.preventDefault();
        var expense_service_id = id;
        var expense_receipt_id = $("#expense_receipt_id"+id).val();
        var expense_category_id = $("#expense_category_id"+id).val();
        var expense_id = $("#expense_id"+id).val();
        var payee_id = $("#payee_id"+id).val();
        var expense_amount = $("#expense_amount"+id).val();
        var expense_details = $("#expense_details"+id).val();
        var url = "{{ route('expense-receipt.update') }}";
        if(expense_receipt_id != ''){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    expense_service_id: expense_service_id,
                    expense_receipt_id: expense_receipt_id,
                    expense_category_id: expense_category_id,
                    expense_id: expense_id,
                    payee_id: payee_id,
                    expense_amount: expense_amount,
                    expense_details: expense_details
                },
                success: function (data) {
                    if(data.error){
                        toastr.error(data.error);
                    }else{
                        $('#editExpenseReceipt'+id).modal('hide');
                        toastr.success('ExpenseReceipt updated successfully.');
                        $('#updateExpenseDetails').html(data);

                        let updateServiceQty = $('#update_expense_service_qty').val();
                        let updateServiceAmount = $('#update_expense_service_amount').val();
                        $('#sub_total_expense_quantity').val(updateServiceQty);
                        $('#sub_total_expense_amount').val(updateServiceAmount);
                    }
                }
            });
        }
    }
    
    //To remove expense service data...
    function deleteExpenseServiceData(id) {
        event.preventDefault();

        var url = "{{ route('expense-receipt-delete') }}";
        if(id != ''){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    expense_service_id: id,
                },
                success: function (data) {
                    if(data.error){
                        toastr.error(data.error);
                    }else{
                        toastr.success('Expense service removed successfully.');
                        $('#updateExpenseDetails').html(data);

                        let updateServiceQty = $('#update_expense_service_qty').val();
                        let updateServiceAmount = $('#update_expense_service_amount').val();
                        $('#sub_total_expense_quantity').val(updateServiceQty);
                        $('#sub_total_expense_amount').val(updateServiceAmount);
                    }
                }
            });
        }
    }

    //To get expense with .
    function getExpenseDataWithCate(){
      var expense_category_id = $("#expense_category_id").val();
      var url = "{{ route('get-all-expense-with-category-id') }}";
        if (expense_category_id != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    expense_category_id: expense_category_id
                },
                success: function (data) {

                    //For Section...
                    $("#expense_id").empty();
                    $("#expense_id").append('<option value="" selected disabled>Select Expense</option>');
                    
                    $.each(data, function(key,value){
                    $("#expense_id").append('<option value="'+value.id+'">'+value.expense_name+'</option>');
                    });

                }

            });
        }  
    };

    //To get expense with .
    function getExpenseDataWithCateFU(id){
      var expense_category_id = $("#expense_category_id"+id).val();
      var url = "{{ route('get-all-expense-with-category-id') }}";
        if (expense_category_id != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    expense_category_id: expense_category_id
                },
                success: function (data) {

                    //For Section...
                    $("#expense_id"+id).empty();
                    $("#expense_id"+id).append('<option value="" selected disabled>Select Expense</option>');
                    
                    $.each(data, function(key,value){
                    $("#expense_id"+id).append('<option value="'+value.id+'">'+value.expense_name+'</option>');
                    });

                }

            });
        }  
    };
</script>
@endsection