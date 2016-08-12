<p><span class="boardroom_number">Boardroom <?php echo $boardroom; ?></span></p>

<?php
    if (!empty($errors)) {
        foreach ($errors as $err) {
            echo '<b class="error">'.$err.'</b><br/>';
        }
    } elseif (!empty($success)) {
        echo '<b class="success">'.$success.'</b><br/>';
    }
?>

<form action="/booker/add/b=<?php echo $boardroom; ?>" method="post">
	<p>1. Booked for: </p>
	<p>
		<select name="employee_id">
		<?php
            if (!empty($employees)) {
                foreach ($employees as $employee) {
                    echo "<option value='".$employee->id."'>"
                        .$employee->name."</option>\n";
                }
            }
        ?>
		</select>
	</p>
	<p>2. I would like to book this meeting: <br/><br/>
		<select name="month">
		<?php
            $curMonthAndDay = date('m d');
            $curMonthAndDay = explode(' ', $curMonthAndDay);
            foreach ($months as $number => $month) {
                $number = str_pad($number, 2, '0', STR_PAD_LEFT);
                if ($number == $curMonthAndDay[0]) {
                    echo "<option value='".$number."' selected>".$month."</option>\n";
                } else {
                    echo "<option value='".$number."'>".$month."</option>\n";
                }
            }
        ?>
		</select>
		<select name="day">
		<?php
            for ($i = 1; $i <= 31; ++$i) {
                $d = str_pad($i, 2, '0', STR_PAD_LEFT);
                if ($d == $curMonthAndDay[1]) {
                    echo "<option value='".$d."' selected>".$i."</option>\n";
                } else {
                    echo "<option value='".$d."'>".$i."</option>\n";
                }
            }
        ?>
		</select>
		<select name="year">
		<?php
            foreach ($years as $year) {
                echo "<option value='".$year."'>".$year."</option>\n";
            }
        ?>
		</select>
	</p>
	<p>3. Specify what the time and end of the meeting (This will be what people see on the calendar.) <br/>
		<p>
			<select name="start_hour">
			<?php
                echo "<option value='12'>12</option>\n";
                for ($i = 1; $i <= 11; ++$i) {
                    if ($i == 8) {
                        echo "<option value='".$i."' selected>".$i."</option>\n";
                    } else {
                        echo "<option value='".$i."'>".$i."</option>\n";
                    }
                }
            ?>
			</select>
			<select name="start_minute">
			<?php
                for ($i = 0; $i <= 59; ++$i) {
                    $m = str_pad($i, 2, '0', STR_PAD_LEFT);
                    echo "<option value='".$m."'>".$m."</option>\n";
                }
            ?>
			</select>
			<select name="start_am_pm">
				<option value="am" selected>AM</option>
				<option value="pm">PM</option>
			</select>
		<p>
		<p>
			<select name="finish_hour">
			<?php
                echo "<option value='12'>12</option>\n";
                for ($i = 1; $i <= 11; ++$i) {
                    if ($i == 8) {
                        echo "<option value='".$i."' selected>".$i."</option>\n";
                    } else {
                        echo "<option value='".$i."'>".$i."</option>\n";
                    }
                }
            ?>
			</select>
			<select name="finish_minute">
			<?php
                for ($i = 0; $i <= 59; ++$i) {
                    $m = str_pad($i, 2, '0', STR_PAD_LEFT);
                    echo "<option value='".$m."'>".$m."</option>\n";
                }
            ?>
			</select>
			<select name="finish_am_pm">
				<option value="am" selected>AM</option>
				<option value="pm">PM</option>
			</select>
		<p>
	</p>
	<p>4. Enter the specifics for the meeting. (This will be what people see when they click on an event link.)
		<p><textarea rows="5" cols="30" name="description"></textarea></p>
	</p>
	<p>5. Is this going to be a recurring event? <br/>
		<p>
			<input type="radio" name="recurrence" value="no" id="rNo" checked><label for="rNo">No</label><br/>
			<input type="radio" name="recurrence" value="yes" id="rYes"><label for="rYes">Yes</label>
		</p>
	</p>
	<p>6. If it is recurring, specify weekly, bi-weekly or monthly
		<p>
			<input type="radio" name="repeat_type" value="weekly" id="rW" checked><label for="rW">weekly</label><br/>
			<input type="radio" name="repeat_type" value="bi-weekly" id="rBw"><label for="rBw">bi-weekly</label><br/>
			<input type="radio" name="repeat_type" value="monthly" id="rM"><label for="rM">monthly</label>
		</p>
		<p>
			If weekly or bi-weekly, specify the number of weeks for it to keep recurring. If monthly, specify the number of months.
			<br/>(If you choose "bi-weekly" and put in an odd number of weeks, the computer will round down.)
		</p>
		<p>
			<input type="text" name="duration" size="3"/> duration (max 4 weeks)
		</p>
	</p>
	<p><input type="submit" value="Submit" name="submit"></p>
 </form>
