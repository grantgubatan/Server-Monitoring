<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ingram Servermon</title>


    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootswatch.css') }}" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

</head>
<body id="{{(Auth::check()) ? : 'particles-js'}}">
  <div class="testdropdown">

  </div>
    <div id="app">
        @include('partials.nav')

        @yield('content')

        @include('partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

    <script>
      particlesJS.load('particles-js', "{{asset('js/particles.json')}}", function(){
        console.log('particles.json loaded...');
      });
    </script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


      <script>
          @if(Session::has('message'))
              var type="{{Session::get('alert-type','info')}}";


              switch(type){
                  case 'info':
                      toastr.options = {
                        "positionClass": "toast-bottom-right",

                      }
                       toastr.info("{{ Session::get('message') }}");
                       break;
                  case 'success':
                      toastr.options = {
                        "positionClass": "toast-bottom-right",

                      }
                      toastr.success("{{ Session::get('message') }}");
                      break;
                  case 'warning':
                      toastr.options = {
                        "positionClass": "toast-bottom-right",

                      }
                      toastr.warning("{{ Session::get('message') }}");
                      break;
                  case 'error':
                      toastr.options = {
                        "positionClass": "toast-bottom-right",

                      }
                      toastr.error("{{ Session::get('message') }}");
                      break;
              }
          @endif

          $(document).ready(function() {

            $('#servers').on('click', function() {

              // Add loading state
              $('.testdropdown').html('Pinging servers this may take a few minutes please wait ...');

              // Set request
              var request = $.get('/servers');

              // When it's done
              request.done(function(response) {
                console.log(response);
              });


            });

            $('#refresh').on('click', function() {

              // Add loading state
              $('.testdropdown').html('Pinging all of the servers this may take a few minutes please wait ...');

              // Set request
              var request = $.get('/servers');

              // When it's done
              request.done(function(response) {
                console.log(response);
              });


            });

          });
      </script>
</body>
</html>
