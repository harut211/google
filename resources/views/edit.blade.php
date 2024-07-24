<!DOCTYPE html>
<html>
<head>
    <title>Update page</title>
    @vite(['resources/scss/app.scss'])
</head>
<body>
<h2 class="d-flex justify-content-center">Update your event</h2>

<div class="card">
    <div class="card-body ">
        <div class="container-lg d-flex justify-content-center">
            <div class="row d-flex justify-content-center">
                <form action="{{route('edit-event')}}" method="post">
                    @if(!empty(session('success')))
                        <div class="alert alert-success">
                            {{session('success')}}
                        </div>
                    @endif
                    <input type="hidden" name="event_id" value="{{$event->event_id}}">
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="formGroupExampleInput"
                               value="{{$event->summary}}">
                        <div style="color: red">
                            {{$errors->first('title')}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Content</label>
                        <input type="text" name="description" class="form-control" id="formGroupExampleInput2"
                               value="{{$event->description}}">
                        <div style="color: red">
                            {{$errors->first('description')}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput3" class="form-label">Start Event</label>
                        <input type="datetime-local" name="start" class="form-control"
                               value="{{$event->start}}" id="formGroupExampleInput3">
                        <div style="color: red">
                            {{$errors->first('start')}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput4" class="form-label">End Event</label>
                        <input type="datetime-local" name="end" class="form-control" id="formGroupExampleInput4"
                               value="{{$event->end}}">
                    </div>
                    <div style="color: red">
                        {{$errors->first('end')}}
                    </div>
                    <button class="btn btn-primary justify-content-center">Edit Event</button>
                </form>
            </div>
        </div>
    </div>
</div>
<h2>
    <a href="{{route('home')}}">Go back</a>
</h2>
</body>
</html>


