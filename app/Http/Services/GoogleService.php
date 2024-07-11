<?php

namespace App\Http\Services;

use App\Models\Events;
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



}
