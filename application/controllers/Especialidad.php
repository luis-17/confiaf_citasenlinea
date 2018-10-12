<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Especialidad extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security'));
		$this->load->model(array('model_especialidad'));
		//cache 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");
	}

	public function lista_especialidades_prog_asistencial(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$lista = $this->model_especialidad->m_cargar_especialidades_prog_asistencial($allInputs);
		$arrListado = array();
		foreach ($lista as $row) { 
			array_push($arrListado, 
				array( 
					'id' => $row['idespecialidad'],
					'descripcion' => $row['especialidad']
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