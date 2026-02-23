@extends('backend.master')
@section('title') Income | Master Template @endsection
@section('income') active @endsection
@section('income.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Income List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('income.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allIncomeCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('income-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addIncome">
                        <i class="fa fa-plus"></i>
                        <span class="">New Income</span></a>
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

                    @foreach($incomeData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">

                        <td class="text-center">
                            <div class="row_title">
                                <b>Category: </b>{{ $item->incomeCategoryData->category_name }} <br>
                                <b>Name: </b>{{ $item->income_name }}
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('income-edit'))
                                <span><button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateIncome({{$item->id}})"> Edit </button></span>


                                @if($item->status == true)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{route('income-inactive', $item->id)}}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{route('income-active', $item->id)}}">Active</a>
                                </span>
                                @endif

                                @endif
                                @if (Auth::user()->can('income-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('income-delete', $item->id)}}">Delete</a>
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

                    {{-- //Update ServiceIncome.. --}}
                    <div class="modal fade" id="updateIncome{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Income</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('income.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group custom-select2-form">
                                                    <label for="income_category_id{{$item->id}}">Income Category <span class=" text-danger">*</span>
                                                    </label>
                                                    <select name="income_category_id" id="income_category_id{{$item->id}}" class="form-select select2" required>
                                                        <option value="" selected disabled>Select Category</option>
                                                        @foreach($incomeCategoryData as $singleECD)
                                                        <option value="{{$singleECD->id}}"
                                                            {{$item->income_category_id == $singleECD->id ? 'selected' : ''}}
                                                            >{{$singleECD->category_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                @error('income_category_id')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="income_name">Income Name<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="income_name" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->income_name}}" placeholder="Name">
                                                </div>

                                                @error('income_name')
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

        @if(isset($allIncomeCount) && $allIncomeCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $incomeData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $incomeData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $incomeData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $incomeData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>


{{-- //Add new Income.. --}}
<div class="modal fade" id="addIncome" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Income</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('income.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="income_category_id">Income Category <span class=" text-danger">*</span>
                                </label>
                                <select name="income_category_id" id="income_category_id" class="form-select select2" required>
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach($incomeCategoryData as $singleECD)
                                    <option value="{{$singleECD->id}}">{{$singleECD->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @error('income_category_id')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="income_name">Income Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="income_name" required class="form-control form-control-solid"
                                    value="" placeholder="Name">
                            </div>

                            @error('income_name')
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
    $("#income_category_id").select2({
        dropdownParent: $('#addIncome')
    });

    //To show update modal...
    function updateIncome(id) {
        $("#updateIncome"+id).modal('show');

        $("#income_category_id"+id).select2({
        dropdownParent: $('#updateIncome'+id)
        });
    }
</script>
@endsection
