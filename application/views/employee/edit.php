<?php
	if (!empty($errors))
		foreach($errors as $err)
			echo '<b class="error">' . $err . '</b><br/>';
	elseif (!empty ($success))
		echo '<b class="success">' . $success . '</b><br/>';
?>		

<form action="/employee/edit/id=<?php echo Input::get('id')?>" method="post">
	<p> 1) Enter new employee name (required).</p>
	<p><input type="text" name="employee_name" value="<?php echo Input::get('employee_name')?>"/></p>

	<p> 2) Enter new employee e-mail (required).</p>
	<p> <input type="text" name="employee_email" value="<?php echo Input::get('employee_email')?>" /></p>
	
	<input type="hidden" name="employee_id" value="<?php echo Input::get('id')?>">
	<p><input type="submit" value="Edit" name="submit"></p>
</form>

<p><a href="/employee/index">&lt;&lt; Employee List</a></p>
