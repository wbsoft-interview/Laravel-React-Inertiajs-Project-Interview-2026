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

        <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
            <div class="h-100">
                <div class="card card-stats h-100">
                    <div class="card-header">
                        <div class="icon icon-warning d-flex">
                            <span class="material-symbols-outlined">campaign</span>
                            <p class="category custom-card-header-title"><strong>নোটিশ</strong></p>
                        </div>
                    </div>
                    <div class="card-content px-3 py-2">
                        <p class="category mb-0">প্রতিষ্ঠানের ডাটা সুরক্ষার কথা মাথায় রেখে আমরা সফটওয়্যারের ডিলিট অপশন বন্ধ রেখেছি , আমরা আপনাদের অনুরোধ করবো ডিলিট না
                        করে ইনাক্টিভ করুন। কিন্তু প্রতিষ্ঠানের আবেদনের ভিত্তিতে ডিলিট অপশনটি পুনরায় চালু করা যেতে পারে।</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
            <div class="h-100">
                <div class="card card-stats h-100">
                    <div class="card-header">
                        <div class="icon icon-warning d-flex">
                            <span class="material-symbols-outlined">campaign</span>
                            <p class="category custom-card-header-title"><strong>ব্যাবহারকারী</strong></p>
                        </div>
                    </div>
                    <div class="card-content px-3 py-2">
                        <p class="category mb-0">এই সফটওয়্যারটি Tiayra Laser & Aesthetic Center এর জন্য প্রস্তুতকৃত।</p>
                        <p class="mb-0">ক্লায়েন্ট - Unlimited</p>
                        <p class="mb-0">সার্ভিস - Unlimited</p>
                        <p class="mb-0">ইনভয়েস - Unlimited</p>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->can('user-list'))
        <div class="col-lg-4 col-md-4 col-sm-6 mb-4">
            <div class="h-100">
                <div class="card card-stats h-100">
                    <div class="card-header">
                        <div class="icon icon-warning d-flex">
                            <span class="material-symbols-outlined">equalizer</span>
                            <p class="category custom-card-header-title"><strong>Users</strong></p>
                        </div>
                    </div>
                    <div class="card-content ps-3 py-2">
                        <p class="category"><strong>Total</strong></p>
                        <h3 class="card-title">{{ $userCount }}</h3>
                    </div>
                    <div class="card-footer p-0 dashboard-card-footer">
                        <div class="stats">
                            <div class="row">
                                <a href="{{ route('users.index') }}" class="text-white">
                                    <div class="col-12 dashboard-card-color text-center py-2">User List</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection