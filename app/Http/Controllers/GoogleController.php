<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Google_Client;
use Google_Service_Oauth2;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(route('google-callback'));
        $this->client->setScopes([
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
          //  'https://www.googleapis.com/auth/calendar.events'
        ]);

    }

    public  function home()
    {
        return view('index');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
    public function redirect(){
        return redirect()->to($this->client->createAuthUrl());
    }


    public function callback(Request $request){

        try {
            $this->client->fetchAccessTokenWithAuthCode(request()->get('code'));

            $oauth2 = new Google_Service_Oauth2($this->client);
            $userInfo = $oauth2->userinfo->get();
           // dd($userInfo->email);
            $accessToken = $this->client->getAccessToken();

            $save = User::updateOrCreate([
                'google_id' => $userInfo->id,
            ],[
                'name' => $userInfo->name,
                'email' => $userInfo->email,
                'password' => Hash::make($userInfo->name.'@'.$userInfo->id),
                'access_token' => $accessToken['access_token'],

            ]);



            Auth::login($save);
            return redirect('/home');


        }catch (\Throwable $th){
            throw $th;
        }

    }

}
