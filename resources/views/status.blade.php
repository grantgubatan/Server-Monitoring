@extends('layouts.app')

@section('content')
@include('partials.sidebar')
<div class="" style="margin-left:300px; width:75%">
    <div class="row">
      <h2>Status</h2><hr>
      <div class="row">
        <form action="{{url('/servers')}}" method="post" class="col-sm-2">
          <!-- Button trigger modal -->
        <button id="lol" type="button" class="btn btn-basic btn-md" data-toggle="modal" data-target="#myModal">
          <span class="glyphicon glyphicon-plus"></span>  Add Server
        </button>

        </form>

        <form action="{{url('/servers')}}" method="post" class="col-sm-2">
          {{csrf_field()}} {{method_field('PUT')}}
          <button type="submit" href="#" class="btn btn-default btn-md" id="refresh">
            <span class="glyphicon glyphicon-refresh"></span>  Ping all Servers
          </button>
        </form>
      </div>
      <div>
        <table class="table table-striped" id="content">
          <thead>
            <tr>
              <th></th>
              <th>Label</th>
              <th>IP Address</th>
              <th>Status</th>
              <th>Current Average Latency</th>
              <th>Peak Latency</th>
              <th>Peak Time</th>
              <th>Last Update</th>
              <th>Commands</th>
            </tr>
          </thead>
          <tbody>
            @forelse($servers as $server)
            <tr>
              <td><span class="fa fa-circle" style="{{($server->status) ? 'color:#55b287' : 'color:#ff6a51'}}"></span></td>
              <td>{{$server->name}}</td>
              <td>{{$server->ip}}</td>
              <td class="{{($server->status) ? 'online' : 'offline'}}">{{($server->status) ? "Online" : "Offline"}}</td>
              <td class="{{((int) $server->latency > 220) ? 'offline' : 'online'}} {{(!$server->status) ? 'offline' : 'None'}}">{{($server->status) ? $server->latency : 'None'}}</td>
              <td class="{{(!$server->status) ? 'offline' : 'blue'}}">{{($server->status) ? $server->peak : 'None'}}</td>
              <td class="{{(!$server->status) ? 'offline' : 'yellow'}}">{{($server->status) ? $server->peak_time->format('M-d-y | g:i a') : 'None'}}</td>
              <td>{{$server->last_update->diffForHumans()}}</td>
              <td>
                <form action="/ping-server/{{$server->id}}" method="POST">
                  {{csrf_field()}} {{method_field('PUT')}}
                  <button type="submit" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-refresh"></span> Ping Server
                  </button>
                </form>
              </td>
            </tr>
            @empty
            @endforelse
          </tbody>
        </table>
        <center>{{$servers->links()}}</center>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" id="myModal" role="document">
          <div class="modal-content">
            <form action="/add-server" method="post">
              {{csrf_field()}}
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Server</h4>
              </div>
              <div class="modal-body">
                <div>
                    <label for="name">Server Name</label>
                    <input type="text" name="name" placeholder="Server Name" class="form-control" required>
                </div>

                <div>
                    <label for="name">IP Address</label>
                    <input type="text" name="ip" placeholder="IP Address" class="form-control" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add Server</button>
              </div>
            </div>
            </form>
        </div>
      </div>
    </div>


</div>


    <script type="text/javascript">
        $(document).ready(function()
        {

          function loadlink()
          {
            console.log("refreshed");
            //$("#content").load(location.href + " #content");
            $("#content").load(location.href + " #content>*");
          }

          function updateServers()
          {
              $.ajax({
                headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/servers",
                type: "PUT",
                data: ""
             });
              console.log("UPDATED");

          }

            loadlink(); // This will run on page load
            setInterval(function(){
            loadlink() // this will run after every 5 seconds
          }, 1000);


        });

        document.onreadystatechange = function () {
          var state = document.readyState
          if (state == 'interactive') {
               document.getElementById('contents').style.visibility="hidden";
          } else if (state == 'complete') {
              setTimeout(function(){
                 document.getElementById('interactive');
                 document.getElementById('load').style.visibility="hidden";
                 document.getElementById('contents').style.visibility="visible";
              },1000);
          }
        }
    </script>
@endsection
