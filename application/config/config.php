<?php

$config = Config::instance();

// Set default time zone
date_default_timezone_set("Europe/Kiev");

// Base uri for site
$config->set('base_uri', 'http://booker.loc');

// DB Configuration
$config->set('db_user', 'root');
$config->set('db_pass', 'root');
$config->set('db_host', 'localhost');
$config->set('db_name', 'booker');

// Hour System, available: 12 or 24
$config->set('hour_system', '12');  

// First day of week, available: Monday or Sunday
$config->set('first_week_day', 'Sunday');

// Number of boardrooms 
$config->set('boardrooms_number', '3');

// Number of years in drop down menu (for add appointments)
$config->set('years_number', '5');

// Max dureation for recurring appointments
// (How many forward, you can book appointments)
$config->set('max_duration_monthly', '6'); 
$config->set('max_duration_weekly', '4'); 
$config->set('max_duration_biweekly', '16'); 

unset($config);

