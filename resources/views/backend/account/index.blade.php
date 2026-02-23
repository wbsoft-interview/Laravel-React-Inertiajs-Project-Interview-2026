@extends('backend.master')
@section('title') Account | Master Template @endsection
@section('account') active @endsection
@section('account.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Account List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('account.index')}}"
                            class="text-primary py-2 px-3 active">All({{$allAccountCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('account-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0" data-bs-toggle="modal"
                        data-bs-target="#addAccount">
                        <i class="fa fa-plus"></i>
                        <span class="">New Account</span></a>
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
                        <th class="text-center" scope="col"><span>Details</span></th>
                        <th class="text-center" scope="col"><span>Account</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                        <th class="text-center" scope="col"><span>Date/Time</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($accountData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <b>#{{$key+1}}</b>
                        </td>
                        <td class="text-start">  
                            <div class="row_title">
                                <b>Holder: </b> {{ $item->account_holder_name }} <br>
                                <b>Account: </b> {{ $item->account_name }}
                            </div>
                            <div class="row-actions mt-2">

                                @if (Auth::user()->can('account-edit'))
                                <span><a class="text-primary fw-bolder" href="{{route('account-profile', $item->id)}}">Details</a></span>

                                <span> | <button class="text-info border-0 bg-transparent fw-bolder" value="{{ $item->id }}" onclick="updateAccount({{$item->id}})"> Edit </button></span>
                                

                                @if($item->status == true)
                                <span> | <a class="text-warning fw-bolder"
                                        href="{{route('account-inactive', $item->id)}}">Not Default</a></span>
                                @else
                                <span> | <a class="text-success fw-bolder"
                                        href="{{route('account-active', $item->id)}}">Default</a>
                                </span>
                                @endif

                                @endif
                                @if (Auth::user()->can('account-delete'))
                                <span> | <a class="text-danger fw-bolder row-delete"
                                        href="{{route('account-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>

                        <td class="text-start">
                            <b>Number: </b>{{ $item->account_number }} <br>
                            <b>Balance: </b>{{ $item->account_balance > 0 ? $item->account_balance.'tk': '0tk' }}
                        </td>
                        
                        <td>
                            @if($item->status == true)
                            <span class="badge bg-success">Default</span>
                            @else
                            <span class="badge bg-danger">Not Default</span>
                            @endif
                        </td>

                        <td>
                            <span class="text-normal">{{Carbon\Carbon::parse($item->created_at->toDateString())->format('d-m-Y')}}</span>
                            <br> <span
                                class="text-normal">{{Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('h:i A')}}</span>
                        </td>

                    </tr>

                    {{-- //Update ServiceCategory.. --}}
                    <div class="modal fade" id="updateAccount{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Account</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('account.update', $item->id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="account_name">Account Name<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="account_name" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->account_name}}" placeholder="Name">
                                                </div>

                                                @error('account_name')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="account_holder_name">Account Holder Name<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="account_holder_name" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->account_holder_name}}" placeholder="Holder Name">
                                                </div>

                                                @error('account_holder_name')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="account_number">Account Number<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" name="account_number" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->account_number}}" placeholder="Account Number">
                                                </div>

                                                @error('account_number')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="account_balance">Account Balance<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" name="account_balance" required
                                                        class="form-control form-control-solid"
                                                        value="{{$item->account_balance}}" placeholder="Account Balance">
                                                </div>

                                                @error('account_balance')
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

        @if(isset($allAccountCount) && $allAccountCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $accountData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $accountData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $accountData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $accountData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>


{{-- //Add new account.. --}}
<div class="modal fade" id="addAccount" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">New Account</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('account.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="account_name">Account Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="account_name" required class="form-control form-control-solid"
                                    value="{{old('account_name')}}" placeholder="Name">
                            </div>

                            @error('account_name')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="account_holder_name">Account Holder Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="account_holder_name" required class="form-control form-control-solid"
                                    value="{{old('account_holder_name')}}" placeholder="Holder Name">
                            </div>
                        
                            @error('account_holder_name')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="account_number">Account Number<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="account_number" required class="form-control form-control-solid"
                                    value="{{old('account_number')}}" placeholder="Account Number">
                            </div>
                        
                            @error('account_number')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="account_balance">Account Balance<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="account_balance" required class="form-control form-control-solid"
                                    value="0" placeholder="Account Balance">
                            </div>
                        
                            @error('account_balance')
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
    //To show update modal...
    function updateAccount(id) {
        $("#updateAccount"+id).modal('show');
    }
</script>
@endsection