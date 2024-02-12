<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Img;
use Validator;

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
        $today = new \DateTime(date('Y-M-j'));
        $start = date_sub($today, new \DateInterval('P11M'));
        $today = new \DateTime(date('Y-M-j'));
        return view('welcome', compact('today', 'start'));
    }

    public function showProfile()
    {
        return view('profile');
    }

    public function showPassword()
    {
        return view('password');
    }

    public function postPicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'picture' => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->with('global-warning', 'The picture is not valid.');
        }

        $user = User::find(Auth::id());

        $image = Img::make($request->file('picture')->getRealPath());

        $image->fit(200, 200);

        $dir = public_path('images/profiles/');

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $current = $user->photo ? $dir . $user->photo : null;

        if ($current) {
            if (file_exists($current)) {
                unlink($current);
            }
        }

        $filename = time() . '.jpg';

        $image->save($dir . $filename);

        $user->photo = $filename;

        $user->save();

        return redirect()
            ->back()
            ->with('global-success', 'Profile picture updated');
    }

    public function postProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $user = User::find(Auth::id());

        $user->name = $request->input('name');

        $user->save();

        return redirect()
            ->back()
            ->with('global-success', 'Profile Updated');
    }

    public function postPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $user = [
            'email' => Auth::user()->email,
            'password' => $request->input('old_password'),
        ];

        if (!Auth::validate($user)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['old_password' => 'Old password failed authentication'])
                ->with('global-danger', 'Authentication failed. Please verify your old password');
        }

        $user = User::find(Auth::id());

        $user->save();

        return redirect()
            ->back()
            ->with('global-success', 'Password changed');
    }

    public function sendSms(\App\Services\SmsGatewayContract $smsGateway)
    {
        // dd($smsGateway->send('254735388704,254711347184,254777347184,254790584171,254722248525',\Illuminate\Foundation\Inspiring::quote()));
        // "[{"status":"200","response":"success","message_id":105844447,"recipient":"254711347184"},{"status":"200","response":"success","message_id":105844448,"recipient":"254790584171"}] ◀"

        // dd($smsGateway->status(105876472));
        // dd($smsGateway->balance());
    }
}
