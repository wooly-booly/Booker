<script src="/js/confirmDelete.js"></script>
<b>B.B. Details</b>

<form action="/booker/modify/" method="post">
	<p>When: <?php echo $appoint->viewTime; ?></p>
	<p>Notes: <input type="text" name="description" value="<?php echo $appoint->description; ?>" /></p>
	<p>Who:
		<select name="employee_id">
		<?php
            if (!empty($employees)) {
                foreach ($employees as $employee) {
                    if ($employee->id == $appoint->employee_id) {
                        echo "<option value='".$employee->id."' selected>".$employee->name."</option>\n";
                    } else {
                        echo "<option value='".$employee->id."'>".$employee->name."</option>\n";
                    }
                }
            }
        ?>
		</select>
		<?php
            if ($appoint->start_time < time()) {
                echo "<p><b>You can't edit passed appointments!</b></p>";
            } else {
                if ($appoint->recurrence != 'once') {
                    echo '<p><input type="checkbox" name="all_recurrence" value="yes" id="cAll">'
                        .'<label for="cAll">Apply to all occurrences?</label></p>';
                }

                echo '<p><input type="submit" value="UPDATE" name="UPDATE" />';
                echo ' <input type="submit" value="DELETE" name="DELETE" class="confirmDel" /></p>';
            }
        ?>
		<input type="hidden" value="<?php echo $appoint->id ?>" name="appointment_id" class="confirmDel" />
	</p>
</form>
