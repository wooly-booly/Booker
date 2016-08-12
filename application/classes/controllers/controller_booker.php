<?php

class Controller_Booker extends Controller
{
	private $_emplModel = null;
	private $_appointModel = null;
	private $_calendar = null;
	
	public function __construct() 
	{
		parent::__construct();
		$this->_emplModel = new Model_Employee();	
		$this->_appointModel = new Model_Appointment();
		$this->_calendar = new Calendar();
	}

	public function action_index()
    {
		// month and year for calendar
		$m = Input::get('m');
		$y = Input::get('y');
		$boardroom = Input::get('b') ? Input::get('b') : 1;
		
		if (empty($m) || empty($y))
		{	
			$cYearMonth = explode(" ", date("Y m F"));
			$m = $cYearMonth[1];
			$y = $cYearMonth[0];
			$fullMonthName = $cYearMonth[2];
			$prevAndNextMonth = $this->_calendar->getPrevAndNextMonth($m, $y);
		}
		else
		{
			$fullMonthName = date('F', mktime(0,0,0,intval($m)));
			$prevAndNextMonth = $this->_calendar->getPrevAndNextMonth($m, $y);
		}
		
		$appointments = $this->_appointModel->getAppointmentsByMonth($m, $y, $boardroom);

		$firstDayOfWeek = $this->conf->get('first_week_day');
		$weekDays = $this->_calendar->daysList($firstDayOfWeek);
		$cells = $this->_calendar->calendarCells($m, $y, $firstDayOfWeek);

		$this->view->set('boardroom', $boardroom);
		$this->view->set('year', $y);
		$this->view->set('month', $fullMonthName);
		$this->view->set('prevNext', $prevAndNextMonth);
		$this->view->set("weekDays", $weekDays);
		$this->view->set("cells", $cells);
		$this->view->set("appointments", $appointments);
		$this->view->show('index');
    }
	
	public function action_add()
	{	
		$boardroom = Input::get('b') ? Input::get('b') : 1;	
		if (Input::occurred())
		{
			$params = $this->validateNewAppointment();
			
			if (!empty($params)) 
			{
				$result = $this->_appointModel->addAppointments($params);
				$this->view->set('success', 'New appointment succesfully added!');
			}
		}
		
		$years = $this->_calendar->yearsList($this->conf->get('years_number'));
		$employees = $this->_emplModel->getEmloyeeList();
		$monthsList = $this->_calendar->monthsList();
		
		$this->view->set('boardroom', $boardroom);
		$this->view->set('employees', $employees);
		$this->view->set('months', $monthsList);
		$this->view->set('years', $years);
		$this->view->show('add');
	}
	
	public function action_edit()
	{
		$id = Input::get('id');
		$appointInfo = $this->_appointModel->getAppointmentByID($id);
		
		if(empty($appointInfo))
			$this->redirect('booker/index/b=1');
			
		if ($appointInfo[0]->start_time > time())
			$employees = $this->_emplModel->getEmloyeeList();
		else
			$employees = $this->_emplModel->getFullEmployeeList();
		
		$this->view->set('appoint', $appointInfo[0]);
		$this->view->set('employees', $employees);
		$this->view->show('edit');
	}
	
	public function action_modify()
	{
		$delete = Input::get('DELETE');
		$update = Input::get('UPDATE');
		$all = Input::get('all_recurrence');
		$appointID = Input::get('appointment_id');
		
		if ($delete)
		{
			if ($all == 'yes')
				$result = $this->_appointModel->deleteAppointment($appointID, true);
			else
				$result = $this->_appointModel->deleteAppointment($appointID);
			
			if ($result)
				$this->view->set('success', 'Event(s) has been removed!');
		}
		
		if ($update)
		{
			$employeeID = Input::get('employee_id');
			$description = Input::get('description');
			
			$errors = array();
			// if no descriptions than ...
			if (empty($description))
			{
				$errors[] = "Notes is required! Please enter the specifics for the meeting";
				$this->view->set('errors', $errors);
			}
			else
			{
				$params = array(
					"description" => $description,
					"employee_id" => $employeeID
				);

				if ($all == 'yes')
					$result = $this->_appointModel->updateAppointment($appointID, true, $params);
				else
					$result = $this->_appointModel->updateAppointment($appointID, false, $params);

				if ($result)
					$this->view->set('success', 'Event(s) has been updated!');
			}
		}
		
		$this->view->show('modify');
	}
	
	private function validateNewAppointment()
	{
		$errors = array();
		
		// get data for checking
		$boardroom = Input::get('b') ? Input::get('b') : 1;	
		$recurrence = Input::get('recurrence'); // 'yes' or 'no'
		$repeatType = Input::get('repeat_type');
		$duration = Input::get('duration');
		$description = Input::get('description');

		// date for meeting Y-m-d
		$bYear = Input::get("year");
		$bMonth = Input::get("month");		
		$bDay = Input::get("day");
		$bookDateYmd = $bYear . '-' . $bMonth . '-' . $bDay;
		$bookDateUnix = strtotime($bookDateYmd);

		// start time for meeting
		$bookStartHour = Input::get('start_hour');
		$bookStartMinute = Input::get('start_minute');
		$bookStartAmPm = Input::get('start_am_pm');			
		$bookStartTime = $bookStartHour . ':' . $bookStartMinute 
					. ' ' . $bookStartAmPm;						
		$bookSDataTime = $bookDateYmd . ' ' . $bookStartTime; // Y-m-d g:i a

		$bookSDataTimeUnix = strtotime($bookSDataTime);

		// finish time for meeting
		$bookFinishHour = Input::get('finish_hour');
		$bookFinishMinute = Input::get('finish_minute');
		$bookFinishAmPm = Input::get('finish_am_pm');			
		$bookFinishTime = $bookFinishHour . ':' . $bookFinishMinute 
					. ' ' . $bookFinishAmPm;			
		$bookFDataTime = $bookDateYmd . ' ' . $bookFinishTime; // Y-m-d g:i a
		$bookFDataTimeUnix = strtotime($bookFDataTime);

		//Current date and time in Unix Format
		$curDateUnix = strtotime(date("Y-m-d"));
		$curDateTimeUnix = strtotime(date("Y-m-d H:i"));


		// if date something like '31 February' ... 
		if (!Calendar::checkIsDateReal($bookDateYmd, 'Y-m-d'))
			$errors[] = "The date you selected isn't real";

		// if date for meeting less than current day ...
		// if ($bookDateUnix < $curDateUnix)
		//	$errors[] = "The date you selected can not be less than the current date";

		// if start time less than current time ...
		if ($bookSDataTimeUnix <= $curDateTimeUnix)
			$errors[] = "The date and time you selected can not be less than the current date and time";

		// if start time bigger than  finish time ...
		if  ($bookSDataTimeUnix >= $bookFDataTimeUnix)
			$errors[] = "Start time must be less than finish time";

		// if no descriptions than ...
		if (empty($description))
			$errors[] = "Please enter the specifics for the meeting";
		
		// next code check Is appointment already busy or not
		if (empty($errors) && ($recurrence == 'yes'))
		{
			if ( !($duration == intval($duration))
			||   !is_numeric($duration) )
			{
				$errors[] = "Duration must be integer";
			}
			if ($duration <= 1)
			{
				$errors[] = "Duration must be larger than 1";
			}
			else
			{
				$duration = intval($duration);
				$maxWeekly = $this->conf->get('max_duration_weekly');
				$maxBiWeekly = $this->conf->get('max_duration_biweekly');
				$maxMonthly =  $this->conf->get('max_duration_monthly');

				$isFreeRoom = false;
				switch ($repeatType) 
				{
					case 'weekly':
						if ($duration > $maxWeekly)
						{
							$errors[] = "Weekly duration, max " . $maxWeekly;
							break;
						}							
						$isFreeRoom = $this->_appointModel->isRoomFreeForRecurAppoint(
							$boardroom, $bookSDataTimeUnix, $bookFDataTimeUnix, $repeatType, $duration);							
						break;
					case 'bi-weekly':
						if ($duration > $maxBiWeekly)
						{
							$errors[] = "Bi-Weekly duration, max " . $maxBiWeekly;
							break;
						}
						$duration = intval($duration/2);
						$isFreeRoom = $this->_appointModel->isRoomFreeForRecurAppoint(
							$boardroom, $bookSDataTimeUnix, $bookFDataTimeUnix, $repeatType, $duration);							
						break;
					case 'monthly':
						if ($duration > $maxMonthly)
						{
							$errors[] = "Monthly duration, max " . $maxMonthly;
							break;
						}		

						$realDay = $this->_calendar->isDayRealInThisMonth($bookSDataTimeUnix, $duration);
						// if not real day of month
						if (!$realDay) 
						{
							$errors[] = "Day of months not real (Not every month has 29, 30, 31 number)";
							break;
						}

						$isFreeRoom = $this->_appointModel->isRoomFreeForRecurAppoint(
							$boardroom, $bookSDataTimeUnix, $bookFDataTimeUnix, $repeatType, $duration);
						break;
					default:
						$recurrence = 'no';
						break;
				}	

				$busy = $this->_appointModel->getBusyTime();
				if (!$isFreeRoom && !empty($busy))
					$errors[] = "The boardroom not available for time you selected"
								. " (already busy " . $busy . ")";
			}								
		}
		elseif (empty($errors))
		{	
			// if boardroom not available for this time
			$isFreeRoom = $this->_appointModel->isRoomFreeForTime($boardroom, 
									$bookSDataTimeUnix, $bookFDataTimeUnix);			
			if (!$isFreeRoom)
				$errors[] = "The boardroom not available for time you selected"
						. " (already busy " . $this->_appointModel->getBusyTime() . ")";
		}

		if (!empty($errors))
		{
			$this->view->set('errors', $errors);	
			return false;
		}

		// Params for add appointment to DB
		$params = array(
			'start_time' => $bookSDataTimeUnix,
			'finish_time' => $bookFDataTimeUnix,
			'description' => Input::get('description'),			
			'boardroom' => $boardroom,
			'repeatYesOrNo' => $recurrence,
			'recurrence' => $repeatType,
			'duration' => $duration,
			'employee_id' => Input::get('employee_id')
		);

		return $params;
	}
}