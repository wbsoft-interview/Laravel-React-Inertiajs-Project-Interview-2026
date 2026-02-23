@extends('backend.master')
@section('title') Upozila | Beauty Parlour @endsection
@section('upozila') active @endsection
@section('upozila-list') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Upozila List</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row my-2 aggregate-section-div">
        <div class="px-3 ">
            <div class=" d-flex justify-content-between align-items-center aggregate-section border">
                <div class="d-flex align-items-center">
                    <p class="mb-0"><a href="{{route('upozila-list')}}"
                            class="text-primary py-2 px-3 active">All({{$allUpozilaCount}})</a>
                    </p>
                </div>
                <div class="d-sm-block">
                    @if(Auth::user()->can('zone-create'))
                    <a href="javascript:void(0)"
                        class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
                        <i class="fa fa-list my-auto"></i>
                        <span class="">Upozila List</span></a>
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
                        <th class="text-center" scope="col"><span>Name</span></th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach($upozilaData as $key=>$item)
                    @if(isset($item) && $item != null)
                    <tr class="">

                        <td>
                            <b>{{$key+1}}</b>
                        </td>

                        <td class="text-start">
                            <div class="row_title">
                                <b>Division: </b> {{ $item->districtData->divisionData->name_en }} <br>
                                <b>District: </b> {{ $item->districtData->name_en }} <br>
                                {{-- <b>URL: </b> {{ $item->url }} <br> --}}
                            </div>
                            <div class="row-actions mt-2">

                                {{-- @if (Auth::user()->can('zone-edit'))
                                <span><button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                                        onclick="updateZone({{$item->id}})"> Edit </button></span>
                                @endif

                                @if (Auth::user()->can('zone-delete'))
                                <span> | <a class="text-danger fw-bolder"
                                        href="{{route('zone-delete', $item->id)}}">Delete</a>
                                </span>
                                @endif --}}
                            </div>
                        </td>
                        <td class="text-start">
                            <b>Name: </b> {{ $item->name_en }} <br>
                            <b>Bangla: </b> {{ $item->name_bn }} <br>
                        </td>

                    </tr>

                    @endif
                    @endforeach


                </tbody>
            </table>
        </div>

        @if(isset($allUpozilaCount) && $allUpozilaCount > 10)
        <div class="clearfix d-flex">
            <div class="float-left">
                <p class="text-muted">
                    {{ __('Showing') }}
                    <span class="font-weight-bold">{{ $upozilaData->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-weight-bold">{{ $upozilaData->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-weight-bold">{{ $upozilaData->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div class="float-right custom-table-pagination">
                {!! $upozilaData->links('pagination::bootstrap-4') !!}
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
@endsection
