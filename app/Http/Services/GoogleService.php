<?php

namespace App\Http\Services;

use App\Models\Events;
use App\Models\User;
use Carbon\Carbon;

class GoogleService{
    public function addEventToDatabase($request,$event_id){
        $eventDb = Events::updateOrCreate([
            'user_id'=> auth()->user()->id,
            'event_id' =>$event_id,
            'summary' => $request->input('title'),
            'description' => $request->input('description'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
        ]);
    }

    public function timeStart($start){
        $carbonTimeStart = Carbon::parse($start)->subHours(7);
        $formattedTimeStart = $carbonTimeStart->format('Y-m-d\TH:i:s') . '-07:00';
        return $formattedTimeStart;
    }

    public function timeEnd($end){
        $carbonTimeEnd = Carbon::parse($end)->subHours(7);
        $formattedTimeEnd = $carbonTimeEnd->format('Y-m-d\TH:i:s') . '-07:00';
        return $formattedTimeEnd;
    }



    public function refreshToken(){
        $token = User::where('id',auth()->user()->id)->get()->toArray()[0];
        $refreshToken = $token['refresh_token'];
        return $refreshToken;
    }

    public function updateEventToDatabase($request, $event_id){
        $eventId = Events::where('event_id',$event_id)->first();
        $eventDb = Events::find($eventId->id);
        $eventDb->summary = $request->input('title');
        $eventDb->description = $request->input('description');
        $eventDb->start = $request->input('start');
        $eventDb->end = $request->input('end');
        $eventDb->save();
    }

}
