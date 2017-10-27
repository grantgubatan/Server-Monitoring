<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Ingram Servermon</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                margin: 0;
                padding: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                color: white;
                position: absolute;
                width: 800px;
                z-index: 15;
  top: 50%;
  left: 50%;
  margin: -100px 0 0 -150px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            img {
                width: 100%;
                padding: 0;
                display: inline-block;
                margin: 0 auto;
                vertical-align: middle;
                max-width: 203px;
                max-height: 65%;
                border: 0;
                z-index: 9999;
                padding-top: 50px;
                margin-left: 100px;

            }
            #particles-js
            {
              background: black;
            }
        </style>

        <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    </head>
    <body id="particles-js">
      <img src="{{ url('/') }}/ingram.png" alt="">
        <div class="">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif
        </div>

        <div class="title m-b-md">
              <p>Servermon</p>
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
          </script>
    </body>
</html>
