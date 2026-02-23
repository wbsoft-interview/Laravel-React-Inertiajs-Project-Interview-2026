@extends('backend.master')
@section('title') User | Master Template @endsection
@section('users') active @endsection
@section('styles')
@endsection

<!-- BEGIN: Content-->
@section('main_content_section')
<div class="row py-3 ps-2">
  <div class="heading d-flex justify-content-start heading-margin-bottom-minus">
    <h3 class="mb-0"><span>User List</span></h3>
  </div>
</div>


<div class="table_wrapper py-1 card">
  <div class="row my-2 aggregate-section-div">
    <div class="px-3 ">
      <div class=" d-flex justify-content-between align-items-center aggregate-section border">
        <div class="d-flex align-items-center py-1">
          <p class="mb-0"><a href="{{route('users.index')}}"
              class="text-primary  py-2 px-3 active">All ({{ $userCount }})</a></p>
        </div>
        <div class="d-sm-block">
          @if (Auth::user()->can('user-create')) 
            <a href="javascript:void(0)" class="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0"
              data-bs-toggle="modal" data-bs-target="#oneInputModalCenterForLarge">
              <i class="fa fa-plus my-auto"></i>
              <span class="my-auto">New User</span></a>
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
            
            <th class="text-center" scope="col"><span> Serial </span></th>
            <th class="text-center" scope="col"><span>User Name</span></th>
            <th class="text-center" scope="col"><span> Email </span></th>
            <th class="text-center" scope="col"><span> Mobile </span></th> 
            <th class="text-center" scope="col"><span> Role </span></th> 
            <th class="text-center" scope="col"><span> Status </span></th> 


          </tr>
        </thead>
        <tbody class="text-center">

          @foreach ($userData as $key=>$item)
          @if(isset($item) && $item != null)
            <tr class="">
              <td>
                <b>{{$key+1}}</b>
              </td>
              <td> 
                  {{ $item->name }}
                  <div class="row-actions">

                      @if (Auth::user()->can('user-edit')) 
                        @if ($item->role != 'superadmin') 
                          <span><button class="text-primary border-0 bg-transparent fw-bolder" value="{{ $item->id }}"
                              onclick="updateUser({{$item->id}})"> Edit </button> </span>

                          <span> | <a href="{{ route('users-delete', ['id'=>$item->id]) }}" class="row-delete fw-bolder"><span
                            class="trash text-danger">Delete</span> </a></span>

                          @if ($item->status == true)
                          <span> | <a href="{{ route('users-inactive', $item->id) }}" class="fw-bolder"><span
                                class="text-warning">Inactive</span> </a></span>
                          @else
                          <span> | <a href="{{ route('users-active', $item->id) }}" class="fw-bolder"><span
                                class="text-success">Active</span> </a></span>
                          @endif

                          @if(Auth::user()->role == 'superadmin')
                          <span> | <a href="javascript:void(0)" class="fw-bolder" onclick="upgradePackage({{$item->id}})"><span class="text-info">Package Renew</span>
                            </a></span>
                          @endif
                        @endif
                      @endif
                  </div>
              </td>
              <td> {{ $item->email }}</td>
              <td> {{ $item->mobile }}</td>
              @php
              $roleName = \Spatie\Permission\Models\Role::where('name', $item->role)->first();
              @endphp
              <td>
                <span class="badge bg-primary">{{ Str::title($roleName ? $roleName->display_name : '') }}</span>
              </td>
              <td>
                @if($item->status == true)
                <span class="badge bg-success">Active</span>
                @else
                <span class="badge bg-warning">Inactive</span>
                @endif
              </td>
            </tr>

            {{-- edit modal  --}}
            <div class="modal fade" id="updateUser{{$item->id}}" tabindex="-1" aria-labelledby="oneInputModalLabel"
              aria-hidden="true" data-bs-backdrop='static'>
              <div class="modal-dialog modal-dialog-centered max-width-1000px">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Update User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body p-0">
                    <form action="{{ route('users.update', $item->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return checkValidateForUpdate({{$item->id}})">
                      @csrf
                      @method('put')
                      <div class="row px-4">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class=" col-md-6 my-1">
                          <label for="name" class="form-label mb-2">Name<span class="text-danger">*</span></label>
                          <input type="text" class="form-control  form-control-solid" name="name" id="edit_name" placeholder="Name"
                            value="{{$item->name}}" required>

                            @error('name')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
            
                        <div class="col-md-6 my-1">
                          <label for="email" class="form-label mb-2">Email <span class="text-danger">*</span></label>
                          <input type="email" class="form-control  form-control-solid" name="email" id="edit_email"
                            value="{{$item->email}}" placeholder="Email" required>

                            @error('email')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 my-1">
                          <label for="mobile" class="form-label mb-2">Mobile <span class="text-danger">*</span></label>
                          <input type="number" class="form-control  form-control-solid number-control-hide" name="mobile"
                            id="mobile{{$item->id}}" value="{{$item->mobile}}" placeholder="Mobile" required>

                            @error('mobile')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
            
                        <div class="col-md-6 my-1">
                          <div class="form-group custom-select2-form setting-custom-select2-form custom-user-role">
                            <label for="roles">Role <span class="text-danger">*</span>
                            </label>
                            <select name="roles" id="roles{{$item->id}}" class="form-select select2 mt-2" required>
                              <option value="" selected disabled> Select Role </option>
                              @foreach($roles as $role)
                              <option value="{{$role->id}}" {{$item->hasRole($role->name) ? 'selected' : '' }}>{{Str::title($role->display_name)}}</option>
                              @endforeach
                            </select>

                            @error('roles')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                          </div>
                        </div>

                      </div>
                      <div class="modal-footer">
                        <div class="">
                          <button type="submit" class="btn btn-success">Update</button>
                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            
            @if(Auth::user()->role == 'superadmin')
            {{-- edit modal  --}}
            <div class="modal fade" id="upgradePackage{{$item->id}}" tabindex="-1" aria-labelledby="oneInputModalLabel"
              aria-hidden="true" data-bs-backdrop='static'>
              <div class="modal-dialog modal-dialog-centered max-width-1000px">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Package Renew</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body p-0">
                    <form action="{{ route('users-package-renew') }}" method="POST" enctype="multipart/form-data">
                      @csrf

                      <input type="hidden" name="admin_id" id="" value="{{$item->id}}">
                      <div class="row px-4">

                        @php
                            //To get adminpackage data...
                            $singleAPData = App\Models\AdminPackage::getSinglePackageData($item->id);
                            $getPackageData = App\Models\AdminPackage::getPackageData($item->id);
                        @endphp

                        @if(Auth::user()->role == 'superadmin')
                        <div class="col-md-6 my-1">
                          <div class="col-md-4-group custom-select2-form">
                            <label for="package_category_id">Package Category<span class=" text-danger">*</span> </label>
                            <select name="package_category_id" id="package_category_id{{$item->id}}" class="form-select select2"
                              onchange="getPackageFU({{$item->id}})" required>
                            
                              <option value="" selected disabled>Select Category</option>
                            
                              @foreach ($packageCategoryData as $category)
                              <option value="{{ $category->id }}"
                                {{ optional(optional($singleAPData)->packageData)->package_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                              </option>
                              @endforeach

                            </select>
                          </div>
                        
                          @error('current_balance')
                          <span class=text-danger>{{$message}}</span>
                          @enderror
                        </div>
                        
                        <div class="col-md-6 my-1">
                          <div class="col-md-4-group custom-select2-form">
                            <label for="package_id">Package<span class=" text-danger">*</span> </label>
                            <select name="package_id" id="package_id{{ $item->id }}" class="form-select select2"
                              data-current-package="{{ optional($item->activePackage)->package_id }}" required>
                              <option value="" selected disabled>Select Package</option>

                              @foreach ($getPackageData as $package)
                              <option value="{{ $package->id }}" 
                                data-price="{{ $package->package_price }}"
                                {{ optional($singleAPData)->package_id == $package->id ? 'selected' : '' }}>
                                {{ $package->package_name }} / Price: {{$package->package_price}}
                              </option>
                              @endforeach

                            </select>
                          </div>
                        
                          @error('current_balance')
                          <span class=text-danger>{{$message}}</span>
                          @enderror
                        </div>
                        @endif

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
            @endif

          @endif
          @endforeach


        </tbody>
      </table>
    </div>

    @if(isset($userCount) && $userCount > 10)
    <div class="clearfix d-flex">
      <div class="float-left">
        <p class="text-muted">
          {{ __('Showing') }}
          <span class="font-weight-bold">{{ $userData->firstItem() }}</span>
          {{ __('to') }}
          <span class="font-weight-bold">{{ $userData->lastItem() }}</span>
          {{ __('of') }}
          <span class="font-weight-bold">{{ $userData->total() }}</span>
          {{ __('results') }}
        </p>
      </div>
    
      <div class="float-right custom-table-pagination">
        {!! $userData->links('pagination::bootstrap-4') !!}
      </div>
    </div>
    @endif

  </div>

  {{-- create modal  --}}
  <div class="modal fade" id="oneInputModalCenterForLarge" tabindex="-1" aria-labelledby="oneInputModalLabel"
    aria-hidden="true" data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered max-width-1000px">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="oneInputModalLabel">New User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <form action="{{ route('users.store') }}" method="POST" onsubmit="return checkValidate()">
            @csrf
            <div class="row px-4">
              <div class=" col-md-6 my-1">
                <label for="name" class="form-label mb-2">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control  form-control-solid" name="name" id="name" placeholder="Name"
                  value="{{old('name')}}" required>

                @error('name')
                <span class=text-danger>{{$message}}</span>
                @enderror
              </div>
  
              <div class="col-md-6 my-1">
                <label for="email" class="form-label mb-2">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control  form-control-solid" name="email" id="email" placeholder="Email"
                  value="{{old('email')}}" required>

                  @error('email')
                  <span class=text-danger>{{$message}}</span>
                  @enderror
              </div>
              <div class="col-md-6 my-1">
                <label for="mobile" class="form-label mb-2">Mobile <span class="text-danger">*</span></label>
                <input type="number" class="form-control  form-control-solid number-control-hide" name="mobile"
                  id="mobile" placeholder="Mobile" value="{{old('mobile')}}" required>

                  @error('mobile')
                  <span class=text-danger>{{$message}}</span>
                  @enderror
              </div>
  
              <div class="col-md-6 my-1">
                <label for="password" class="form-label mb-2">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control  form-control-solid" name="password" id="password"
                  placeholder="Password" required>

                  @error('password')
                  <span class=text-danger>{{$message}}</span>
                  @enderror
              </div>
  
              <div class="col-md-6 my-1">
                <label for="password_confirmation" class="form-label mb-2">Confirm Password <span
                    class="text-danger">*</span></label>
                <input type="password" class="form-control  form-control-solid" name="password_confirmation"
                  id="password_confirmation" placeholder="Confirm Password" required>

                  @error('password_confirmation')
                  <span class=text-danger>{{$message}}</span>
                  @enderror
              </div>
  
              <div class="col-md-6 my-1">
                <div class="form-group custom-select2-form setting-custom-select2-form custom-user-role">
                  <label for="roles">Role <span class="text-danger">*</span>
                  </label>
                  <select name="roles" id="roles" class="form-select select2" required>
                    <option value="" selected disabled> Select Role </option>
                    @foreach($roles as $role)
                    <option value="{{$role->id}}">{{Str::title($role->display_name)}}</option>
                    @endforeach
                  </select>

                  @error('roles')
                  <span class=text-danger>{{$message}}</span>
                  @enderror
                </div>
              </div>

              @if(Auth::user()->role == 'superadmin')
              <div class="col-md-6 my-1">
                <div class="col-md-4-group custom-select2-form">
                  <label for="package_category_id">Package Category<span class=" text-danger">*</span> </label>
                  <select name="package_category_id" id="package_category_id" class="form-select select2" onchange="getPackage()" required>
                    <option value="" selected disabled>Select Category</option>
                    @foreach ($packageCategoryData as $category)
                    <option value="{{  $category->id }}">
                      {{  $category->category_name}}</option>
                    @endforeach
                  </select>
                </div>
              
                @error('current_balance')
                <span class=text-danger>{{$message}}</span>
                @enderror
              </div>
              
              <div class="col-md-6 my-1">
                <div class="col-md-4-group custom-select2-form">
                  <label for="package_id">Package<span class=" text-danger">*</span> </label>
                  <select name="package_id" id="package_id" class="form-select select2" required>
                    <option value="" selected disabled>Select Package</option>
              
                  </select>
                </div>
              
                @error('current_balance')
                <span class=text-danger>{{$message}}</span>
                @enderror
              </div>
              @endif

            </div>
            <div class="modal-footer">
              <div class="">
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


</div>
@endsection

@section('scripts')
<script>

  $("#roles").select2({
    dropdownParent: $('#oneInputModalCenterForLarge')
  })
  $("#package_category_id").select2({
    dropdownParent: $('#oneInputModalCenterForLarge')
  })
  $("#package_id").select2({
    dropdownParent: $('#oneInputModalCenterForLarge')
  })

  function checkValidate() {
        var mobileNumber = $("#mobile").val();

        if (mobileNumber != '' && mobileNumber.length != 11) { 
            event.preventDefault();
            toastr.error("Mobile number must be 11 digit."); 
            return false;
        }

        return true;
    }
    
    //For update...
    function checkValidateForUpdate(id) {
        var mobileNumber = $("#mobile"+id).val();

        if (mobileNumber != '' && mobileNumber.length != 11) { 
            event.preventDefault();
            toastr.error("Mobile number must be 11 digit."); 
            return false;
        }

        return true;
    }
  
  //To show update modal...
  function updateUser(id) {
      $("#updateUser"+id).modal('show');
      $("#roles"+id).select2({
        dropdownParent: $('#updateUser'+id)
      })
  }
  
  //To show update modal...
  function upgradePackage(id) {
      $("#upgradePackage"+id).modal('show');
      $("#package_category_id"+id).select2({
        dropdownParent: $('#upgradePackage'+id)
      })
      $("#package_id"+id).select2({
        dropdownParent: $('#upgradePackage'+id)
      })
  }

  //To fetch all the packages...
  function getPackage() {
      var packageCategoryId = $("#package_category_id").val();
      var url = "{{ route('get-all-package-with-category-id') }}";
      if (packageCategoryId != '') {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              type: 'post',
              url: url,
              data: {
                  package_category_id: packageCategoryId
              },
              success: function (data) {
                  //For Subject...
                  $("#package_id").empty();
                  $("#package_id").append('<option value="" selected disabled>Select Package</option>');

                  $.each(data, function(key, value){
                    $("#package_id").append(
                    '<option value="'+value.id+'" data-price="'+value.package_price+'">'+
                      value.package_name + ' / Price: ' + value.package_price +
                      '</option>'
                    );
                  });
              }

          });
      }
  };

  //To fetch all the packages...
  function getPackageFU(id) {
      var packageCategoryId = $("#package_category_id"+id).val();
      var url = "{{ route('get-all-package-with-category-id') }}";
      if (packageCategoryId != '') {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              type: 'post',
              url: url,
              data: {
                  package_category_id: packageCategoryId
              },
              success: function (data) {
                  //For Subject...
                  $("#package_id"+id).empty();
                  $("#package_id"+id).append('<option value="" selected disabled>Select Package</option>');

                  $.each(data, function(key, value){
                    $("#package_id"+id).append(
                    '<option value="'+value.id+'" data-price="'+value.package_price+'">'+
                      value.package_name + ' / Price: ' + value.package_price +
                      '</option>'
                    );
                  });
              }

          });
      }
  };

</script>
@endsection