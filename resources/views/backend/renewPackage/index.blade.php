@extends('backend.master')

@section('styles')
<style>
    .custom-card-header-title {
        margin-top: 8px;
        margin-bottom: 0px;
    }

</style>
@endsection

@section('main_content_section')
<div class="card mt-3 shadow">
    <div class="row px-3 py-3">

        <div class="col-md-6 mb-4">
            <div class="h-100">
                <div class="card card-stats h-100">
                    <div class="card-header">
                        <div class="icon icon-warning d-flex">
                            <span class="material-symbols-outlined">campaign</span>
                            <p class="category custom-card-header-title">
                                <strong>গুরুত্বপূর্ণ নোটিশ</strong>
                            </p>
                        </div>
                    </div>
                    <div class="card-content px-3 py-2">
                        <p class="category mb-0">
                            আপনার বর্তমান প্যাকেজটির মেয়াদ শেষ হয়ে গেছে।
                            সফটওয়্যারের সকল ফিচার ব্যবহার চালু রাখতে অনুগ্রহ করে
                            নতুন করে প্যাকেজ নবায়ন (Renew) করুন।
                            প্যাকেজ নবায়ন না করা পর্যন্ত সিস্টেমের অন্যান্য মেনু ও অপশনগুলো
                            সাময়িকভাবে সীমিত থাকবে।
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="h-100">
                <div class="card card-stats h-100 border-warning">
                    <div class="card-header">
                        <div class="icon icon-warning d-flex align-items-center">
                            <span class="material-symbols-outlined">payments</span>
                            <p class="category custom-card-header-title ms-2">
                                <strong>আপনার বর্তমান প্যাকেজ</strong>
                            </p>
                        </div>
                    </div>
        
                    <div class="card-content px-3 py-3">
                        @if($activePackage && $activePackage->packageData)
                        <h5 class="mb-2">
                            {{ $activePackage->packageData->package_name }}
                        </h5>
        
                        <p class="mb-1">
                            💰 <strong>Price:</strong>
                            {{ number_format($activePackage->packageData->package_price) }} Tk
                        </p>
        
                        <p class="mb-1">
                            📅 <strong>Start Date:</strong>
                            {{ \Carbon\Carbon::parse($activePackage->start_date)->format('d M Y') }}
                        </p>
        
                        <p class="mb-2">
                            ⏳ <strong>End Date:</strong>
                            {{ \Carbon\Carbon::parse($activePackage->end_date)->format('d M Y') }}
                        </p>
        
                        @php
                        $isExpired = \Carbon\Carbon::parse($activePackage->end_date)->isPast();
                        @endphp

                        <p class="mb-2">
                            📌 <strong>Package Status:</strong>
                            <span class="badge {{ $isExpired ? 'bg-danger' : 'bg-success' }}">
                                {{ $isExpired ? 'Expired' : 'Active' }}
                            </span>
                        </p>

                        <div class="mt-3">
                            <a href="javascript:void(0)" class="btn btn-warning w-100" onclick="upgradePackage()">
                                Renew Package Now
                            </a>
                        </div>
                        @else
                        <p class="text-danger mb-0">
                            বর্তমানে কোনো সক্রিয় প্যাকেজ পাওয়া যায়নি।
                            অনুগ্রহ করে একটি প্যাকেজ ক্রয় করুন।
                        </p>
        
                        <a href="{{ route('package-renew') }}" class="btn btn-warning w-100 mt-3">
                            প্যাকেজ কিনুন
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="upgradePackage" tabindex="-1" aria-labelledby="oneInputModalLabel"
            aria-hidden="true" data-bs-backdrop='static'>
            <div class="modal-dialog modal-dialog-centered max-width-1000px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="oneInputModalLabel">Package Renew</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form action="{{ route('save-package-renew') }}" method="POST" enctype="multipart/form-data">
                            @csrf
        
                            <input type="hidden" name="admin_id" id="" value="{{$activePackage->package_by}}">
                            <input type="hidden" name="package_id" id="" value="{{$activePackage->package_id}}">
                            <input type="hidden" name="package_category_id" id="" value="{{$activePackage->packageData->package_category_id}}">
                            <div class="row px-4">
                                <div class="col-md-6 my-1">
                                    <div class="col-md-4-group custom-select2-form">
                                        <label for="package_category_id">Package Category<span class=" text-danger">*</span>
                                        </label>
                                        <select  id="package_category_id"
                                            class="form-select select2" disabled required>
        
                                            <option value="{{ $activePackage->packageData->package_category_id }}" selected disabled>
                                                {{ $activePackage->packageData->packageCategoryData->category_name }}
                                            </option>
        
                                        </select>
                                    </div>
        
                                    @error('current_balance')
                                    <span class=text-danger>{{$message}}</span>
                                    @enderror
                                </div>
        
                                <div class="col-md-6 my-1">
                                    <div class="col-md-4-group custom-select2-form">
                                        <label for="package_id">Package<span class=" text-danger">*</span> </label>
                                        <select  id="package_id" class="form-select select2" disabled required>
                                            <option value="{{ $activePackage->package_id }}" selected disabled>
                                                {{ $activePackage->packageData->package_name }} / Price: {{$activePackage->packageData->package_price}}
                                            </option>
        
                                        </select>
                                    </div>
        
                                    @error('current_balance')
                                    <span class=text-danger>{{$message}}</span>
                                    @enderror
                                </div>
        
                            </div>
                            <div class="modal-footer">
                                <div class="">
                                    <button type="submit" class="btn btn-success">Renew Now</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            timer: 4000,
            showConfirmButton: false
        });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            timer: 4000,
            showConfirmButton: false
        });
</script>
@endif

<script>
    //To show update modal...
    function upgradePackage() {
    $("#upgradePackage").modal('show');
    }
</script>

  @endsection