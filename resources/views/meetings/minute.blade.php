<div class="paper">
	<div id="toPrint" class="paperBorder">
		<div class="paperTitleLeft">
			<h3>{{$minute->meeting()->withTrashed()->first()->title}}</h3>
			<p>meeting venue: {{$minute->venue}}</p>
		</div>
		<div class="paperTitleRight">
			<h3>{{date('d-M-Y',strtotime($minute->startDate))}}</h3>
			<p>{{date('H:s:i',strtotime($minute->endDate))}}</p>
		</div>
		<div class="clearboth"></div>
		<div class="paperSubTitle">
			<p>
				<span>Participants:</span>
				@foreach(App\Model\Profile::whereIn('userId',explode(',',$minute->attendees))->lists('name','userId') as $attendee)
				{{$attendee}} ,
				@endforeach
			</p>
			<p>
				<span>Absentees:</span>
				@foreach(App\Model\Profile::whereIn('userId',explode(',',$minute->absentees))->lists('name','userId') as $absentees)
					{{$absentees}} ,
				@endforeach
			</p>
		</div>
		<h4>Minutes</h4>
		@if($minute->filed == '0')
			<?php 
				$lastFiledMinute = App\Model\Minutes::where('filed','=','1')->orderBy('startDate', 'DESC')->limit(1)->first();
				if($lastFiledMinute)
				{
					$tasks = App\Model\MinuteTasks::where('minuteId',$minute->id)
							->orWhereIn('id',function($query) use ($lastFiledMinute){
								$query->select('taskId')
		                    		->from('filedMinutes')
		                       		->where('filedMinutes.status','!=','Closed')
		                       		->where('filedMinutes.status','!=','Cancelled')
		                       		->where('filedMinutes.minuteId','=',$lastFiledMinute->id);
							})->get();
				}
				else
				{
					$tasks = $minute->tasks;
				}
			?>
		@else
			<?php $tasks = $minute->file; ?>
		@endif
		<?php $count = 1; ?>
		@foreach( $tasks as $task)
			<div class="minuteItem">
				<div class="minuteItemNumber">
					<p>{{$count++}}</p>
				</div>
				<div class="minuteItemLeft">
					<h5>{{$task->title}}</h5>
					<p>{{$task->description}}</p>
				</div>
				<div class="minuteItemRight">
					<h6>MINUTE{{$task->id}}</h6>
					<p>
						@if(isEmail($task->assignee))
						{{$task->assignee}}
						@else
						{{$task->assigneeDetail->name}}
						@endif
					</p>
					<p>{{$task->dueDate}}</p>
					<p>{{$task->status}}</p>
				</div>
				<div class="clearboth"></div>
			</div>
		@endforeach

		@if($minute->ideas()->count())
			<h4>Ideas</h4>
			@foreach($minute->ideas()->get() as $idea)
				<div class="minuteItem">
					<div class="minuteItemNumber">
						<p>{{$count++}}</p>
					</div>
					<div class="minuteItemLeft">
						<h5>{{$idea->title}}</h5>
						<p>{{$idea->description}}</p>
					</div>
					<div class="minuteItemRight">
						<h6>IDEA{{$idea->id}}</h6>
						<p>
							@if($idea->orginator)
								@if(isEmail($idea->orginator))
									{{$idea->orginator}}
								@else
									{{$idea->orginatorDetail->name}}
								@endif
							@endif
						</p>
					</div>
					<div class="clearboth"></div>
				</div>
			@endforeach
		@endif
		@if($minute->filed == '0')
			<span class="draft_tag"></span>
		@endif
	</div>
</div>
<div onclick="PrintElem('#toPrint')">Print</div>
<script type="text/javascript">
function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data) 
    {
    	var css1 = "<?php echo asset('/css/base.css'); ?>";
    	var css2 = "<?php echo asset('/css/sss.css'); ?>";
        var mywindow = window.open('', 'my div', 'height=400,width=600');
        mywindow.document.write('<html><head><title>Minutes</title>');
        /*optional stylesheet*/ 
        //"<link rel=\"stylesheet\" href=\"style.css\" type=\"text/css\" media=\"print\"/>"
        mywindow.document.write('<link rel="stylesheet" href="'+css1+'" type="text/css" media="print" />');
        mywindow.document.write('<link rel="stylesheet" href="'+css2+'" type="text/css" media="print" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }

</script>
