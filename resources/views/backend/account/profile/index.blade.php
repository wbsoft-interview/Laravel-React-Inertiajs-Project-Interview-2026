@extends('backend.master')
@section('title') Account Profile | Master Template @endsection
@section('account-profile') active @endsection
@section('styles')
<style>
    .setting-custom-select2-form .select2-container--default .select2-selection--single {
        padding-top: 5px;
        padding-bottom: 5px;
    }

</style>
@endsection

@section('main_content_section')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class=" d-flex align-items-center justify-content-between my-3">
        <div class="align-items-center">
            <h3 class="mb-0 pb-0">Account Profile<span class="divider"></span></h3>
        </div>
    </div>

    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <section class="app-user-view-account">
                <div class="row">

                    <!-- User Content -->
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <div class=" d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0 pb-0">Account Details</h4>
                                    <div class="d-sm-block">
                                        {{-- @if(Auth::user()->can('account-create'))
                                        <a href="javascript:void(0)" class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0"
                                            data-bs-toggle="modal" data-bs-target="#updateAccount">
                                            <i class="fa fa-edit"></i>
                                            <span class="">Edit Account</span></a>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-container table-responsive">
                                    <table id="" class="table table-bordered">
                                        <thead class="text-uppercase">
                                            <tr class="me-3">
                                                <th class="text-center" scope="col"><span>Account</span></th>
                                                <th class="text-center" scope="col"><span>Holder Name</span></th>
                                                <th class="text-center" scope="col"><span>Account Number</span></th>
                                                <th class="text-center" scope="col"><span>Main Balance</span></th>
                                                <th class="text-center" scope="col"><span>Opening Date</span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">

                                            <tr class="">
                                                <td>
                                                    <b>{{ $singleAccountData->account_name}}</b>
                                                </td>

                                                <td>
                                                    {{ Str::title($singleAccountData->account_holder_name) }}
                                                </td>

                                                <td>
                                                    {{ $singleAccountData->account_number}}
                                                </td>

                                                <td>
                                                    {{ $singleAccountData->account_balance}}Tk
                                                </td>

                                                <td class="text-center">
                                                    <b></b><span
                                                        class="text-normal">{{Carbon\Carbon::parse($singleAccountData->created_at->toDateString())->format('d-m-Y')}}</span>
                                                </td>

                                            </tr>


                                        </tbody>
                                    </table>
                                </div>

                                @if(isset($allAccountTransferCount) && $allAccountTransferCount > 10)
                                <div class="clearfix d-flex">
                                    <div class="float-left">
                                        <p class="text-muted">
                                            {{ __('Showing') }}
                                            <span class="font-weight-bold">{{ $accountTransferData->firstItem() }}</span>
                                            {{ __('to') }}
                                            <span class="font-weight-bold">{{ $accountTransferData->lastItem() }}</span>
                                            {{ __('of') }}
                                            <span class="font-weight-bold">{{ $accountTransferData->total() }}</span>
                                            {{ __('results') }}
                                        </p>
                                    </div>

                                    <div class="float-right custom-table-pagination">
                                        {!! $accountTransferData->links('pagination::bootstrap-4') !!}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- //To update... --}}
                        <div class="modal fade" id="updateAccount" tabindex="-1" data-bs-backdrop='static'>
                            <div class="modal-dialog modal-dialog-centered max-width-900px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="oneInputModalLabel">Account Update</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('account.update', $singleAccountData->id)}}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body p-0">
                                            <div class="row px-4 my-4">
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="account_name">Account Name<span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="account_name" required class="form-control form-control-solid"
                                                            value="{{$singleAccountData->account_name}}" placeholder="Name">
                                                    </div>

                                                    @error('account_name')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="account_holder_name">Account Holder Name<span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="account_holder_name" required
                                                            class="form-control form-control-solid"
                                                            value="{{$singleAccountData->account_holder_name}}" placeholder="Holder Name">
                                                    </div>

                                                    @error('account_holder_name')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="account_number">Account Number<span class="text-danger">*</span>
                                                        </label>
                                                        <input type="number" name="account_number" required
                                                            class="form-control form-control-solid"
                                                            value="{{$singleAccountData->account_number}}" placeholder="Account Number">
                                                    </div>

                                                    @error('account_number')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="account_balance">Account Balance<span class="text-danger">*</span>
                                                        </label>
                                                        <input type="number" name="account_balance" required
                                                            class="form-control form-control-solid"
                                                            value="{{$singleAccountData->account_balance}}" placeholder="Account Balance">
                                                    </div>

                                                    @error('account_balance')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ User Pills -->

                    <!-- User Content -->
                    <div class="col-md-12">
                        <h4 class="my-3 pb-0">Transaction Details</h4>
                        <div class="card shadow">
                            <div class="card-header">
                                <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0"><a href="{{route('account-profile', $singleAccountData->id)}}"
                                                class="text-primary py-2 px-3 active">All({{$allAccountTransferCount}})</a>
                                        </p>
                                    </div>
                                    <div class="d-sm-block">
                                        @if(Auth::user()->can('account-create'))
                                        <a href="javascript:void(0)" class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                                            {{-- data-bs-toggle="modal" data-bs-target="#addAccountCreditDebit"> --}}
                                            <i class="fa fa-list"></i>
                                            <span class="">List</span></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-container table-responsive">
                                    <table id="" class="table table-bordered">
                                        <thead class="text-uppercase">
                                            <tr class="me-3">
                                                <th class="text-center" scope="col"><span>Transaction Date</span></th>
                                                <th class="text-center" scope="col"><span>Purpuse</span></th>
                                                <th class="text-center" scope="col"><span>Credit</span></th>
                                                <th class="text-center" scope="col"><span>Debit</span></th>
                                                <th class="text-center" scope="col"><span>Balance</span></th>
                                                <th class="text-center" scope="col"><span>By</span></th>
                                                <th class="text-center" scope="col"><span>Entry Date</span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">

                                            @foreach($accountTransferData as $key=>$item)
                                            @if(isset($item) && $item != null)
                                            <tr class="">
                                                <td>
                                                   <span class="text-normal fw-bolder">{{Carbon\Carbon::parse($item->transfer_date)->format('d-m-Y')}}</span>
                                                </td>

                                                <td class="text-start">

                                                    <div class="row_title">
                                                        {{ $item->transfer_purpuse }}
                                                        @if(isset($item->transfer_from) && $item->transfer_from != null)
                                                        <a href="javascript:void(0)" class="text-primary fw-bolder" onclick="detailsModule({{$item->id}})">{{Str::title($item->transferFromData->name)}}</a>
                                                        @endif
                                                    </div>
                                                    <div class="row-actions mt-2">

                                                        {{-- @if (Auth::user()->can('account-edit'))
                                                        <span><button class="text-success border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                                                onclick="updateAccountCreditDebit({{$item->id}})"> Edit </button></span>
                                                        @endif --}}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->transfer_type == 'Credit')
                                                    {{ $item->transfer_amount }}Tk
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($item->transfer_type == 'Debit')
                                                    {{ $item->transfer_amount }}Tk
                                                    @endif
                                                </td>

                                                <td>
                                                    {{ $item->current_amount }}Tk
                                                </td>

                                                <td>
                                                    {{ Str::title($item->transferByData->name) }}
                                                </td>

                                                <td>
                                                    <span class="text-normal">{{Carbon\Carbon::parse($item->created_at->toDateString())->format('d-m-Y')}}</span>
                                                    <br> <span
                                                        class="text-normal">{{Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('h:i A')}}</span>
                                                </td>

                                            </tr>

                                            {{-- //Add new account.. --}}
                                            <div class="modal fade" id="updateAccountCreditDebit{{$item->id}}" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true"
                                                data-bs-backdrop='static'>
                                                <div class="modal-dialog modal-dialog-centered max-width-900px">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Transfer Amount</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('update-transfer-amount')}}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="account_id" value="{{$singleAccountData->id}}">
                                                            <input type="hidden" name="account_transfer_id" value="{{$item->id}}">
                                                            <div class="modal-body p-0">
                                                                <div class="row px-4 my-4">
                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-group custom-select2-form">
                                                                            <label for="transfer_type">Type <span class=" text-danger">*</span>
                                                                            </label>
                                                                            <select name="transfer_type" id="transfer_type{{$item->id}}" class="form-select select2" required>
                                                                                <option value="" selected disabled>Select Type</option>
                                                                                <option value="Credit" {{$item->transfer_type == 'Credit' ? 'selected' : ''}}>Credit</option>
                                                                                <option value="Debit" {{$item->transfer_type == 'Debit' ? 'selected' : ''}}>Debit</option>
                                                                            </select>
                                                                        </div>

                                                                        @error('transfer_type')
                                                                        <span class=text-danger>{{$message}}</span>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-group custom-select2-form">
                                                                            <label for="account_category_id{{$item->id}}">Account Category<span class=" text-danger">*</span> </label>

                                                                            <select name="account_category_id" id="account_category_id{{$item->id}}"  class="form-select select2" required>
                                                                                <option value="" selected disabled>Select Category</option>
                                                                                @foreach (App\Models\AccountCategory::all() as $category)
                                                                                <option value="{{  $category->id }}" {{ $item->account_category_id == $category->id ? 'selected' : '' }}>{{  $category->category_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        @error('current_balance')
                                                                        <span class=text-danger>{{$message}}</span>
                                                                        @enderror
                                                                    </div>


                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-group">
                                                                            <label for="transfer_date">Transfer Date<span class=" text-danger">*</span> </label>

                                                                            <input type="text" class=" form-control flatpickr-basic custom-date-picker"
                                                                                name="transfer_date" id="transfer_date{{$item->id}}" placeholder="DD-MM-YYYY"
                                                                                value="{{Carbon\Carbon::parse($item->transfer_date)->format('d-m-Y')}}" required>
                                                                        </div>

                                                                        @error('current_balance')
                                                                        <span class=text-danger>{{$message}}</span>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-group">
                                                                            <label for="transfer_amount">Transfer Amount<span class="text-danger">*</span>
                                                                            </label>
                                                                            <input type="number" name="transfer_amount" required
                                                                                class="form-control form-control-solid" value="{{$item->transfer_amount}}"
                                                                                placeholder="Transfer Amount">
                                                                        </div>

                                                                        @error('transfer_amount')
                                                                        <span class=text-danger>{{$message}}</span>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="col-md-12 mb-3">
                                                                        <div class="form-group">
                                                                            <label for="transfer_purpuse">Purpuse<span class="text-danger"></span>
                                                                            </label>
                                                                            <textarea name="transfer_purpuse" rows="3" cols="3"
                                                                                class="form-control form-control-solid" value=""
                                                                                placeholder="Purpuse">{{$item->transfer_purpuse}}</textarea>
                                                                        </div>

                                                                        @error('transfer_purpuse')
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

                                            {{-- //Details Module.. --}}
                                            @if(isset($item->transfer_from) && $item->transfer_from != null)
                                            <div class="modal fade" id="detailsModule{{$item->id}}" tabindex="-1" aria-labelledby="oneInputModalLabel"
                                                aria-hidden="true" data-bs-backdrop='static'>
                                                <div class="modal-dialog modal-dialog-centered max-width-900px">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="oneInputModalLabel">Details Module</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-0">
                                                            <div class="row px-4 my-4">
                                                                <div class="col-md-6 mb-3">
                                                                    <p class="mt-4 fw-bolder mb-0">Student Details:</p>
                                                                    <hr class="mt-1 mb-2 py-0">

                                                                    <p class="mb-1"><b>Name: </b>{{$item->transferFromData->name}}</p>
                                                                    <p class="mb-1"><b>Phone: </b>{{$item->transferFromData->phone_no}}</p>
                                                                    <p class="mb-1"><b>Email: </b>{{$item->transferFromData->email}}</p>
                                                                    <p class="mb-1"><b>NID No: </b>{{$item->transferFromData->nid_no}}</p>
                                                                    <p class="mb-1"><b>Gender: </b>{{Str::title($item->transferFromData->gender)}}</p>
                                                                    <p class="mb-1"><b>Blood Group: </b>{{$item->transferFromData->blood_group}}</p>
                                                                    <p class="mb-1"><b>School/Collage: </b>{{$item->transferFromData->school_collage_name}}</p>
                                                                    <p class="mb-1"><b>Job Title: </b>{{$item->transferFromData->job_title}}</p>
                                                                    <p class="mb-1"><b>Date Of Birth: </b>{{Carbon\Carbon::parse($item->transferFromData->created_at->toDateString())->format('d-m-Y')}}</p>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <p class="mt-4 fw-bolder mb-0">Guardians:</p>
                                                                    <hr class="mt-1 mb-2 py-0">

                                                                    <p class="mb-1"><b>Father Name: </b>{{$item->transferFromData->father_name}}</p>
                                                                    <p class="mb-1"><b>Father Phone: </b>{{$item->transferFromData->father_phone_no}}</p>
                                                                    <p class="mb-1"><b>Mother Name: </b>{{$item->transferFromData->mother_name}}</p>
                                                                    <p class="mb-1"><b>Mother Phone: </b>{{$item->transferFromData->mother_phone_no}}</p>

                                                                    @if(isset($item->transfer_from) && $item->transferFromData->local_guardian_name != null)
                                                                    <p class="mb-1"><b>Local Guardian Name: </b>{{$item->transferFromData->local_guardian_name}}</p>
                                                                    <p class="mb-1"><b>Local Guardian Phone: </b>{{$item->transferFromData->local_guardian_phone_no}}</p>
                                                                    @endif

                                                                    <p class="mt-4 fw-bolder mb-0">Profile Picture:</p>
                                                                    <hr class="mt-1 mb-2 py-0">
                                                                    @if(isset($item->transferFromData->photo) && $item->transferFromData->photo != null)
                                                                        <img src="{{ asset($item->transferFromData->photo) }}" height="80" width="80">
                                                                    @else
                                                                        <img src="{{ asset('backend/template-assets/') }}/images/img_preview.png" height="80"
                                                                            width="80">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @endif
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>

                                @if(isset($allAccountTransferCount) && $allAccountTransferCount > 10)
                                <div class="clearfix d-flex">
                                    <div class="float-left">
                                        <p class="text-muted">
                                            {{ __('Showing') }}
                                            <span class="font-weight-bold">{{ $accountTransferData->firstItem() }}</span>
                                            {{ __('to') }}
                                            <span class="font-weight-bold">{{ $accountTransferData->lastItem() }}</span>
                                            {{ __('of') }}
                                            <span class="font-weight-bold">{{ $accountTransferData->total() }}</span>
                                            {{ __('results') }}
                                        </p>
                                    </div>

                                    <div class="float-right custom-table-pagination">
                                        {!! $accountTransferData->links('pagination::bootstrap-4') !!}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--/ User Pills -->

                </div>
                <!--/ User Content -->
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->

{{-- //Add new account.. --}}
<div class="modal fade" id="addAccountCreditDebit" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true"
    data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="oneInputModalLabel">Add Transfer Amount</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('add-transfer-amount')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="account_id" value="{{$singleAccountData->id}}">
                <div class="modal-body p-0">
                    <div class="row px-4 my-4">
                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="transfer_type">Type <span class=" text-danger">*</span>
                                </label>
                                <select name="transfer_type" id="transfer_type" class="form-select select2" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Debit">Debit</option>
                                </select>
                            </div>

                            @error('transfer_type')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="account_category_id">Account Category<span class=" text-danger">*</span> </label>

                                <select name="account_category_id" id="account_category_id"  class="form-select select2" required>
                                    <option value="">Select Category</option>
                                    @foreach (App\Models\AccountCategory::all() as $category)
                                    <option value="{{  $category->id }}">{{  $category->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @error('current_balance')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="transfer_amount">Transfer Amount<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="transfer_amount" required
                                    class="form-control form-control-solid" value="{{old('transfer_amount')}}"
                                    placeholder="Transfer Amount">
                            </div>

                            @error('transfer_amount')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="transfer_date">Transfer Date<span class=" text-danger">*</span> </label>

                                <input type="text" class=" form-control flatpickr-basic custom-date-picker" name="transfer_date" id="transfer_date"
                                    placeholder="DD-MM-YYYY" value="" required>
                            </div>

                            @error('current_balance')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="transfer_purpuse">Purpuse<span class="text-danger"></span>
                                </label>
                                <textarea name="transfer_purpuse" rows="3" cols="3"
                                    class="form-control form-control-solid" value="{{old('transfer_purpuse')}}"
                                    placeholder="Purpuse"></textarea>
                            </div>

                            @error('transfer_purpuse')
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
    $(document).ready(function(){
        $("#transfer_date").flatpickr({
            allowInput: true,
            dateFormat: "d-m-Y",
        });
    });

    $("#transfer_type").select2({
        dropdownParent: $('#addAccountCreditDebit')
    });
    $("#account_category_id").select2({
        dropdownParent: $('#addAccountCreditDebit')
    });

    //To show update modal...
    function detailsModule(id) {
        $("#detailsModule"+id).modal('show');
    }

    //To show update modal...
    function updateAccount(id) {
        $("#updateAccount"+id).modal('show');
    }

    //To show update modal...
    function updateAccountCreditDebit(id) {
        $("#updateAccountCreditDebit"+id).modal('show');

        $("#transfer_type"+id).select2({
        dropdownParent: $('#updateAccountCreditDebit'+id)
        });
        $("#account_category_id"+id).select2({
        dropdownParent: $('#updateAccountCreditDebit'+id)
        });

        $("#transfer_date"+id).flatpickr({
        allowInput: true,
        dateFormat: "d-m-Y",
        });
    }
</script>
@endsection
