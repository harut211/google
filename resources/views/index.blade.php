<!DOCTYPE html>
<html>
<head>
    <title>events</title>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    @vite(['resources/scss/app.scss'])
</head>
<body>


<div class="card">
    <div class="card-body ">
        <div class="container-lg d-flex justify-content-center">
            <div class="row d-flex justify-content-center">
                <h2>Create Event</h2>
                @if(!empty(session('success')))
                    <div class="alert alert-success"> {{session('success')}}</div>
                @endif
                <form action="{{route('calendar-event')}}" method="post">
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="formGroupExampleInput"
                               placeholder="Event Title">
                        <div style="color: red">
                            {{$errors->first('title')}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Content</label>
                        <input type="text" name="description" class="form-control" id="formGroupExampleInput2"
                               placeholder="Event Content">
                        <div style="color: red">
                            {{$errors->first('description')}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="datetime-local" name="start" class="form-control"
                               style="background-color: #f8fafc">
                        <div style="color: red">
                            {{$errors->first('start')}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="datetime-local" name="end" class="form-control" style="background-color: #f8fafc">
                    </div>
                    <div style="color: red">
                        {{$errors->first('end')}}
                    </div>
                    <button class="btn btn-primary justify-content-center">Add Event</button>
                </form>
            </div>
        </div>
    </div>
</div>
<b>

</b>

<div class="card">
    <div class="card-body ">
        <div class="container-lg d-flex justify-content-center">
            <div class="row d-flex justify-content-center">
                @if(!empty($events))
                       @foreach($events as $event)
                            <div class="alert alert-primary">
                                <h4>Summary --  {{$event['summary']}}</h4>
                                <span>Description-- {{$event['description']}}</span><br>
                                <span>Start time-- {{$event['start']}}</span><br>
                                <span>End time-- {{$event['end']}}</span><br>
                                <button  class="btn btn-danger" id="del-event" value="{{$event['event_id']}}">Delete</button>
                                 <button class="btn btn-warning">
                                     <a href="{{route('edit-page',['id'=> $event['id'] ])}}">Edit</a>
                                 </button>
                            </div>
                       @endforeach
                @endif
            </div>
        </div>
    </div>
</div>


<h2>
    <a href="{{route('logout')}}">logout</a>
</h2>

<script type="module">

    $(function () {

        $('#del-event').on('click', function () {

            let val = $(this).val();
            $.ajax({
                url: "/del-event",
                data: {
                    'val': val,
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


