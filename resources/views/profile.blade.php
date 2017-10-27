@extends('layouts.app')

@section('content')
@include('partials.sidebar')
<div class="container">
<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >


          <div class="panel panel-primary">
            <div class="panel-heading">
              <h2 class="panel-title">{{$user->name}}</h2>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="http://www.infozonelive.com/styles/FLATBOOTS/theme/images/user4.png" class="img-circle img-responsive"> </div>
                <div class=" col-md-9 col-lg-9 ">
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Department:</td>
                        <td>Local IS</td>
                      </tr>
                      <tr>
                      <tr>
                        <td>Name</td>
                        <td>{{$user->name}}</td>
                      </tr>
                      <tr>
                        <td>Email</td>
                        <td>{{$user->email}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            @if ($errors->has('email'))
              <span class="alert alert-danger">{{ $errors->update->first('email') }}</span>
            @endif

            <div class="panel-footer">
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                Edit Profile
              </button>

              <!-- Modal -->
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">Profile Information</h4>
                    </div>
                      <form class="" action="{{url('/profile')}}" method="post">
                        {{csrf_field()}} {{method_field('PUT')}}
                        <div class="modal-body">
                          <div>
                            <p>Name</p>
                            <input type="text" name="name" value="{{$user->name}}" placeholder="Name" class="form-control" required>
                          </div>

                          <div>
                            <p>Email</p>
                            <input type="text" name="email" value="{{$user->email}}" placeholder="Email" class="form-control" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Save changes</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                      </form>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
</div>
@endsection
