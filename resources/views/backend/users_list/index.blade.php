
@extends('layout.base')
@section("custom_css")
<link href="/backend/assets/build/css/intlTelInput.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css">
        <link rel="stylesheet" href="backend/assets/css/all_users.css">
@stop
        @section('content')
                <div class="content">
                    <a href="#" class="float" data-toggle="modal"
                                                    data-target="#myModal">
<i class="fa fa-plus my-float" ></i>
</a>
                    <div class="container-fluid">
                        <div class="row page-title">
                            <div class="col-md-12">
                                <h4 class="mb-1 mt-0">All Users</h4>
                            </div>
                        </div>

                                                <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- <h4 class="header-title mt-0 mb-1">Basic Data Table</h4> --}}
                                            <p class="sub-header">
                                           Find Users
                                                </p>
                                            <div class="container-fluid">
                                                <div class="row">

                                            <div class="form-group col-lg-4 mt-4">
                                                <div class="row">
                                                <label class="form-control-label">Reference Number</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="icon-dual" data-feather="lock"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" id="password" >
                                                </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-4 mt-4">
                                                <label class="form-control-label">Business Name</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="icon-dual" data-feather="lock"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" id="password" >
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-4 mt-4">
                                                <label class="form-control-label">Phone Number</label>
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">

                                                    </div>
                                                    <input type="tel" id="phone" class="form-control">


                                                </div>
                                            </div>

                                                <button type="button" class="btn btn-primary">Search</button>
                                                </div>


                                            </div>

                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div>



                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- <h4 class="header-title mt-0 mb-1">Basic Data Table</h4> --}}
                                            <p class="sub-header">
                                            List of all registered users
                                                </p>
                                            <div class="table-responsive">
                                                 <table class="table mb-0" id="basic-datatable">
                                                <thead>
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Name</th>
                                                     <th scope="col">Location</th>
                                                    <th scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                     @isset($response)
                                           @for ($i = 0; $i < count($response); $i++)
                                        <tr>
                                        <th>{{$i + 1 }}</th>
                                                    <td>{{$response[$i]->first_name}}<br> <span>{{$response[$i]->_id}}</span> 
        
                                                        @if ($response[$i]->user_role == "store_admin")
                                                            <span class="badge badge-primary">owner</span> 
                                                        @elseif ($response[$i]->user_role == "store_assistant")
                                                            <span class="badge badge-secondray">assistant</span>
                                                        @else
                                                             <span class="badge badge-info">No role</span>
                                                        @endif
                                                        @if($response[$i]->is_active)
                                                        <span class="badge badge-success">Activated</span> 
                                                         </td>
                                                         @else
                                                          <span class="badge badge-secondary">Not activated</span>
                                                        </td>
                                                        @endif
                                                    <td>
                                                       @if(isset($response[$i]->store_address))
                                                       {{$response[$i]->store_address}} <br>
                                                       @else
                                                        Store Location Address would be here <br>
                                                       @endif                                                    
                                                    <span class="badge badge-primary">Store Reference Code: 
                                                        @if(isset($response[$i]->store_ref_code))
                                                       {{$response[$i]->store_ref_code}} <br>
                                                       @else
                                                        ST145M455 <br>
                                                       @endif
                                                        
                                                    </span>
                                                 </td>
                                                    <td><div class="btn-group mt-2 mr-1">
                                            <button type="button" class="btn btn-info dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions<i class="icon"><span data-feather="chevron-down"></span></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="/backend/users/{{$i}}">View Profile</a>
                                                <a class="dropdown-item" href="#">Active</a>
                                                <a class="dropdown-item" href="#">Deactivate</a>
                                                </div>
                                                </div></td>
                                                </tr>
                                            @endfor
                                       @endisset
                                                {{-- <tr>
                                                    <th scope="row">1</th>
                                                    <td>John Doe <br> <span>SO123aM123</span> <span class="badge badge-primary">Store Owner</span> <span class="badge badge-secondary">assistant</span> <span class="badge badge-success">Activated</span> </td>
                                                    <td>Store Location Address would be here <br> <span class="badge badge-primary">Store Reference Code: ST145M455</span> </td>
                                                    <td><div class="btn-group mt-2 mr-1">
                                            <button type="button" class="btn btn-info dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions<i class="icon"><span data-feather="chevron-down"></span></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="/backend/view_user">View Profile</a>
                                                <a class="dropdown-item" href="#">Active</a>
                                                <a class="dropdown-item" href="#">Deactivate</a>
                                            </div>
                                        </div></td>
                                                </tr> --}}
                                            </tbody>
                                        </table>
                                    </div>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div>

                    </div>
                </div>
                                            <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel">Create New User</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                      <form class="form-horizontal">
                                            <div class="form-group row mb-3">
                                                <label for="inputphone" class="col-3 col-form-label">Phone Number</label>
                                                <div class="col-9">
                                                    <input type="number" class="form-control" id="inputphone" placeholder="Phone Number">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label for="inputPassword3" class="col-3 col-form-label">Password</label>
                                                <div class="col-9">
                                                    <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label for="inputPassword5" class="col-3 col-form-label">Re Password</label>
                                                <div class="col-9">
                                                    <input type="password" class="form-control" id="inputPassword5" placeholder="Retype Password">
                                                </div>
                                            </div>
                                            <div class="form-group mb-0 justify-content-end row">
                                                <div class="col-9">
                                                    <button type="submit" class="btn btn-primary btn-block ">Create User</button>
                                                </div>
                                            </div>
                                        </form>
                                                </div>
                                                                                                <div class="modal-footer">
                                                </div>

                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
        @endsection


    @section("javascript")
<script src="/backend/assets/build/js/intlTelInput.js"></script>
<script>
var input = document.querySelector("#phone");
window.intlTelInput(input, {
    // any initialisation options go here
});
</script>
    @stop
