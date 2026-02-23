@extends('backend.master')
@section('title') Role | Master Template @endsection
@section('roles') active @endsection
@section('styles')
@endsection
@section('main_content_section')

<!-- BEGIN: Content-->
<div class="app-content content">

  <div class="content-header row ps-2">
    <div class="content-header-left col-md-9 col-12">
      <div class="row breadcrumbs-top">
        <div class="col-12 my-3">
          <h3 class="content-header-title float-start mb-0">Role List </h3>
        </div>
      </div>
    </div>
  </div>

  <div class="row aggregate-section-div">
    <div class="">
      <div class=" d-flex justify-content-between align-items-center aggregate-section border">
        <div class="d-flex align-items-center py-1">
          <p class="mb-0"><a href="{{route('roles.index')}}" class="text-primary  py-2 px-3 active">All
              (1)</a></p>
        </div>
        <div class=" d-sm-block">
          @if (Auth::user()->can('role-create'))
          <a href="{{route('roles.create')}}" class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0">
            <i class="fa fa-plus my-auto"></i>
            <span class="my-auto">New Role</span></a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table id="" class="table table-bordered">
          <thead>
            <tr>
              <th scope="col" style="width:200px;">Name</th>
              <th scope="col">Guard</th>
              <th scope="col">Permission</th>
            </tr>
          </thead>
          <tbody>

            @foreach($roles as $item)
            @if (Auth::user()->can('role-list'))
            <tr>
              <td>
                {{Str::title($item->display_name)}}
                <div class="row-actions">
                  @if (Auth::user()->can('role-edit'))
                    @if(Auth::user()->role == 'superadmin' && $item->display_name != 'superadmin')
                      <span><a href="{{ route('roles.edit',$item->id) }}" class="edit_class_modal border-0 bg-transparent fw-bolder">
                          <span class="text-primary">Edit</span></a></span>
                    @else
                        <span><a href="{{ route('roles.edit',$item->id) }}" class="edit_class_modal border-0 bg-transparent fw-bolder">
                            <span class="text-primary">Edit</span></a></span>
                    @endif
                  @endif

                  @if (Auth::user()->can('role-delete') && !in_array($item->display_name, ['superadmin', 'admin']))
                  <span> | <a href="{{ route('roles-delete', ['id'=>$item->id]) }}" class="row-delete">
                      <span class="trash text-danger fw-bolder row-delete">Delete</span>
                    </a>
                  </span>
                  @endif
                </div>
              </td>
              <td>{{$item->guard_name}}</td>

              <td>
                @foreach ($item->permissions as $perm)
                <span class="badge bg-info" style="margin-top: 5px;">
                  {{ $perm->name }}
                </span>
                @endforeach
              </td>
            </tr>
            @endif
            @endforeach


          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<!-- END: Content-->

@endsection