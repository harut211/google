<?php

namespace App\Http\Services;

use App\Models\Events;
use App\Models\User;
use Carbon\Carbon;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Hash;

class GoogleService{
    public function addEventToDatabase($request, $event_id)
    {
        $eventDb = Events::updateOrCreate([
            'user_id'=> auth()->user()->id,
            'event_id' =>$event_id,
            'summary' => $request->input('title'),
            'description' => $request->input('description'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
        ]);
    }

    public function timeStart($start)
    {
        $carbonTimeStart = Carbon::parse($start)->subHours(7);
        $formattedTimeStart = $carbonTimeStart->format('Y-m-d\TH:i:s') . '-03:00';
        return $formattedTimeStart;
    }

    public function timeEnd($end)
    {
        $carbonTimeEnd = Carbon::parse($end)->subHours(7);
        $formattedTimeEnd = $carbonTimeEnd->format('Y-m-d\TH:i:s') . '-03:00';
        return $formattedTimeEnd;
    }



    public function refreshToken()
    {
        $refreshToken = auth()->user()->refresh_token;
        return $refreshToken;
    }

    public function updateEventToDatabase($request, $event_id)
    {
        $eventId = Events::where('event_id',$event_id)->first();
        $eventDb = Events::find($eventId->id);
        $eventDb->summary = $request->input('title');
        $eventDb->description = $request->input('description');
        $eventDb->start = $request->input('start');
        $eventDb->end = $request->input('end');
        $eventDb->save();
    }


    public function addEvent($request, $start, $end)
    {
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
        return $event;

    }

    public function saveUser($userInfo, $accessToken, $refreshToken)
    {
        $save = User::updateOrCreate([
            'google_id' => $userInfo->id,
        ],
            array_filter([
                'name' => $userInfo->name,
                'email' => $userInfo->email,
                'password' => Hash::make($userInfo->name.'@'.$userInfo->id),
                'access_token' => $accessToken['access_token'],
                'refresh_token' => $refreshToken,
            ]));
        return $save;
    }

}
