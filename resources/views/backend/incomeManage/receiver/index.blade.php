@extends('backend.master')
@section('title') Receiver | Master Template @endsection
@section('receiver') active @endsection
@section('receiver.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Receiver List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('receiver.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allReceiverCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('receiver-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addReceiver">
                        <i class="fa fa-plus"></i>
                        <span class="">New Receiver</span></a>
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
                        <th class="text-center" scope="col"><span>Receiver Name</span></th>
                        <th class="text-center" scope="col"><span>Receiver Phone</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($receiverData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            {{$key+1}}
                        </td>

                        <td>
                            <div class="row_title">
                                {{ $item->receiver_name }}
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('receiver-edit'))
                                <span><button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateReceiver({{$item->id}})"> Edit </button></span>


                                @if($item->status == true)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{route('receiver-inactive', $item->id)}}">Inactive</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{route('receiver-active', $item->id)}}">Active</a>
                                </span>
                                @endif

                                @endif
                                @if (Auth::user()->can('receiver-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('receiver-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            {{$item->receiver_phone}}
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
                    <div class="modal fade" id="updateReceiver{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Receiver</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('receiver.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data" onsubmit="return checkValidateFU({{$item->id}})">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="receiver_name">Receiver Name<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="receiver_name" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->receiver_name}}" placeholder="Name">
                                                </div>

                                                @error('receiver_name')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="receiver_phone">Receiver Phone<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="receiver_phone" id="receiver_phone{{$item->id}}" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->receiver_phone}}" placeholder="Phone">
                                                </div>

                                                @error('receiver_phone')
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

        @if(isset($allReceiverCount) && $allReceiverCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $receiverData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $receiverData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $receiverData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $receiverData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>

<input type="hidden" id="selectedId" value="">
{{-- //Add new receiver.. --}}
<div class="modal fade" id="addReceiver" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Receiver</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('receiver.store')}}" method="post" enctype="multipart/form-data" id="receiver-form">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="receiver_name">Receiver Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="receiver_name" required class="form-control form-control-solid"
                                    value="" placeholder="Name">
                            </div>

                            @error('receiver_name')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="receiver_phone">Receiver Phone<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="receiver_phone" id="receiver_phone" required class="form-control form-control-solid"
                                    value="" placeholder="Phone">
                            </div>

                            @error('receiver_phone')
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
        document.getElementById("receiver-form").addEventListener("submit", function(event) {
            checkValidate(event);
        });
    });

    function checkValidate(event) {
        var mobileNumber1 = document.getElementById("receiver_phone").value;
        if (mobileNumber1 != '' && mobileNumber1.length != 11) {
            event.preventDefault();
            toastr.error("Mobile number must be 11 digits.");
            return false;
        }

        return true;
    }

    //To show update modal...
    function updateReceiver(id) {
        $("#updateReceiver"+id).modal('show');
        $("#selectedId").val(id);
    }

    function checkValidateFU(id) {
        var mobileNumber1 = $("#receiver_phone"+id).val();
        if (mobileNumber1 != '' && mobileNumber1.length != 11) {
            event.preventDefault();
            toastr.error("Mobile number must be 11 digit.");
            return false;
        }

        return true;
    }
</script>
@endsection
