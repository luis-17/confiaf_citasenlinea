<?php
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Qr_php {
		public function __construct()
		{
			require_once (dirname(__FILE__)."/phpqrcode/qrlib.php");
		}
	}

?>