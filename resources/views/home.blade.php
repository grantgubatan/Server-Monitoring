@extends('layouts.app')

@section('content')
@include('partials.sidebar')
<div class="container">
  <div class="jumbotron">
    <div class="text-center">
      <h1>Server Monitoring <br><small>v1.1</small></h1>
      <p>Local IS Department</p>
    </div>

    <div class="row">
      <h3>Want a quick ping?</h3>
      <form action="{{url('/home')}}" method="post">
        {{csrf_field()}}
        <input type="text" name="ip" class="form-control" placeholder="Enter hostname, url or ip address">
      </form>
    </div>

    @if($server->name !== null)
    <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Status</th>
        <th>Average Latency</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{$server->name}}</td>
        <td class="{{($server->status) ? 'online' : 'offline'}}">{{($server->status) ? "Online" : "Offline"}}</td>
        <td>{{($server->status) ? $server->latency : 'None'}}</td>
      </tr>
    </tbody>
  </table>
    @endif
  </div>

</div>
@endsection
