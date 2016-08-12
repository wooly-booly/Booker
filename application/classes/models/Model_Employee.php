<?php

class Model_Employee extends Model
{
    private $_table = 'employees';

    public function addEmployee($name, $email)
    {
        $name = $this->db->escape($name);
        $email = $this->db->escape($email);
        $params = array('name' => $name, 'email' => $email);

        $result = $this->db->insert($this->_table, $params);

        return $result;
    }

    public function editEmployee($id, $name, $email)
    {
        $id = intval($id);
        $name = $this->db->escape($name);
        $email = $this->db->escape($email);

        $params = array('name' => $name, 'email' => $email);

        $result = $this->db->update($this->_table, $params,
                                    "id='".$id."'", 1);

        return $result;
    }

    public function getEmloyeeList()
    {
        $where = "status='works'";

        $result = $this->db->select($this->_table, '*', null, $where, 'name');

        return $result;
    }

    public function getFullEmployeeList()
    {
        $result = $this->db->select($this->_table, '*', null, null, 'name');

        return $result;
    }

    public function getEmloyeeByID($id)
    {
        $id = intval($id);
        $result = $this->db->select($this->_table, '*', null,
                                    "id='".$id."'", null, 1);

        if (!empty($result[0])) {
            return $result[0];
        } else {
            return;
        }
    }

    public function uniqueEmployee($name)
    {
        $name = $this->db->escape($name);
        $result = $this->db->select($this->_table, 'name', null,
                                    "name='{$name}'", null, 1);

        return $result;
    }

    public function deleteEmployee($id)
    {
        $id = intval($id);

        if (!$id) {
            return;
        }

        $appointModel = new Model_Appointment();
        $isOldAppoints = $appointModel->isOldAppointsForEmployee($id);

        if ($isOldAppoints) {
            $q = ' DELETE FROM appointments, employee_to_appointment '
            .' USING appointments, employee_to_appointment '
            .' WHERE appointments.id=employee_to_appointment.appointment_id '
            ." AND appointments.start_time>'".time()."' "
            ." AND employee_to_appointment.employee_id='".$id."'";

            $this->db->sql($q);
            $result = $this->db->update($this->_table, array('status' => 'retired'), 'id='.$id, 1);

            return $result;
        } else {
            $q = ' DELETE FROM appointments, employee_to_appointment, employees '
            .' USING appointments, employee_to_appointment, employees '
            .' WHERE appointments.id=employee_to_appointment.appointment_id '
            .' AND employees.id=employee_to_appointment.employee_id '
            ." AND employee_to_appointment.employee_id='".$id."'";

            $result = $this->db->sql($q);

            return $result;
        }
    }
}
