<!DOCTYPE html>
<html>
<head>
    <title>Update page</title>
</head>
<body>
<h2>Update your event</h2>
<h2>
    <a href="{{route('home')}}" >Go back</a>
</h2>
<h3>{{session('success')}}</h3>
<form action="{{route('edit-event')}}" method="post">
    <input type="hidden" name="event_id" value="{{$event->event_id}}" >
    <input type="text" name="title" value="{{$event->summary}}"><br>
    <input type="text" name="description" value="{{$event->description}}"><br>
    <input type="datetime-local" name="start" value="{{$event->start}}"><br>
    <input type="datetime-local" name="end" value="{{$event->end}}">
    <button>add event</button>
</form>
</body>
</html>


