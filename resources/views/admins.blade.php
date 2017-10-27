@extends('layouts.app')

@section('content')
@include('partials.sidebar')
<div class="container">
    <div class="row">
        <h1>Administrators</h1>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary {{(Auth::user()->role !== 'super') ? 'hidden' : ''}}" data-toggle="modal" data-target="#addAccount">
          Add account
        </button>

        <!-- Modal -->
        <div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Account?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="{{url('add-account')}}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">
                  <div>
                    <p><b>Full name</b></p>
                    <input type="text" name="name" placeholder="Full name" class="form-control" value="{{old('name')}}" required>
                  </div>

                  <div>
                    <p><b>Email</b></p>
                    <input type="email" name="email" placeholder="Email" class="form-control" value="{{old('email')}}" required>
                  </div>

                  <div>
                    <p><b>Password</b></p>
                    <input type="password" name="password" placeholder="Password" class="form-control" value="{{old('password')}}" required>
                  </div>

                  <div>
                    <p><b>Confirm Password</b></p>
                    <input type="password" name="password2" placeholder="Confirm Password" class="form-control" value="{{old('password2')}}" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>

          <table class="table table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Added at</th>
                <th>Commands</th>
              </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                  <tr>
                    <td>{{ucwords($user->name)}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{ucwords($user->role)}}</td>
                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M-d-Y')}}</td>
                    <td>

                      <button type="button" class="btn btn-default btn-sm {{($user->id === 1 || $user->role === 'super') ? 'hidden' : ''}}" data-toggle="modal" data-target="#changeRights{{$user->id}}">
                        <span class="glyphicon glyphicon-edit"></span> Change to Super admin
                      </button>

                      <div class="modal fade" id="changeRights{{$user->id}}" class="viewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel"></h4>
                            </div>
                            <form action="/change-rights/{{$user->id}}" method="post">
                              {{csrf_field()}} {{method_field('PUT')}}
                              <div class="modal-body">
                                <h3>Change rights of {{$user->name}} to Super Admin?</h3>
                                <input type="hidden" name="rights" value="super">
                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Confirm</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>

                      <button type="button" class="btn btn-basic btn-sm {{($user->id === 1 || $user->role === 'admin') ? 'hidden' : ''}}" data-toggle="modal" data-target="#changeRightstoAdmin{{$user->id}}">
                        <span class="glyphicon glyphicon-edit"></span> Change to Admin
                      </button>

                      <div class="modal fade" id="changeRightstoAdmin{{$user->id}}" class="viewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel"></h4>
                            </div>
                            <form action="/change-rights/{{$user->id}}" method="post">
                              {{csrf_field()}} {{method_field('PUT')}}
                              <div class="modal-body">
                                <h3>Change rights of {{$user->name}} to Admin?</h3>
                                <input type="hidden" name="rights" value="admin">
                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Confirm</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>

                      <button type="button" class="btn btn-danger btn-sm {{(Auth::user()->role !== 'super' || Auth::user()->id === $user->id || $user->id === 1 ) ? 'hidden' : ''}}" data-toggle="modal" data-target="#removeAccount{{$user->id}}">
                        <span class="glyphicon glyphicon-remove"></span> Remove Account
                      </button>

                      <div class="modal fade" id="removeAccount{{$user->id}}" class="viewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel"></h4>
                            </div>
                            <form action="/delete-account/{{$user->id}}" method="post">
                              {{csrf_field()}} {{method_field('DELETE')}}
                              <div class="modal-body">
                                <h3>Remove {{$user->name}}?</h3>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Confirm</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>


                    </td>
                  </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection
