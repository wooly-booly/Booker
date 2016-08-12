<?php

class Input
{
	public static function occurred($type = 'post')
	{
		if ($type == 'post' && !empty($_POST)) 
			return true;
		
		if ($type == 'get' && !empty($_GET)) 
			return true;
		
		return false;
	}
	
	public static function get($value)
	{
		if (isset($_POST[$value]))
			return self::escape($_POST[$value]);
		elseif (isset($_GET[$value]))
			return self::escape($_GET[$value]);
		else
			return '';
	}

	public static function escape($value)
	{
		$value = trim($value);
		$value = htmlentities($value, ENT_QUOTES, 'UTF-8');

		return $value;
	}
}