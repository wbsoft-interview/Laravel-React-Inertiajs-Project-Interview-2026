<input type="hidden" id="update_expense_service_qty" value="{{$totalExpenseServiceQty}}">
<input type="hidden" id="update_expense_service_amount" value="{{$totalExpenseServiceAmount}}">
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
                <span><a class="text-primary fw-bolder"
                        href="javascript(void.0)" onclick="editExpenseReceipt({{$item->id}})">Edit</a>
                </span>
                @endif
                
                @if (Auth::user()->can('expense-receipt-delete'))
                <span> | <a class="text-danger fw-bolder"
                        href="javascript(void.0)" onclick="deleteExpenseServiceData({{$item->id}})">Delete</a>
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
            <span class="fw-bolder text-success">{{$item->expense_amount}}</span>
        </td>
    </tr>

    <div class="modal fade" id="editExpenseReceipt{{$item->id}}" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true"
        data-bs-backdrop='static'>
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
                                    <label for="expense_category_id">Expense Category <span class=" text-danger">*</span>
                                    </label>
                                    <select name="expense_category_id" id="expense_category_id{{$item->id}}" class="form-select select2"
                                        onchange="getExpenseDataWithCateFU({{$item->id}})" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach($expenseCategoryData as $singleECD)
                                        <option value="{{$singleECD->id}}"
                                            {{$singleECD->id == $item->expense_category_id ? 'selected' : ''}}
                                            >{{$singleECD->category_name}}</option>
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
                            //  dd($getExpenseData);
                            @endphp

                            <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="expense_id">Expense <span class=" text-danger">*</span>
                                </label>
                                <select name="expense_id" id="expense_id{{$item->id}}" class="form-select select2" required>
                                    <option value="" selected disabled>Select Expense</option>

                                    @foreach($getExpenseData as $singleED)
                                    <option value="{{$singleED->id}}" {{$singleED->id == $item->expense_id ? 'selected' : ''}}>
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
                                <input type="number" name="expense_amount" id="expense_amount{{$item->id}}" required class="form-control form-control-solid"
                                    value="{{$item->expense_amount}}" step="0.01" placeholder="Expense Amount">
                            </div>

                            @error('expense_amount')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="expense_details">Expense Details<span class="text-danger">*</span>
                                </label>
                                <textarea rows="3" cols="3" name="expense_details" id="expense_details{{$item->id}}" required class="form-control form-control-solid"
                                    value="" placeholder="Expense Details">{{$item->expense_details}}</textarea>
                            </div>

                            @error('expense_details')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
    
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" onclick="updateExpenseReceipt({{$item->id}})">Update</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @endif
    @endforeach
</tbody>