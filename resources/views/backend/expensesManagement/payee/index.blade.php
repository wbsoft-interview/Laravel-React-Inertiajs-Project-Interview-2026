@extends('backend.master')
@section('title') Payee | Master Template @endsection
@section('payee') active @endsection
@section('payee.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Payee List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('payee.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allPayeeCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('payee-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addPayee">
                        <i class="fa fa-plus"></i>
                        <span class="">New Payee</span></a>
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
                        <th class="text-center" scope="col"><span>Serial</span></th>
                        <th class="text-center" scope="col"><span>Payee Name</span></th>
                        <th class="text-center" scope="col"><span>Payee Phone</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($payeeData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">  
                        <td>
                            {{$key+1}}
                        </td>

                        <td>
                            <div class="row_title">
                                {{ $item->payee_name }}
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('payee-edit'))
                                <span><button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updatePayee({{$item->id}})"> Edit </button></span>
                                

                                @if($item->status == true)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{route('payee-inactive', $item->id)}}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{route('payee-active', $item->id)}}">Active</a>
                                </span>
                                @endif

                                @endif
                                @if (Auth::user()->can('payee-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('payee-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            {{$item->payee_phone}}
                        </td>

                        <td>
                            @if($item->status == true)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                    </tr>

                    {{-- //Update ServiceCategory.. --}}
                    <div class="modal fade" id="updatePayee{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Payee</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('payee.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data" onsubmit="return checkValidateFU({{$item->id}})">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="payee_name">Payee Name<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="payee_name" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->payee_name}}" placeholder="Name">
                                                </div>

                                                @error('payee_name')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="payee_phone">Payee Phone<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="payee_phone" id="payee_phone{{$item->id}}" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->payee_phone}}" placeholder="Phone">
                                                </div>

                                                @error('payee_phone')
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

        @if(isset($allPayeeCount) && $allPayeeCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $payeeData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $payeeData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $payeeData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $payeeData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>

<input type="hidden" id="selectedId" value="">
{{-- //Add new payee.. --}}
<div class="modal fade" id="addPayee" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Payee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('payee.store')}}" method="post" enctype="multipart/form-data" id="payee-form">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="payee_name">Payee Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="payee_name" required class="form-control form-control-solid"
                                    value="" placeholder="Name">
                            </div>

                            @error('payee_name')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="payee_phone">Payee Phone<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="payee_phone" id="payee_phone" required class="form-control form-control-solid"
                                    value="" placeholder="Phone">
                            </div>
                        
                            @error('payee_phone')
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
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("payee-form").addEventListener("submit", function(event) {
            checkValidate(event);
        });
    });

    function checkValidate(event) {
        var mobileNumber1 = document.getElementById("payee_phone").value;
        if (mobileNumber1 != '' && mobileNumber1.length != 11) {
            event.preventDefault();
            toastr.error("Mobile number must be 11 digits.");
            return false;
        }

        return true;
    }

    //To show update modal...
    function updatePayee(id) {
        $("#updatePayee"+id).modal('show');
        $("#selectedId").val(id);
    }

    function checkValidateFU(id) {
        var mobileNumber1 = $("#payee_phone"+id).val();
        if (mobileNumber1 != '' && mobileNumber1.length != 11) { 
            event.preventDefault();
            toastr.error("Mobile number must be 11 digit."); 
            return false;
        }

        return true;
    }
</script>
@endsection