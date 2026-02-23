@extends('backend.master')
@section('title') Role | Master Template @endsection
@section('roles') active @endsection
@section('styles')
@endsection
@section('main_content_section')
	
	<!-- BEGIN: Content-->
    <div class="app-content content min-vh-100">
		<div class="content-header row ps-2">
			<div class="content-header-left col-md-9 col-12">
				<div class="row breadcrumbs-top">
					<div class="col-12 my-3">
						<h3 class="content-header-title float-start mb-0">Update Permission</h3>
					</div>
				</div>
			</div>
		</div>
	    <div class="card">
			<form action="{{route('roles.update', $role->id)}}" method="post">
				@csrf
				@method('put')

				<div class="card-body border-bottom pb-4">
					<div class="form-group">
						<label class="required" for="name">Role Name</label>
						<input class="form-control" type="text" name="name" id="name" value="{{ Str::title($role->display_name) }}" placeholder="Role Name">
				
						@error('name')
						<span class=text-danger>{{$message}}</span>
						@enderror
					</div>
				</div>

	        	<div class="card-body">
					<div class="container">
						<div class="row">
							@php $i = 0; @endphp
							@foreach ($permissionGroups as $group)
							@if(isset($group) && $group != null)
							@php
							$permissions = App\Models\User::getpermissionsByGroupName($group->name);
							$j = 1;
							$i += 1
							@endphp
				
							<div class="col-md-4 mb-3">
								<div class="input-field">
									<p>
										<label class="custom-permission-group-color">
											<input type="checkbox" id="{{ $i }}Management" value="{{ $group->name }}"
												onclick="checkPermissionByGroup('role-{{$i}}-management-checkbox', this)"
												{{ App\Models\User::roleHasPermissions($role, $permissions) ? 'checked' : '' }}
												class="custom-permission-check-box" />
				
											<span class="custom-permission-group-name custom-cursor-pointer"
												for="{{ $i }}Management">{{ Str::title($group->name) }}</span>
										</label>
									</p>
								</div>
							</div>
				
							<div class="col-md-8 mb-3">
								<div class="input-field role-{{ $i }}-management-checkbox">
				
									@foreach ($permissions as $permission)
									@if(isset($group) && $group != null)
									<p>
										<label>
											<input class="filled-in common-input" type="checkbox" name="permissions[]"
												onclick="checkSinglePermission('role-{{ $i }}-management-checkbox', '{{ $i }}Management', {{ count($permissions) }})"
												{{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} id="checkPermission{{ $permission->id }}"
												value="{{ $permission->name }}">
									
											<span class="custom-permission-name custom-cursor-pointer" for="checkPermission{{ $permission->id }}">{{ $permission->name }}</span>
										</label>
									</p>
									@endif
									@endforeach
				
								</div>
							</div>
				
							@endif
							@endforeach
						</div>
				
						<div class="row">
							<div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 d-flex align-items-center"
								style="margin-top: 15px;">
								<input type="submit" name="submit" class="btn btn btn-success" value="Update">
							</div>
						</div>
					</div>
				</div>
			</form>

	    </div>

    </div>
    <!-- END: Content-->

@endsection

@section('scripts')
@include('backend.userRole.roles.partial.script')
@endsection