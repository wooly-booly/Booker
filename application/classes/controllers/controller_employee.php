<?php

class Controller_Employee extends Controller
{
    private $_emplModel = null;

    public function __construct()
    {
        parent::__construct();
        $this->_emplModel = new Model_Employee();
    }

    public function action_index()
    {
        $employeeList = $this->_emplModel->getEmloyeeList();

        $this->view->set('employees', $employeeList);
        $this->view->show('index');
    }

    public function action_delete()
    {
        $id = Input::get('id');

        if ($id) {
            $this->_emplModel->deleteEmployee($id);
        }

        $this->redirect('employee/index/');
    }

    public function action_edit()
    {
        if (Input::occurred()) {
            $valid = $this->validateEmployee(false);

            if ($valid) {
                $id = Input::get('employee_id');
                $name = Input::get('employee_name');
                $email = Input::get('employee_email');

                $success = $this->_emplModel->editEmployee($id, $name, $email);

                if ($success) {
                    $this->view->set('success', 'Employee info succesfully edit!');
                } else {
                    $this->view->set('errors', array("Can't edit employee info"));
                }
            }
        } elseif (Input::get('id')) {
            $employee = $this->_emplModel->getEmloyeeByID(Input::get('id'));

            if ($employee) {
                $_POST['employee_name'] = $employee->name;
                $_POST['employee_email'] = $employee->email;
            }
        }

        $this->view->show('edit');
    }

    public function action_add()
    {
        if (Input::occurred()) {
            $valid = $this->validateEmployee();

            if ($valid) {
                $name = Input::get('employee_name');
                $email = Input::get('employee_email');

                $success = $this->_emplModel->addEmployee($name, $email);

                if ($success) {
                    $this->view->set('success', 'New employee succesfully added!');
                } else {
                    $this->view->set('errors', array("Can't add employee to DB"));
                }
            }
        }

        $this->view->show('add');
    }

    private function validateEmployee($unique = true)
    {
        $validation = new Validation();

        if ($unique) {
            $unique = $this->_emplModel->uniqueEmployee(Input::get('employee_name'));
        } else {
            $unique = '';
        }

        $validation->validate($_POST, array(
            'employee_name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
                'only_letters_space' => true,
                'unique' => $unique,
            ),
            'employee_email' => array(
                'required' => true,
                'email' => true,
            ),
        ));

        if (!$validation->validSuccess()) {
            $this->view->set('errors', $validation->error());

            return false;
        }

        return true;
    }
}
