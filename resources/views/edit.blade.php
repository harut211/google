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
    <input type="hidden" name="event_id" value="{{$id}}" >
    <input type="text" name="title" placeholder="New Summary"><br>
    <input type="text" name="description" placeholder="New Description"><br>
    <input type="datetime-local" name="start" placeholder="Start time"><br>
    <input type="datetime-local" name="end" placeholder="End time">
    <button>add event</button>
</form>
</body>
</html>


