<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sede extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security'));
		$this->load->model(array('model_sede'));
		//cache 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");
		//$this->sessionHospital = @$this->session->userdata('sess_vs_'.substr(base_url(),-8,7));
		//if(!@$this->user) redirect ('inicio/login');
		//$permisos = cargar_permisos_del_usuario($this->user->idusuario);
	}

	public function lista_sedes_cbo(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		if( isset($allInputs['search']) ){
			$lista = $this->model_sede->m_cargar_sedes_cbo($allInputs);
		}else{
			$lista = $this->model_sede->m_cargar_sedes_cbo();
		}
		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado, 
				array(
					'id' => $row['idsede'],
					'idsede' => $row['idsede'],
					'descripcion' => $row['descripcion'],
					'hora_inicio' => $row['hora_inicio_atencion'],
					'hora_final' => $row['hora_final_atencion'],
					'idempresaadmin' => $row['idempresaadmin'],
					'idsedeempresaadmin' => $row['idsedeempresaadmin'],
				)
			);
		}
    	$arrData['datos'] = $arrListado;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}