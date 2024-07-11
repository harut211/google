<?php

namespace App\Http\Controllers;
use App\Http\Services\GoogleService;
use App\Models\Events;
use App\Models\User;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Google_Service_Oauth2;
use Carbon\Carbon;
class GoogleController extends Controller
{
    protected $client;
    protected $googleService;

    public function __construct(GoogleService $googleService)
    {
        $this->googleService=$googleService;
        $this->client = new Google_Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(route('google-callback'));
        $this->client->setScopes([
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/calendar.events'
        ]);
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');

    }

    public  function home()
    {
        $events = Events::all()->toArray();
        return view('index', compact('events'));
    }

    public function editPage(Request $request)
    {
        $id = $request->input('id');

        return view('edit',compact('id'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
    public function redirect(){
        $this->client->setAccessType('offline');
        return redirect()->to($this->client->createAuthUrl());
    }


    public function callback(Request $request){

        try {
            $this->client->fetchAccessTokenWithAuthCode(request()->get('code'));
            $oauth2 = new Google_Service_Oauth2($this->client);
            $userInfo = $oauth2->userinfo->get();

            $accessToken = $this->client->getAccessToken();
            $refreshToken = $this->client->getRefreshToken();


            $save = User::updateOrCreate([
                'google_id' => $userInfo->id,
            ],[
                'name' => $userInfo->name,
                'email' => $userInfo->email,
                'password' => Hash::make($userInfo->name.'@'.$userInfo->id),
                'access_token' => $accessToken['access_token'],
                'refresh_token' => $refreshToken,
            ]);


            Auth::login($save);
            return redirect('/home');


        }catch (\Throwable $th){
            throw $th;
        }

    }

    public function addEvent(Request $request)
    {

        $start = $this->googleService->timeStart($request->input('start'));

        $end = $this->googleService->timeEnd($request->input('end'));

        $oauth2 = new Google_Service_Oauth2($this->client);

        $token = User::where('id',auth()->user()->id)->get()->toArray()[0];
        $refreshToken = $token['refresh_token'];

        $client = $this->client;

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            $newAccessToken = $client->getAccessToken();
            $client->setAccessToken($newAccessToken);
        }

        $calendar = new Google_Service_Calendar($client);

        $event = new Google_Service_Calendar_Event([
            'summary' => $request->input('title'),
            'description' => $request->input('description'),
            'start' => [
                'dateTime' =>  $start,
                'timeZone' => 'Asia/Yerevan',
            ],
            'end' => [
                'dateTime' => $end,
                'timeZone' => 'Asia/Yerevan',
            ],
        ]);

        $calendarId = 'primary';
        $event = $calendar->events->insert($calendarId, $event);

        $eventDb = $this->googleService->addEventToDatabase($request, $event->id);

        return redirect()->back()->with(['success'=>'Your event has been added.']);

    }


    public function delEvent(Request $request){
       $id = $request->input('val');

        $oauth2 = new Google_Service_Oauth2($this->client);

        $token = User::where('id',auth()->user()->id)->get()->toArray()[0];
        $refreshToken = $token['refresh_token'];

        $client = $this->client;

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            $newAccessToken = $client->getAccessToken();
            $client->setAccessToken($newAccessToken);
        }

        $calendar = new Google_Service_Calendar($client);
        try {
            $calendar->events->delete('primary',$id);
            $event = Events::where('event_id',  $id)->delete();
        }catch (\Throwable $th){
            echo "Denied";
            throw $th;
        }


    }


    public function editEvent(Request $request){

        $oauth2 = new Google_Service_Oauth2($this->client);

        $token = User::where('id',auth()->user()->id)->get()->toArray()[0];
        $refreshToken = $token['refresh_token'];

        $client = $this->client;

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            $newAccessToken = $client->getAccessToken();
            $client->setAccessToken($newAccessToken);
        }

        $calendar = new Google_Service_Calendar($client);

        $newStart = new \Google\Service\Calendar\EventDateTime();
        $newEnd = new \Google\Service\Calendar\EventDateTime();
        $eventId = $request->input('event_id');

        $start = $this->googleService->timeStart($request->input('start'));
        $end = $this->googleService->timeEnd($request->input('end'));


        $event = $calendar->events->get('primary', $eventId);

        $event->setSummary($request->input('title'));
        $event->setDescription($request->input('description'));
        $newStart->setDateTime($start);
        $event->setStart($newStart);
        $newEnd->setDateTime($end);
        $event->setEnd($newEnd);

        $calendar->events->update('primary',$eventId, $event);

        return redirect()->back()->with(['success'=>'Your event has been updated.']);

    }

}
