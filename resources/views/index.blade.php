<h2>
    <a href="{{route('logout')}}">logout</a>
</h2>

<form action="{{route('calendar-event')}}" method="post">
    <input type="text" name="title"><br>
    <input type="text" name="description"><br>
    <input type="date" name="start"><br>
    <input type="date" name="end">
    <button>add event</button>
</form>

<b>
    {{session('google_token')}}
</b>
<span>
    {{dd(\Illuminate\Support\Facades\Auth::user())}}
</span>


