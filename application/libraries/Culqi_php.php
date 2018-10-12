<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Culqi_php {
	public function __construct()
	{
		require_once('Requests.php');
		Requests::register_autoloader();
		require_once('culqi.php');
	}
	/*public function Culqi_php(){
		
	}*/
}

?>