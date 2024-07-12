<!DOCTYPE html>
<html>
<head>
    <title>events</title>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
</head>
<body>

<h2>Create Event</h2>
    <form action="{{route('calendar-event')}}" method="post">
        <input type="text" name="title"><br>
        <input type="text" name="description"><br>
        <input type="datetime-local" name="start"><br>
        <input type="datetime-local" name="end">
        <button>add event</button>
    </form>

<b>
    {{session('success')}}
</b>
        <span  >
            @if(!empty($events))
                @foreach($events as $event)
                    <div style="display: block; background-color: #c3cad4; margin: 20px">
                        <h4>Summary --  {{$event['summary']}}</h4>
                        <span>Description-- {{$event['description']}}</span><br>
                        <span>Start time-- {{$event['start']}}</span><br>
                        <span>End time-- {{$event['end']}}</span><br>
                        <button id="del-event" value="{{$event['event_id']}}" >Delete</button>
                         <a href="{{route('edit-page',['id'=> $event['event_id'] ])}}">Edit</a>
                    </div>
                @endforeach
            @endif
</span>

    <h2>
        <a href="{{route('logout')}}">logout</a>
    </h2>

    <script type="module">

 $(function (){

     $('#del-event').on('click',function (){

         let val = $(this).val();
         $.ajax({
             url: "/del-event",
             data: {
                 'val':val,
             },
             success: function (result) {
               alert('Your event deleted');
               location.reload();
             }
         })
     });

 })
</script>
</body>
</html>


