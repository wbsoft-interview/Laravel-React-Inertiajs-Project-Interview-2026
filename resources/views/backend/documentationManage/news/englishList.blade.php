@extends('backend.master')
@section('title') English News | Sangbad Protikhon @endsection
@section('news') active @endsection
@section('news.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span>English News List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3">
            <div class="d-flex justify-content-between align-items-center aggregate-section border flex-wrap">
    
                <!-- Filter Tabs -->
                <div class="d-flex flex-wrap align-items-center">
                    <a href="{{ route('news-list-en') }}"
                        class="py-2 px-3 {{ request()->is('news-list-en') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                        All ({{ $allNewsCount }})
                    </a>
                    <a href="{{ route('published-news-en') }}"
                        class="py-2 px-3 {{ request()->is('published-news-en') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                        Published ({{ $publishedNewsCount }})
                    </a>
                    <a href="{{ route('unpublished-news-en') }}"
                        class="py-2 px-3 {{ request()->is('unpublished-news-en') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                        Unpublished ({{ $unpublishedNewsCount }})
                    </a>
                    <a href="{{ route('draft-news-en') }}"
                        class="py-2 px-3 {{ request()->is('draft-news-en') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                        Draft ({{ $draftNewsCount }})
                    </a>
                </div>
    
                <!-- Add New Button -->
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
            <table id="" class="table table-bordered">
                <thead class="text-uppercase">
                    <tr class="me-3">
                        <th class="text-center" scope="col"><span>News Image</span></th>
                        <th class="text-center" scope="col"><span>Details</span></th>
                        <th class="text-center" scope="col"><span>Status</span></th>
                        <th class="text-center" scope="col"><span>Created</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($newsData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">
                        <td>
                            <a href="{{ $item->photo ? asset('storage/uploads/newsImg/'.$item->photo) : asset('backend/template-assets/images/img_preview.png') }}"
                                data-rel="lightcase">
                                <img src="{{ $item->photo ? asset('storage/uploads/newsImg/'.$item->photo) : asset('backend/template-assets/images/img_preview.png') }}"
                                    height="100" width="100" alt="">
                            </a>
                        </td>
                        <td class="text-start">
                            <div class="row_title">
                                <b>Title: </b> <span>{{$item->title_en}} </span>
                            </div>
                            <div class="row-actions">
                                @if (Auth::user()->can('news-edit'))
                                <span><button class="edit_class_modal border-0 bg-transparent fw-bolder" onclick="updateNewsTag({{ $item->id }})">
                                        <a class="text-primary" href="javascript:void(0)">View</a> </button> | </span>
                                <span><button class="edit_class_modal border-0 bg-transparent fw-bolder" value="{{ $item->id }}"> <a class="text-info"
                                            href="{{route('news.edit', $item->id)}}">Edit</a> </button> | </span>
                                @endif
                                {{--
                                @if($item->is_published == true)
                                <span><a class="text-warning fw-bolder"
                                        href="{{route('news-inactive', $item->id)}}">Unpublished</a></span>
                                @else
                                <span><a class="text-success fw-bolder"
                                        href="{{route('news-active', $item->id)}}">Published</a></span>
                                @endif --}}

                                @if (Auth::user()->can('news-delete'))
                                <span> <a class="text-danger fw-bolder row-delete"
                                        href="{{route('news-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($item->is_published == 0)
                            <span class="badge bg-danger">Unpublished</span>
                            @elseif ($item->is_published == 1)
                            <span class="badge bg-success">Publishsed</span>
                            @else
                            <span class="badge bg-info">Save as Draft</span>
                            @endif
                        </td>

                        <td class="text-start">
                            <b>Date: </b><span
                                class="text-normal">{{Carbon\Carbon::parse($item->created_at->toDateString())->format('d-m-Y')}}</span><br>
                            <b>Time: </b><span
                                class="text-normal">{{Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Dhaka')->format('h:i A')}}</span>
                        </td>

                    </tr>

                    {{-- //Update ServiceTag.. --}}
                    <div class="modal fade" id="updateNewsTag{{$item->id}}" tabindex="-1" aria-labelledby="oneInputModalLabel"
                        aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">News Details</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('news-tag.update', $item->id) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body p-0">
                                        <div class="row px-4 my-4">
                    
                                            <div class="col-md-12">
                                                {!! $item->post_en !!}
                                            </div>
                    
                                        </div>
                                    </div>
                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
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

        @if(isset($allNewsCount) && $allNewsCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $newsData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $newsData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $newsData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $newsData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif

    </div>


</div>
@endsection

@section('scripts')
<script>
    //To show update modal...
    function updateNewsTag(id) {
    $("#updateNewsTag"+id).modal('show');
    }
</script>
@endsection