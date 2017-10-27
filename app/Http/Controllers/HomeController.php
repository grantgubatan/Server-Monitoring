<?php

namespace App\Http\Controllers;
use App\Server;
use App\User;
use Redirect;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $server = new Server;
        return view('home', compact('server'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function editProfile(Request $request)
    {
        if(User::where('email', '=', $request->email)->count() > 0)
        {
          $notification = array(
                   'message' => 'Email already registered',
                   'alert-type' => 'error'
               );
          return Redirect::back()->withInput(Input::all())->with($notification);
        }
        else
        {
          $user = Auth::user();
          $user->name = $request->name;
          $user->email = $request->email;
          $user->save();

          $notification = array(
                   'message' => 'Profile Updated',
                   'alert-type' => 'info'
               );
          return Redirect::back()->withInput(Input::all())->with($notification);
        }
    }

    public function pingSearch(Request $request)
    {

      $server = new Server;

      $server->name = $request->ip;

      exec("ping -w 60 ". $request->ip, $output, $result);

      if($result != 0)
      {
        $server->status = 0;
        $server->latency = "None";
      }
      else
      {
        $server->status = 1;
        $server->latency = $this->pingDomain($request->ip);
      }

      $server->last_update = date("Y-m-d H:i:s");

      $notification = array(
               'message' => 'Server Status Updated',
               'alert-type' => 'success'
           );

      return view('/home', compact('server'));
    }

    public function status()
    {
      $servers = Server::paginate(10);

      return view('status', compact('servers'));
    }

    public function servers()
    {
        $servers = Server::paginate(10);

        return view('servers', compact('servers'));
    }

    public function updateServers()
    {

      $servers = Server::all();
      ini_set('max_execution_time', 300);

      foreach ($servers as $server)
      {
          //$server->firewall = @fsockopen($server->ip, 80, $errno, $errstr, 30);

          exec("ping -w 60 " . $server->ip, $output, $result);

          if($result != 0)
          {
            $server->status = 0;
            $server->latency = "None";
          }
          else
          {
            $last_latency = $server->latency;
            $current_latency = $this->pingDomain($server->ip);

            if($current_latency > $last_latency)
            {
              $server->peak = $current_latency;
              $server->peak_time = date('Y-m-d H:i:s');
            }

            $server->status = 1;
            $server->latency = $current_latency;
          }

          $server->last_update = date('Y-m-d H:i:s');
          $server->save();
      }

        $notification = array(
                 'message' => 'All server status updated',
                 'alert-type' => 'success'
             );

        return redirect('/status')->with($notification);

        /*An array of hosts to check
             $servers = Server::all();
             $addresses = array();
             $online_ips = array();
             $offline_ips = array();

             foreach($servers as $server)
             {
               $addresses[] = $server->ip;
             }
             // The TCP port to test
             $testport = 80;
             // The length of time in seconds to allow host to respond
             $waitTimeout = 5;

             // Loop addresses and create a socket for each
             $socks = array();
             foreach ($addresses as $address)
             {
               if (!$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))

                // echo "Could not create socket for $address <br>";
                 continue;

               else

                 //echo "Created socket for $address <br>";

               socket_set_nonblock($sock);
               // Suppress the error here, it will always throw a warning because the
               // socket is in non blocking mode
               @socket_connect($sock, $address, $testport);
               $socks[$address] = $sock;
             }

             // Sleep to allow sockets to respond
             // In theory you could just pass $waitTimeout to socket_select() but this can
             // be buggy with non blocking sockets
             sleep($waitTimeout);

             // Check the sockets that have connected
             $w = $socks;
             $r = $e = NULL;
             $count = @socket_select($r, $w, $e, 0);
             //echo "$count sockets connected successfully <br>";

             // Loop connected sockets and retrieve the addresses that connected
             foreach ($w as $sock)
             {
               $address = array_search($sock, $socks);

               $online_ips[] = $address;

               @socket_close($sock);
             }


             foreach ($servers as $server)
             {
               if(in_array($server->ip, $online_ips))
               {
                 $server->status = 1;
                 $server->last_update = date("Y-m-d H:i:s");
                 $server->save();
               }
               else
               {
                 $server->status = 0;
                 $server->last_update = date("Y-m-d H:i:s");
                 $server->save();
               }
             }

             $notification = array(
                      'message' => 'All server status updated',
                      'alert-type' => 'success'
                  );

             return redirect('/status')->with($notification);
             */

    }

    public function pingServer(Request $request, $id)
    {
      $server = Server::findOrFail($id);

      exec("ping -w 60 ". $server->ip, $output, $result);

      if($result != 0)
      {
        $server->status = 0;
        $server->latency = "None";
      }
      else
      {
        $last_latency = $server->latency;
        $current_latency = $this->pingDomain($server->ip);

        if($current_latency > $last_latency)
        {
          $server->peak = $current_latency;
          $server->peak_time = date('Y-m-d H:i:s');
        }

        $server->status = 1;
        $server->latency = $current_latency;
      }


      $server->last_update = date("Y-m-d H:i:s");

      $server->save();

      $notification = array(
               'message' => 'Server Status Updated',
               'alert-type' => 'success'
           );

      return Redirect::back()->with($notification);
    }

    public function deleteServer(Request $request, $id)
    {

      $server = Server::findOrFail($id);

      $server->delete();

      $notification = array(
               'message' => 'Server removed',
               'alert-type' => 'info'
           );

      return redirect('/servers')->with($notification);
    }

    public function admins()
    {
        $users = User::paginate(10);
        return view('admins', compact('users'));
    }

    public function help()
    {
        return view('help');
    }

    public function addServer(Request $request)
    {

       $server = new Server;

       $server->name = $request->name;
       $server->ip = $request->ip;

       exec("ping -w 60 " . $server->ip, $output, $result);

       if($result != 0)
       {
         $server->status = 0;
         $server->latency = "None";
       }
       else
       {
         $last_latency = $server->latency;
         $current_latency = $this->pingDomain($server->ip);

         if($current_latency > $last_latency)
         {
           $server->peak = $current_latency;
           $server->peak_time = date('Y-m-d H:i:s');
         }

         $server->status = 1;
         $server->latency = $current_latency;
       }


       $server->last_update = date("Y-m-d H:i:s");

       $server->save();

      $notification = array(
               'message' => 'Server Added',
               'alert-type' => 'success'
           );
      return redirect('/status')->with($notification);
    }

    public function deleteAccount(Request $request, $id)
    {

      $user = User::findOrFail($id);

      $user->delete();

      $notification = array(
               'message' => 'Account removed',
               'alert-type' => 'info'
           );

      return redirect('/admins')->with($notification);
    }

    public function updateServer(Request $request, $id)
    {
      $server = Server::findOrFail($id);
      $server->name = $request->name;
      $server->ip = $request->ip;

      exec("ping -w 60 " . $server->ip, $output, $result);

      if($result != 0)
      {
        $server->status = 0;
        $server->latency = "None";
      }
      else
      {
        if($server->latency === "None")
        {
          $last_latency = 0;
        }

        $last_latency = $server->latency;
        $current_latency = $this->pingDomain($server->ip);

        if($current_latency > $last_latency)
        {
          $server->peak = $current_latency;
          $server->peak_time = date('Y-m-d H:i:s');
        }

        $server->status = 1;
        $server->latency = $current_latency;
      }


      $server->last_update = date("Y-m-d H:i:s");


      $server->save();

      $notification = array(
               'message' => 'Server Updated',
               'alert-type' => 'success'
           );
      return redirect('/servers')->with($notification);
    }

    public function addAccount(Request $request)
    {
      $user = new User;

      if (User::where('email', '=', $request->email)->count() > 0)
      {
        $notification = array(
                 'message' => 'Email Already Registered',
                 'alert-type' => 'error'
             );
        return Redirect::back()->withInput(Input::all())->with($notification);
      }
      else
      {
            if($request->password !== $request->password2)
            {
              $notification = array(
                       'message' => 'Passwords did not match',
                       'alert-type' => 'error'
                   );
              return Redirect::back()->withInput(Input::all())->with($notification);
            }
            else
            {
              User::create([
                  'name' => $request->name,
                  'email' => $request->email,
                  'password' => bcrypt($request->password2),
              ]);

              $notification = array(
                       'message' => 'Account Added',
                       'alert-type' => 'info'
                   );
              return redirect('/admins')->with($notification);
            }


      }

    }

    public function pingDomain($domain)
    {
      $ping = exec("ping $domain");
      $pingTime = explode(',',trim($ping));
      $time = explode("=",trim($pingTime[2]));
      return $time[1];
    }

    public function changeRights(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->role = $request->rights;

        $user->save();

        $notification = array(
                 'message' => 'Account updated',
                 'alert-type' => 'success'
             );
        return redirect('/admins')->with($notification);

    }

    public function updateServerScheduler()
    {
      $servers = Server::all();
      ini_set('max_execution_time', 300);

      foreach ($servers as $server)
      {
          //$server->firewall = @fsockopen($server->ip, 80, $errno, $errstr, 30);

          exec("ping -w 60 " . $server->ip, $output, $result);

          if($result != 0)
          {
            $server->status = 0;
            $server->latency = "None";
          }
          else
          {
            $last_latency = $server->latency;
            $current_latency = $this->pingDomain($server->ip);

            if($current_latency > $last_latency)
            {
              $server->peak = $current_latency;
              $server->peak_time = date('Y-m-d H:i:s');
            }

            $server->status = 1;
            $server->latency = $current_latency;
          }

          $server->last_update = date('Y-m-d H:i:s');
          $server->save();
      }
    }

}
