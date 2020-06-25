@extends('layout.base')
@section("custom_css")


@stop




@section('content')

    <div class="account-pages my-5">
        <div class="container-fluid">
            <div class="row-justify-content-center">
                <div class="h2"><i data-feather="file-text" class="icon-dual"></i> Settings Page</div>

                <div class="col-md-12">
                    <div class="profile">
                        <div class="info"><img class="user-img" src="">
                            <h4>Username</h4>

                        </div>
                        <div class="cover-image"></div>
                    </div>
                </div>

                <style>
                    .line-head{
                        border-bottom: solid 1px #dddddd;
                        margin-top: 0!important;
                        margin-bottom: 15px;
                    }
                    nav-tabs .nav-link.active,
                    .nav-tabs .nav-item.show .nav-link {
                        color: #495057;
                        background-color: #FFF;
                        border-color: #dee2e6 #dee2e6 #FFF;
                    }
                    .nav-tabs li a.active {
                        border-left: 5px solid #5369f8!important;
                        border-bottom: none!important;
                    }
                </style>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <ul class="nav flex-column nav-tabs user-tabs">
                                <li class="nav-item"><a class="nav-link active" href="#user-details" data-toggle="tab">Details</a></li>
                                <li class="nav-item"><a class="nav-link" href="#user-timeline" data-toggle="tab">Timeline</a></li>
                                <li class="nav-item"><a class="nav-link" href="#user-profile" data-toggle="tab">Profile</a></li>
                                <li class="nav-item"><a class="nav-link" href="#password-change" data-toggle="tab">Password</a></li>
                                <li class="nav-item"><a class="nav-link" href="#assistant" data-toggle="tab">Staff Assistant</a></li>
                            </ul>
                        </div>
                    </div>


                    <div class="col-md-9">
                        <div class="card p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="user-details">
                                    <div class="tile user-settings">
                                        <h4 class="line-head">Details</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Username</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>John Doe</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Email</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>johndoe@yahoo.com</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Status</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                Active

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="user-timeline">
                                    <div class="tile user-settings">
                                        <h4 class="line-head">Timeline</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Created</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Two weeks ago</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Last Updated</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>One Week Ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="user-profile">
                                    <div class="tile user-settings">
                                        <h4 class="line-head">Profile</h4>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="row mb-12">
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Username</label>
                                                            <input class="form-control" type="text" name="username" value="John Doe">
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div><br>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Email</label>
                                                            <input class="form-control" type="text" name="email" value="johndoe@yahoo.com">
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div><br>
                                                </div>

                                                <div class="col-md-3">

                                                    <img id="preview" class="img img-responsive img-thumbnail" src="{{asset('backend/assets/images/users/avatar-7.jpg')}}"/>

                                                    <div class="form-group">
                                                        <label for="exampleInputFile">File input</label>
                                                        <input class="form-control-file" id="profile_image" type="file" aria-describedby="fileHelp" name="image">
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row mb-12">
                                                <div class="col-md-12">
                                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="password-change">
                                    <div class="tile user-settings">
                                        <h4 class="line-head">Change Password</h4>
                                        <form method="POST" action="">
                                            {{csrf_field()}}
                                            <label class="control-label">Password</label>
                                            <div class="form-group">
                                                <label class="sr-only" for="exampleInputAmount">Password</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class=" fa fa-lock"></i></span></div>
                                                    <input class="form-control" id="password" name="password" type="password">

                                                </div>
                                            </div>


                                            <label class="control-label">Confirm Password</label>
                                            <div class="form-group">
                                                <label class="sr-only" for="exampleInputAmount">Confirm Password</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class=" fa fa-lock"></i></span></div>
                                                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password">

                                                </div>
                                            </div>


                                            <button class="btn btn-primary" type="submit">Update</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="assistant">
                                    <div class="tile user-settings">
                                        <h4 class="line-head">Staff Assistant</h4>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><b>Staff Assigned to You:</b></p>
                                            </div>
                                            <div class="col-md-6">Sean Jones</div>
                                            <div class="col-md-6">
                                                <p>seanjones@customerpay.me</p>
                                                <p>+2345667788912</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


    </div>

@endsection





@section("javascript")

@stop