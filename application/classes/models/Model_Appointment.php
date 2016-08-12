<?php

class Model_Appointment extends Model
{
	private $_table = 'appointments';
	private $_busyTime = '';
	
	public function addAppointments($params)
	{	
		$result = false;
		$fields = $params;
		$neededKeys = array('start_time', 'finish_time', 
					'description',	'boardroom');
		
		if ($params['repeatYesOrNo'] == 'yes')
			$neededKeys[] = 'recurrence';
		
		foreach ($fields as $key => $val)
			if (!in_array($key, $neededKeys))
				unset($fields[$key]);
		
		if ($params['repeatYesOrNo'] == 'no')
		{
			$result = $this->addOneAppointment($fields, $params['employee_id']);
		}
		elseif ($params['repeatYesOrNo'] == 'yes')
		{
			$rType = $params['recurrence'];
			$duration = $params['duration'];
			$toDeleteRecurrence = time(); // unique identifier
			$fields['to_delete_recurrence'] = $toDeleteRecurrence;
			$unixStartTime = $params['start_time'];
			$unixFinishTime = $params['finish_time'];
						
			for ($i=0; $i<$duration; $i++)
			{
				if ($i == 0)
				{
					$result = $this->addOneAppointment($fields, $params['employee_id']);
				}
				else
				{
					if ($rType == 'weekly')
					{
						$unixStartTime = strtotime('+1 week', $unixStartTime); 
						$unixFinishTime = strtotime('+1 week', $unixFinishTime);
						$fields['start_time'] = $unixStartTime;
						$fields['finish_time'] = $unixFinishTime;

						$result = $this->addOneAppointment($fields, $params['employee_id']);
					}
					elseif ($rType == 'bi-weekly')
					{
						$unixStartTime = strtotime('+2 week', $unixStartTime); 
						$unixFinishTime = strtotime('+2 week', $unixFinishTime);
						$fields['start_time'] = $unixStartTime;
						$fields['finish_time'] = $unixFinishTime;
						
						$result = $this->addOneAppointment($fields, $params['employee_id']);
					}
					elseif($rType == 'monthly')
					{
						$unixStartTime = strtotime('+1 month', $unixStartTime); 
						$unixFinishTime = strtotime('+1 month', $unixFinishTime);
						$fields['start_time'] = $unixStartTime;
						$fields['finish_time'] = $unixFinishTime;
						
						$result = $this->addOneAppointment($fields, $params['employee_id']);
					}
				}	

				if (!$result)
				{
					return false;
					break;
				}
			}
		}	
		
		return $result;
	}
	
	public function addOneAppointment($fieldsForAppointTable, $emplID)
	{
		$fields = $this->db->escape($fieldsForAppointTable);
		$emplID = $this->db->escape($emplID);
		$appointID = $this->db->insert($this->_table, $fields);
		
		// fields for table employee_to_appointment
		$fields = array('employee_id' => $emplID,
						'appointment_id' => $appointID);
		$appToEmplID = $this->db->insert('employee_to_appointment', $fields);
		
		return $appToEmplID;
	}
		
	public function updateAppointment($id, $forAll=false, $params=array())
	{
		// appointment id
		$id = intval($id);
		
		if (empty($params)) 
			return false;
		else
			$params = $this->db->escape($params);
		
		$appoint = $this->getAppointmentByID($id);
		
		if (empty($appoint))
			return false;
		
		$appoint = $appoint[0];

		if (($appoint->employee_id == $params['employee_id'])
		&&	($appoint->description == $params['description']))
		{
			return true;
		}

		if ($forAll)
		{
			if (empty($appoint->to_delete_recurrence))
				return false;
			
			$q = " UPDATE appointments, employee_to_appointment"
				." SET appointments.description='" . $params['description'] . "', "
				." employee_to_appointment.employee_id='" . $params['employee_id'] . "'"					
				." WHERE appointments.id=employee_to_appointment.appointment_id "
				." AND appointments.to_delete_recurrence='" . $appoint->to_delete_recurrence . "' "
				." AND appointments.start_time>'" . time() . "' ";		
		}
		else
		{	
			$q = " UPDATE appointments, employee_to_appointment"
				." SET appointments.description='" . $params['description'] . "', "
				." employee_to_appointment.employee_id='" . $params['employee_id'] . "'";
			
			if ($appoint->employee_id != $params['employee_id'])
			{
				$q .= ", appointments.recurrence='once' ";
				$q .= ", appointments.to_delete_recurrence=null ";
			}
			
			$q .= " WHERE appointments.id=employee_to_appointment.appointment_id "
				. " AND appointments.id='" . $id . "' ";		
			
		}
	
		return $this->db->sql($q);
	}
	
	public function deleteAppointment($id, $forAll=false)
	{
		// appointment id
		$id = intval($id);
		
		if ($forAll)
		{
			$appoint = $this->getAppointmentByID($id);
			if (empty($appoint)) return;

			// to delete all appointments get unique id for all of them...
			$uniqueDelId = $appoint[0]->to_delete_recurrence;

			$q = " DELETE FROM appointments, employee_to_appointment "
				." USING appointments, employee_to_appointment "
				." WHERE appointments.id=employee_to_appointment.appointment_id "
				." AND appointments.to_delete_recurrence='" . $uniqueDelId . "' "
				." AND appointments.start_time>'" . time() . "' ";
		}
		else
		{	
			$q = " DELETE FROM appointments, employee_to_appointment "
				." USING appointments, employee_to_appointment "
				." WHERE appointments.id=employee_to_appointment.appointment_id "
				." AND appointments.id='" . $id . "' ";			
		}
		
		return $this->db->sql($q);
	}
	
	public function getAppointmentByID($id)
	{
		$id = intval($id);
		$q = " SELECT appointments.*, employee_to_appointment.employee_id "
			." FROM appointments INNER JOIN employee_to_appointment "
			." WHERE employee_to_appointment.appointment_id=appointments.id "
			." AND appointments.id='" . $id . "'"
			." LIMIT 1";
		
		$result = $this->db->sql($q, 'select');

		if (!empty($result))
		{
			$conf = Config::instance();
			$hourSystem = $conf->get('hour_system');
			$startTime = Calendar::timeToHourSystem($result[0]->start_time, $hourSystem);
			$finishTime = Calendar::timeToHourSystem($result[0]->finish_time, $hourSystem);			
			$result[0]->viewTime = $startTime . ' - ' . $finishTime;
		}
		
		return $result;
	}
	
	public function getAppointmentsByMonth($month, $year, $boardroom = 1)
	{
		$month = str_pad(intval($month), 2, '0', STR_PAD_LEFT);
		$year = intval($year);
		$boardroom = intval($boardroom);
		
		$start = strtotime($year . "-" . $month . "-01");
		$finish = strtotime('+1 month', $start); 
		
		$where = "start_time>='" . $start . "'"
				. " AND start_time<'" . $finish . "'"
				. " AND boardroom='" . $boardroom . "'";
		
		$result = $this->db->select($this->_table, "id, start_time, finish_time", 
									null, $where, 'start_time');
			
		if (!empty($result))
		{
			$appointments = array(); // appointments[day] => array_of_appoints;
			$daysInMonth = Calendar::daysInMonth($start);
			
			$conf = Config::instance();
			$hourSystem = $conf->get('hour_system');
			
			for ($day=1; $day<=$daysInMonth; $day++)
			{
				$d = str_pad($day, 2, '0', STR_PAD_LEFT); // date('d')format
				$dayStart = strtotime($year . "-" . $month . "-" . $d);
				$dayEnd = strtotime('+1 day', $dayStart); 

				$appointsForDay = array();
				foreach ($result as $appoint)
				{
					if ( ($appoint->start_time >= $dayStart)
					&&   ($appoint->start_time < $dayEnd) )
					{
						$appoint->start_time = Calendar::timeToHourSystem($appoint->start_time, $hourSystem);
						$appoint->finish_time = Calendar::timeToHourSystem($appoint->finish_time, $hourSystem);		
						$appointsForDay[] = $appoint;
					}
				}
				
				$appointments[$day] = $appointsForDay; 
			}
			
			return $appointments;
		}	
		
		return $result;
	}
	
	public function isOldAppointsForEmployee($id)
	{	
		$q = " SELECT appointments.id "
			." FROM employee_to_appointment INNER JOIN appointments "
			." ON employee_to_appointment.appointment_id=appointments.id"
			." WHERE appointments.start_time<='" . time() . "' "
			." AND employee_to_appointment.employee_id='" . $id . "'"
			." LIMIT 1";
		
		return $this->db->sql($q, 'select');
	}
	
	public function isRoomFreeForTime($boardroom, $unixStartTime, $unixFinishTime)
	{
		$where = "boardroom='" . $boardroom . "'"
				. " && ( (start_time>='" . $unixStartTime . "'"
				. " && start_time<'" . $unixFinishTime. "')"
				. " OR (finish_time>'" . $unixStartTime . "'"
				. " && finish_time<='" . $unixFinishTime. "') "
				. " OR (start_time<'" . $unixStartTime . "'"
				. " && finish_time>'" . $unixFinishTime . "') )";
						
		$result = $this->db->select($this->_table, "id, start_time, finish_time", 
									null, $where, null, 1);

		if (!empty($result))
		{	
			$this->_busyTime = date('Y/m/d H:i', $result[0]->start_time) 
								. ' - ' . date('H:i', $result[0]->finish_time) ;
			return false;
		}
		else
			return true;
	}
	
	public function isRoomFreeForRecurAppoint($boardroom, $unixStartTime,	
									$unixFinishTime, $rType, $duration)
	{
		$free = false;			
			
		for ($i=0; $i<$duration; $i++)
		{				
			if ($i == 0)
			{
				$free = $this->isRoomFreeForTime($boardroom, $unixStartTime, $unixFinishTime);
			}
			else
			{
				if ($rType == 'weekly')
				{	
					$unixStartTime = strtotime('+1 week', $unixStartTime); 
					$unixFinishTime = strtotime('+1 week', $unixFinishTime);					
				}
				elseif ($rType == 'bi-weekly')
				{
					$unixStartTime = strtotime('+2 week', $unixStartTime); 
					$unixFinishTime = strtotime('+2 week', $unixFinishTime);
				}
				elseif($rType == 'monthly')
				{
					$unixStartTime = strtotime('+1 month', $unixStartTime); 
					$unixFinishTime = strtotime('+1 month', $unixFinishTime);
				}	
				$free = $this->isRoomFreeForTime($boardroom, $unixStartTime, $unixFinishTime);
			}

			if (!$free)
				break;
		}			

		return $free;
	}
	
	public function getBusyTime()
	{
		return $this->_busyTime;
	}
}