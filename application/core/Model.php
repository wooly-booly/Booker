<?php

class Model
{
	protected $db = null;
	
	public function __construct()
	{
		// get DB instance
		$conf = Config::instance();
		$this->db = DB::instance();
		$this->db->connect( $conf->get('db_host'), $conf->get('db_user'), 
							$conf->get('db_pass'), $conf->get('db_name'));
		unset($conf);
	}

}