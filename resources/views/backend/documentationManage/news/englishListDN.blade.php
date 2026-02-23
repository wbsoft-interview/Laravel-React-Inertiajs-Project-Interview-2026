@extends('backend.master')
@section('title') Draft English News | Sangbad Protikhon @endsection
@section('news') active @endsection
@section('news.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span>Draft English News List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0">
                        <a href="{{ route('documentation.index') }}"
                            class="py-2 px-3 {{ request()->is('documentation') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                            All ({{ $allNewsCount }})
                        </a>
                        <a href="{{ route('published-documentation-en') }}"
                            class="py-2 px-3 {{ request()->is('published-documentation-en') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                            Published ({{ $publishedNewsCount }})
                        </a>
                        <a href="{{ route('unpublished-documentation-en') }}"
                            class="py-2 px-3 {{ request()->is('unpublished-documentation-en') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                            Unpublished ({{ $unpublishedNewsCount }})
                        </a>
                        <a href="{{ route('draft-documentation-en') }}"
                            class="py-2 px-3 {{ request()->is('draft-documentation-en') ? 'text-white bg-primary rounded' : 'text-primary' }}">
                            Draft ({{ $draftNewsCount }})
                        </a>
                    </p>
                </div>
                <div class="d-none d-sm-block">
                    @if (Auth::user()->can('documentation-create'))
                    <a href="{{route('documentation.create')}}"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                        <i class="fa fa-plus"></i>
                        <span class="">New Documentation</span></a>
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
                        <th class="text-center" scope="col"><span>Documentation Image</span></th>
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
                            <a href="{{ $item->photo ? Storage::url('uploads/documentationImg/' . $item->photo) : asset('backend/template-assets/images/img_preview.png') }}"
                                data-rel="lightcase">
                                <img src="{{ $item->photo ? Storage::url('uploads/documentationImg/' . $item->photo) : asset('backend/template-assets/images/img_preview.png') }}"
                                    height="100" width="100" alt="">
                            </a>
                        </td>
                        <td class="text-start">
                            <div class="row_title">
                                <b>Title: </b> <span>{{$item->title_en}} </span><br>
                                {{-- <b>Description: </b><span class="pl-1 ml-2">{!! Str::limit(preg_replace('/<img[^>]*>/', '', $item->post_en), 100) !!}</span> --}}
                            </div>
                            <div class="row-actions">
                                @if (Auth::user()->can('documentation-edit'))
                                <span><button class="edit_class_modal border-0 bg-transparent fw-bolder">
                                        <a class="text-primary" href="javascript:void(0)"
                                            onclick="updateNewsTag({{ $item->id }})">View</a> </button> | </span>
    
                                <span><button class="edit_class_modal border-0 bg-transparent fw-bolder"
                                        value="{{ $item->id }}"> <a class="text-info"
                                            href="{{route('documentation.edit', $item->id)}}">Edit</a> </button> | </span>
                                @endif
    
                                @if (Auth::user()->can('documentation-delete'))
                                <span> <a class="text-danger fw-bolder row-delete"
                                        href="{{route('documentation-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif
                            </div>
                        </td>
    
                        <td>
                            @if($item->is_published == 2)
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
                    <div class="modal fade" id="updateNewsTag{{$item->id}}" tabindex="-1"
                        aria-labelledby="oneInputModalLabel" aria-hidden="true" data-bs-backdrop='static'>
                        <div class="modal-dialog modal-dialog-centered max-width-900px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Documentation Details</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="#" method="post" enctype="multipart/form-data">
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
    
        @if(isset($draftNewsCount) && $draftNewsCount > 10)
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