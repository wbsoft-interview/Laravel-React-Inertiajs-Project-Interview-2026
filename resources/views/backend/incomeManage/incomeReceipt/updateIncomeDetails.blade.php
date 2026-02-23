<input type="hidden" id="update_income_service_qty" value="{{$totalIncomeServiceQty}}">
<input type="hidden" id="update_income_service_amount" value="{{$totalIncomeServiceAmount}}">
<thead>
    <tr>
        <th class="text-center" scope="col"><span>SN</span></th>
        <th class="text-center" scope="col"><span>Income</span></th>
        <th class="text-center" scope="col"><span>Details</span></th>
        <th class="text-center" scope="col"><span>Amount</span></th>
    </tr>
</thead>
<tbody>
    @foreach($unpaidIncomeServiceData as $key=>$item)
    @if(isset($item) && $item != null)
    <tr class="text-center">
        <td>
            <b>{{$key+1}}</b>
        </td>
        <td class="text-start">
            <div class="row_title">
                <b>Category: </b>{{$item->incomeCategoryData->category_name}} <br>
                <b>Income: </b>{{$item->incomeData->income_name}}
            </div>
            <div class="row-actions mt-2">
                @if (Auth::user()->can('income-receipt-edit'))
                <span><a class="text-primary fw-bolder"
                        href="javascript(void.0)" onclick="editIncomeReceipt({{$item->id}})">Edit</a>
                </span>
                @endif

                @if (Auth::user()->can('income-receipt-delete'))
                <span> | <a class="text-danger fw-bolder"
                        href="javascript(void.0)" onclick="deleteIncomeServiceData({{$item->id}})">Delete</a>
                </span>
                @endif
            </div>
        </td>

        <td class="text-start w-50">
            <b>Receiver: </b>{{$item->receiverData->receiver_name}}
            <br>
            <b>Details: </b>{{$item->income_details}}
        </td>

        <td class="text-center">
            <span class="fw-bolder text-success">{{$item->income_amount}}</span>
        </td>
    </tr>

    <div class="modal fade" id="editIncomeReceipt{{$item->id}}" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true"
        data-bs-backdrop='static'>
        <div class="modal-dialog modal-dialog-centered max-width-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Income Receipt</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="income_receipt_id{{$item->id}}" value="{{$item->income_receipt_id}}">
                    <div class="modal-body p-0">
                        <div class="row px-4 my-4">

                            <div class="col-md-6 mb-3">
                                <div class="form-group custom-select2-form">
                                    <label for="income_category_id">Income Category <span class=" text-danger">*</span>
                                    </label>
                                    <select name="income_category_id" id="income_category_id{{$item->id}}" class="form-select select2"
                                        onchange="getIncomeDataWithCateFU({{$item->id}})" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach($incomeCategoryData as $singleECD)
                                        <option value="{{$singleECD->id}}"
                                            {{$singleECD->id == $item->income_category_id ? 'selected' : ''}}
                                            >{{$singleECD->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('income_category_id')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            @php
                             //To get all the income data with category...
                             $getIncomeData = App\Models\Income::getIncomeDataWithCategory($item->income_category_id);
                            //  dd($getIncomeData);
                            @endphp

                            <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="income_id">Income <span class=" text-danger">*</span>
                                </label>
                                <select name="income_id" id="income_id{{$item->id}}" class="form-select select2" required>
                                    <option value="" selected disabled>Select Income</option>

                                    @foreach($getIncomeData as $singleED)
                                    <option value="{{$singleED->id}}" {{$singleED->id == $item->income_id ? 'selected' : ''}}>
                                        {{$singleED->income_name}}</option>
                                    @endforeach

                                </select>
                            </div>

                            @error('income_id')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="receiver_id">Receiver <span class=" text-danger">*</span>
                                </label>
                                <select name="receiver_id" id="receiver_id{{$item->id}}" class="form-select select2" required>
                                    <option value="" selected disabled>Select Receiver</option>
                                    @foreach($receiverData as $singlePD)
                                    <option value="{{$singlePD->id}}" {{$singlePD->id == $item->receiver_id ? 'selected' : ''}}>
                                        {{$singlePD->receiver_name}} / {{$singlePD->receiver_phone}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @error('receiver_id')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="income_amount">Income Amount<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="income_amount" id="income_amount{{$item->id}}" required class="form-control form-control-solid"
                                    value="{{$item->income_amount}}" step="0.01" placeholder="Income Amount">
                            </div>

                            @error('income_amount')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="income_details">Income Details<span class="text-danger">*</span>
                                </label>
                                <textarea rows="3" cols="3" name="income_details" id="income_details{{$item->id}}" required class="form-control form-control-solid"
                                    value="" placeholder="Income Details">{{$item->income_details}}</textarea>
                            </div>

                            @error('income_details')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" onclick="updateIncomeReceipt({{$item->id}})">Update</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @endif
    @endforeach
</tbody>
