@extends('master')
@section('content')
<div class="inner-container follow">
    <div class="inner-page">
        <h2 class="sub-title">Create new task <a href="{{url('followups')}}" class="btn-close">X</a> </h2>
        {!! Form::open(['id'=>'createTaskForm']) !!}
        {!! Form::hidden('id', $task->id) !!}
        <div class="follow-header">
            <div class="user-img"><img src="img/profile/img-photo.jpg"></div>
            <div class="title-col1">
                <p>Assigned to</p>
                {!! Form::text('assignee','',['id'=>'selectAssignee','placeholder'=>'email','class'=>'input-txt']) !!}
                {{-- <input name="assignee" id="selectAssignee" type="text" class="input-txt" /> --}}
                {!! $errors->first('assignee','<span>:message</span>')!!}
            </div>
            <div class="title-col2 date">
                <p>Due Date Time</p>
                <input name="dueDate" value="{{$task->dueDate}}" type="text" class="input-date nextDateInput" />
                {!! $errors->first('dueDate','<span>:message</span>')!!}
            </div>
            {{-- <div class="title-col3 time">
                <p>Due Time</p>
                <input type="text" class="input-date" />
            </div> --}}
            <div class="title-col4">
                <p>Status</p>
                Draft
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
                <input name="title" type="text" value="{{$task->title}}" class="input-txt-long" placeholder="Title">
                {!! $errors->first('title','<span>:message</span>')!!}
            </div>
            <div class="follow-row">
                <textarea name="description" class="textarea-long" placeholder="Task description">{{$task->description}}</textarea>
                {!! $errors->first('description','<span>:message</span>')!!}
            </div>
            <div class="follow-row">
                <textarea name="notes" class="textarea-long" placeholder="Notes">{{$task->notes}}</textarea>
                {!! $errors->first('notes','<span>:message</span>')!!}
            </div>
        </div>
        {!! Form::close()!!}

        <div class="follow-bottom">
            <a href="#" id="createTaskSubmit" class="btn-inter btn-small">Create Task</a>
            <a href="#" id="createTaskSave" class="btn-normal marginright10 btn-small">Save Draft</a>
            <a href="#" id="deleteDraft" tid="{{ $task->id }}" class="btn-normal marginright10 btn-small">Discard Draft</a>
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
            $('#createTaskForm').attr('action',"{{url('/followups/createTask')}}");            
            $('#createTaskForm').submit();
        });
        $('#deleteDraft').click(function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            //has to be deleted in minutes
        }
        else
        {
            path = "{{url('/followups/deleteDraft')}}/"+tid;
        }
        if (confirm('Are you sure to discard task?'))
        {
           window.location.href=path;
        }
        
    });
        // nextDateInput();
        // ( "#selectAssignee" ).autocomplete({
        //     source: "/assignee/search",
        //     minLength: 2,
        //     select: function( event, ui ) {
        //     	insert = '<option value="">Search user</option><option value="'+ui.item.userId+'" selected="selected">'+ui.item.value+'</option>';
	       //      $('#assignee').html(insert);
	       //      $(this).val('');
	       //      $('#selectAssignee').hide();
	       //      $('#assignee').show();
	       //       getHistory();
	       //      return false;
        //     }
        // });
    </script>
@endsection