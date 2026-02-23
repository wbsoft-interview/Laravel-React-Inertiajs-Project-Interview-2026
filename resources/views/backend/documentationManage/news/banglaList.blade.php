@extends('backend.master')
@section('title') Bangla News | Sangbad Protikhon @endsection
@section('news') active @endsection
@section('news.index') active @endsection
@section('styles')
@endsection

@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Bangla News List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3">
            <div class="d-flex justify-content-between align-items-center aggregate-section border flex-wrap">

                <div class="d-flex flex-wrap align-items-center">
                    <a href="{{ route('news-list-bn') }}"
                        class="py-2 px-3 {{ request()->is('news-list-bn') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                        All ({{ $allNewsCount }})
                    </a>
                    <a href="{{ route('published-news-bn') }}"
                        class="py-2 px-3 {{ request()->is('published-news-bn') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                        Published ({{ $publishedNewsCount }})
                    </a>
                    <a href="{{ route('unpublished-news-bn') }}"
                        class="py-2 px-3 {{ request()->is('unpublished-news-bn') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                        Unpublished ({{ $unpublishedNewsCount }})
                    </a>
                    <a href="{{ route('draft-news-bn') }}"
                        class="py-2 px-3 {{ request()->is('draft-news-bn') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                        Draft ({{ $draftNewsCount }})
                    </a>
                </div>

                <div class="mt-2 mt-sm-0">
                    @if (Auth::user()->can('news-create'))
                    <a href="{{ route('news.create') }}"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                        <i class="fa fa-plus"></i>
                        <span>New News</span>
                    </a>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="row px-3">
        <div class="table-container table-responsive">
            <table id="newsDataTable" class="table table-bordered datatable-common-padding">
                <thead class="text-uppercase">
                    <tr>
                        <th class="d-none">Id</th>
                        <th>News Image</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
    $("#newsDataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('news-list-bn') }}",
        columns: [
            { data: 'id', name: 'id', visible: false, searchable: false },
            { data: 'photo', name: 'photo', orderable: false, searchable: false },
            { data: 'title_bn', name: 'title_bn', searchable: true },
            { data: 'is_published', name: 'is_published' },
            { data: 'created_at', name: 'created_at' }
        ],
        order: [[0, "desc"]],
    });
});
</script>
@endsection