@extends('master')
@section('content')
<div class="inner-container follow">
    <div class="inner-page">
        <h2 class="sub-title">Create new task <a href="#" class="btn-close">X</a> </h2>
        {!! Form::open(['id'=>'createTaskForm']) !!}
        <div class="follow-header">
            <div class="user-img"><img src="img/profile/img-photo.jpg"></div>
            <div class="title-col1">
                <p>Assigned to</p>
                <input name="assignee" type="text" class="input-txt" />
            </div>
            <div class="title-col2 date">
                <p>Due Date Time</p>
                <input name="dueDate" type="text" class="input-date" />
            </div>
            {{-- <div class="title-col3 time">
                <p>Due Time</p>
                <input type="text" class="input-date" />
            </div> --}}
            <div class="title-col4">
                <p>Status</p>
                Open
            </div>
        </div>

        <div class="follow-content">
            <div class="follow-row">
                <div class="follow-note">
                    <label>Creation Date :</label>08, Dec ’15
                </div>
                <div class="follow-note">
                    <label>Recently Updated :</label>00 mins ago
                </div>
            </div>
            <div class="follow-row">
                <input name="title" type="text" class="input-txt-long" placeholder="Title">
            </div>
            <div class="follow-row">
                <textarea name="description" class="textarea-long" placeholder="Task description"></textarea>
            </div>
            <div class="follow-row">
                <textarea name="notes" class="textarea-long" placeholder="Notes"></textarea>
            </div>
        </div>
        {!! Form::close()!!}

        <div class="follow-bottom">
            <a href="#" id="createTaskSubmit" class="btn-inter btn-small">Create Task</a>
            <a href="#" id="createTaskSave" class="btn-normal marginright10 btn-small">Save Draft</a>
        </div>
    </div>
</div>
@endsection
@section('javascript')
    <script type="text/javascript">
    	$('#createTaskSave').click(function(event) {
            event.preventDefault();
            $('#createTaskForm').attr('action',"{{url('/followups/draft')}}");            
            $('#createTaskForm').submit();
        });
        $('#createTaskSubmit').click(function(event) {
            event.preventDefault();
            $('#createTaskForm').attr('action',"{{url('/jobs/createTask')}}");            
            $('#createTaskForm').submit();
        });
    </script>
@endsection