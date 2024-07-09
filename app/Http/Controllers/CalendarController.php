<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Session;


class CalendarController extends Controller
{
   public function addEvent(Request $request){
       $client = new Google_Client();
      // dd($client->fetchAccessTokenWithAuthCode(request()->get('code')));
       $id = auth()->user()->id;
       $token = User::where('id',auth()->user()->id)->get()->toArray()[0];
      // dd($token['access_token']);
       $client->setAccessToken($token['access_token']);
       $calendar = new Google_Service_Calendar($client);

//       dd($request->input('title'));
       $event = new Google_Service_Calendar_Event([
           'summary' => $request->input('title'),
           'description' => $request->input('description'),
           'start' => [
               'dateTime' => $request->input('start'),
               'timeZone' => 'America/Los_Angeles',
           ],
           'end' => [
               'dateTime' => $request->input('end'),
               'timeZone' => 'America/Los_Angeles',
           ],
       ]);

       $calendarId = 'primary';
      // dd($calendarService->events);
       $event = $calendar->events->insert($calendarId, $event);

       return redirect()->back()->with('success', 'Событие успешно добавлено в Google Календарь!');
   }
}
