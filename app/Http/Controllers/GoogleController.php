<?php

namespace App\Http\Controllers;

use App\Http\Services\GoogleService;
use App\Models\Events;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Auth;
use Google_Service_Oauth2;

class GoogleController extends Controller
{
    protected $client;
    protected $googleService;

    public function __construct(GoogleService $googleService)
    {
        $this->googleService = $googleService;
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

    public function home()
    {
        $events = Events::all()->toArray();
        return view('index', compact('events'));
    }

    public function editPage(Request $request)
    {
        $event = Events::find($request->input('id'));
        return view('edit', compact('event'));
    }


    public function redirect()
    {
        $this->client->setAccessType('offline');
        return redirect()->to($this->client->createAuthUrl());
    }


    public function callback(Request $request)
    {
        try {
            $this->client->fetchAccessTokenWithAuthCode(request()->get('code'));
            $oauth2 = new Google_Service_Oauth2($this->client);
            $userInfo = $oauth2->userinfo->get();

            $accessToken = $this->client->getAccessToken();

            $refreshToken = $this->client->getRefreshToken();
            $save = $this->googleService->saveUser($userInfo, $accessToken, $refreshToken);

            Auth::login($save);
            return redirect('/home');

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function addEvent(Request $request)
    {
        $start = $this->googleService->timeStart($request->input('start'));
        $end = $this->googleService->timeEnd($request->input('end'));

        $client = $this->client;

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($this->googleService->refreshToken());
            $newAccessToken = $client->getAccessToken();
            $client->setAccessToken($newAccessToken);
        }

        $calendar = new Google_Service_Calendar($client);

        $event = $this->googleService->addEvent($request, $start, $end);

        $calendarId = 'primary';
        $event = $calendar->events->insert($calendarId, $event);

        $this->googleService->addEventToDatabase($request, $event->id);

        return redirect()->back()->with(['success' => 'Your event has been added.']);

    }

    public function delEvent(Request $request)
    {
        $id = $request->input('val');

        $client = $this->client;

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($this->googleService->refreshToken());
            $newAccessToken = $client->getAccessToken();
            $client->setAccessToken($newAccessToken);
        }

        $calendar = new Google_Service_Calendar($client);
        try {
            $calendar->events->delete('primary', $id);
            Events::where('event_id', $id)->delete();
        } catch (\Throwable $th) {
            echo "Denied";
            throw $th;
        }

    }


    public function editEvent(Request $request)
    {
        $client = $this->client;

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($this->googleService->refreshToken());
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

        $calendar->events->update('primary', $eventId, $event);

        $this->googleService->updateEventToDatabase($request, $eventId);

        return redirect()->back()->with(['success' => 'Your event has been updated.']);

    }

}
