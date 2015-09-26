@if(count($historytask))
	<div class="mainListFilter">
		<input type="text" placeholder="Search..." id="historySearch"> <span id="showHistroyDiv">Reset</span>
		<select  class="dropdown">
		  <option value="0">Any origin</option>
		  <option value="Option">Option 1</option>
		  <option value="Option">Option 2</option>
		  <option value="Option">Option 3</option>
		</select>
		<select  class="dropdown">
		  <option value="0">Any one</option>
		  <option value="Option">Option 1</option>
		  <option value="Option">Option 2</option>
		  <option value="Option">Option 3</option>
		</select>
		    <select name="speed" class="dropdown">
		      <option>Slower</option>
		      <option>Slow</option>
		      <option selected="selected">Medium</option>
		      <option>Fast</option>
		      <option>Faster</option>
		    </select>
		<button>Reset all</button>
	</div>
	<div id="historyDiv" class="mainList">

	</div>
@else
No Tasks
@endif
<!--================ Buttons for now sections ======================-->
<div class="arrowBtn arrowBtnRight">
	<span id="moveright"><img src="{{asset('images/arrow_right.png')}}"> </span>
	<p>Now</p>
</div>