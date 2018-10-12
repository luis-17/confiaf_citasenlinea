<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parentesco extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security', 'otros_helper','imagen_helper'));
		$this->load->model(array('model_parentesco'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");		
		$this->sessionCitasEnLinea = @$this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
	}

  public function lista_parentesco_cbo(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    if( isset($allInputs['search']) ){
      $lista = $this->model_parentesco->m_carga_parentesco_cbo($allInputs);
    }else{
      $lista = $this->model_parentesco->m_carga_parentesco_cbo();
    }

    $arrListado = array();
    foreach ($lista as $row) {
      array_push($arrListado, 
        array(
          'id' => (int)$row['idparentesco'],
          'idparentesco' => (int)$row['idparentesco'],
          'descripcion' => $row['descripcion'],
          'estado_p' => $row['estado_p'],
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
