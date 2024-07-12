<!DOCTYPE html>
<html>
<head>
    <title>events</title>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
</head>
<body>
<h3>{{session('success')}}</h3>
<form action="{{route('edit-event')}}" method="post">
    <input type="hidden" name="event_id" value="{{$id}}">
    <input type="text" name="title"><br>
    <input type="text" name="description"><br>
    <input type="datetime-local" name="start"><br>
    <input type="datetime-local" name="end">
    <button>add event</button>
</form>

</body>
</html>


