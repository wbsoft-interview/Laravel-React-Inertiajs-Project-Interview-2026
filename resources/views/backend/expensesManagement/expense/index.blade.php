@extends('backend.master')
@section('title') Expense | Master Template @endsection
@section('expense') active @endsection
@section('expense.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Expense List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('expense.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allExpenseCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('expense-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addExpense">
                        <i class="fa fa-plus"></i>
                        <span class="">New Expense</span></a>
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
                        <th class="text-center" scope="col"><span>Details</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($expenseData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">

                        <td class="text-start"> 
                            <div class="row_title">
                                <b>Category: </b>{{ $item->expenseCategoryData->category_name }} <br>
                                <b>Name: </b>{{ $item->expense_name }}
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('expense-edit'))
                                <span><button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateExpense({{$item->id}})"> Edit </button></span>
                                

                                @if($item->status == true)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{route('expense-inactive', $item->id)}}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{route('expense-active', $item->id)}}">Active</a>
                                </span>
                                @endif

                                @endif
                                @if (Auth::user()->can('expense-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('expense-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($item->status == true)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                    </tr>

                    {{-- //Update ServiceExpense.. --}}
                    <div class="modal fade" id="updateExpense{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Expense</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('expense.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group custom-select2-form">
                                                    <label for="expense_category_id{{$item->id}}">Expense Category <span class=" text-danger">*</span>
                                                    </label>
                                                    <select name="expense_category_id" id="expense_category_id{{$item->id}}" class="form-select select2" required>
                                                        <option value="" selected disabled>Select Category</option>
                                                        @foreach($expenseCategoryData as $singleECD)
                                                        <option value="{{$singleECD->id}}"
                                                            {{$item->expense_category_id == $singleECD->id ? 'selected' : ''}}
                                                            >{{$singleECD->category_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            
                                                @error('expense_category_id')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="expense_name">Expense Name<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="expense_name" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->expense_name}}" placeholder="Name">
                                                </div>

                                                @error('expense_name')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cancel</button>
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

        @if(isset($allExpenseCount) && $allExpenseCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $expenseData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $expenseData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $expenseData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $expenseData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>


{{-- //Add new expense.. --}}
<div class="modal fade" id="addExpense" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Expense</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('expense.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="expense_category_id">Expense Category <span class=" text-danger">*</span>
                                </label>
                                <select name="expense_category_id" id="expense_category_id" class="form-select select2" required>
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
                            <div class="form-group">
                                <label for="expense_name">Expense Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="expense_name" required class="form-control form-control-solid"
                                    value="" placeholder="Name">
                            </div>

                            @error('expense_name')
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
    $("#expense_category_id").select2({
        dropdownParent: $('#addExpense')
    });

    //To show update modal...
    function updateExpense(id) {
        $("#updateExpense"+id).modal('show');

        $("#expense_category_id"+id).select2({
        dropdownParent: $('#updateExpense'+id)
        });
    }
</script>
@endsection