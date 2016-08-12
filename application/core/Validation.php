<?php

class Validation
{
    private $_errors = array();
    private $_valid = false;

    private function errorHappened($error)
    {
        $this->_errors[] = $error;
    }

    public function validate($data, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $clause) {
                $value = $data[$item];
                $this->checkRules($value, $item, $rule, $clause);
            }
        }

        if (empty($this->_errors)) {
            $this->_valid = true;
        }
    }

    protected function checkRules($value, $item, $rule, $clause)
    {
        $value = trim($value);

        if (empty($value) && ($rule === 'required')) {
            $this->errorHappened($item.' is required');
        } elseif (!empty($value)) {
            switch ($rule) {
                case 'min':
                    if (strlen($value) < $clause) {
                        $this->errorHappened($item
                            .' minimum characters is '.$clause);
                    }
                    break;
                case 'max':
                    if (strlen($value) > $clause) {
                        $this->errorHappened($item
                            .' maximum characters is '.$clause);
                    }
                    break;
                case 'only_letters_space':
                    if (!preg_match('/^[a-zA-Z ]*$/', $value)) {
                        $this->errorHappened($item
                            .' only letters and white space allowed');
                    }
                    break;
                case 'email':
                    $p = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)
                    || !preg_match($p, $value)) {
                        $this->errorHappened('Invalid email format');
                    }
                    break;
                case 'unique':
                    if (!empty($clause)) {
                        $this->errorHappened($item
                            .' already exists');
                    }
                    break;
            }
        }
    }

    public function error()
    {
        return $this->_errors;
    }

    public function validSuccess()
    {
        return $this->_valid;
    }
}
