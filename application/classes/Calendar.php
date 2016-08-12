<?php

class Calendar
{
	public function monthsList()
	{
		for ($i=1; $i<=12; $i++)
			$months[$i] = date("M", mktime(0, 0, 0, $i, 1, 0));
		return $months;
	}
	
	public function daysList($firstDay)
	{
		$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 
				'Thursday', 'Friday', 'Saturday');
		
		if (strtolower($firstDay) == 'monday')
		{
			unset($days[0]);
			$days[] = 'Sunday';
		}
		
		return $days; 
	}
	
	public function yearsList($totalNumber)
	{
		$totalNumber = intval($totalNumber);
		$years = array();
		$year = (int) date("Y");
		
		for ($i = 0; $i < $totalNumber; $i++)
			$years[] = $year++;
		
		return $years;
	}
	
	public function getPrevAndNextMonth($month, $year)
	{
		$month = intval($month);
		$year = intval($year);
		
		$nextMonth = ($month == 12) ? 1 : ($month + 1);
		$nextYear = ($month == 12) ? ($year + 1) : $year;
		$prevMonth = ($month == 1) ? 12 : ($month - 1);
		$prevYear = ($month == 1) ? ($year - 1) : $year;
		
		
		return array("prev_month" => $prevMonth, "prev_year" => $prevYear,
					 "next_month" => $nextMonth, "next_year" => $nextYear);
	}
	
	public function checkIsDateReal($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	public function daysInMonth($unixDate)
	{
		return cal_days_in_month(CAL_GREGORIAN, date("m", $unixDate), date( "Y", $unixDate));
	}
	
	public function timeToHourSystem($unixTime, $type = '12')
	{
		if ('12' == $type)
			return date('g:i a', $unixTime);
		else
			return date('G:i', $unixTime);
	}
	
	public function calendarCells($currentMonth, $currentYear, $firstDayType)
	{
		$currentMonth = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
		$firstDate = strtotime($currentYear . "-" . $currentMonth . "-01");
		$firstDay = date("N", $firstDate); // Number of a day in the week

		if ($firstDayType == 'Monday')
			$firstDay--;
		else
			$firstDay = ($firstDay > 6) ? 0 : $firstDay; // For Sunday

		$month = date("M", $firstDate); // e.g. Dec, Jan

		$daysInMonth = $this->daysInMonth($firstDate);

		$currentCell = 0;
		$currentDay = 0;
		$firstDayCome = false;
		$cells = array();

		// cells content
		for ($i = 0; $i <= 5; $i ++) 
		{
			for ($j = 0; $j <= 6; $j++)
			{
				if ($currentCell < $firstDay && !$firstDayCome)
				{
					$cells[$i][$j] = "";
				}
				else if ($currentCell == $firstDay && !$firstDayCome) 
				{
					$firstDayCome= true;
					$cells[$i][$j] = ++$currentDay;
				}
				else if ($firstDayCome) 
				{
					if ($currentDay < $daysInMonth)
						$cells[$i][$j] = ++$currentDay; 
					else
						$cells[$i][$j] = "";	
				}							
				$currentCell++;				
			}
		}
		
		return $cells;
	}
	
	public function isDayRealInThisMonth($unixTime, $duration)
	{
		$dayNum = date("d", $unixTime);
		
		for ($i=0; $i<$duration; $i++)
		{
			$unixTime = strtotime('+1 month', $unixTime);
			$newDayNum = date("d", $unixTime);;
			
			if ($dayNum != $newDayNum)
			{
				return false;
				break;
			}	
		}
		
		return true;;
	}
}