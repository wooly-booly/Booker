<p><span class="boardroom_number">Boardroom <?php echo $boardroom; ?></span></p>
<p>
	<b>
		<?php 
			echo "<a href='/booker/index/m=" . $prevNext['prev_month'] 
				. "&y=" . $prevNext['prev_year'] . "&b=" .  $boardroom . "'>&lt;</a> ";
			echo $month . " " . $year; 
			echo " <a href='/booker/index/m=" . $prevNext['next_month'] 
				. "&y=" . $prevNext['next_year'] . "&b=" .  $boardroom . "'>&gt;</a>";
		?>
	</b>
</p>

<table class="booker" cellspacing="0">
<?php
	if (!empty($cells))
	{
		// weekdays
		echo "<tr>";
		foreach ($weekDays as $day)
				echo "<td class='days'><div><b>" . $day . "</b></td>";
		echo "</tr>\n";
	
		// cells content
		foreach ($cells as $row => $tds)
		{
			if ($row > 1 && empty($tds[0]))
				break;
		
			echo "<tr>";
			foreach ($tds as $nDay)
			{	
				echo "<td><div>" . $nDay . "</div>" . "<div>";	
				
				if (is_numeric($nDay) && !empty($appointments[$nDay]))
					foreach ($appointments[$nDay] as $appoint)
					{
						echo "<a href='/booker/edit/id=" . $appoint->id . "'>"
							. $appoint->start_time . ' - ' . $appoint->finish_time 
							. "</a></br>\n";
					}
				
				echo "</div></td>";
			}
			echo "</tr>\n";
		}
	}
?>
</table>

<p><a href="/booker/add/b=<?php echo $boardroom ?>">Book It!</a></p>
<p><a href="/employee/index">Employee List</a></p>