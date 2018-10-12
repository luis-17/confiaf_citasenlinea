<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HistorialCitas extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security', 'fechas_helper', 'otros_helper'));
		$this->load->model(array('model_historial_citas'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");		
		$this->sessionCitasEnLinea = @$this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
	}

  public function lista_historial_citas(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);

    $allInputs['idusuario'] = $this->sessionCitasEnLinea['idusuario'];
    $lista = $this->model_historial_citas->m_carga_historial_citas($allInputs);

    $arrListado = array();
    foreach ($lista as $row) {
      $medico = $row['med_nombres'] . ' ' . $row['med_apellido_paterno'] . ' ' . $row['med_apellido_materno'];
      $paciente = ucwords(strtolower( $row['nombres'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']));

      if($row['estado_cita'] == 2){
        $icon_cita = 'fa fa-calendar';
        $color_cita = 'btn-warning';
      }else if($row['estado_cita'] == 5){
        $icon_cita = 'fa fa-check-square-o';
        $color_cita = 'btn-success';
      }

      if($row['estado_comprobante'] == 1){
        $icon_comprobante = 'fa fa-file-text-o';
        $color_comprobante = '#ce1d19';
        $font_size = '15px;';
      }else if($row['estado_comprobante'] == 2){
        $icon_comprobante = 'fa fa-info-circle';
        $color_comprobante = '#00bcd4';
        $font_size = '18px;';
      }

      array_push($arrListado, 
        array(
          'idusuariowebcita' => (int)$row['idusuariowebcita'],
          'idusuarioweb' => (int)$row['idusuarioweb'],
          'idprogcita' => $row['idprogcita'],
          'estado_cita' => (int)$row['estado_cita'],
          'icon_cita' => $icon_cita,
          'color_cita' => $color_cita,
          'idcliente' => (int)$row['idcliente'],
          'iddetalleprogmedico' => (int)$row['iddetalleprogmedico'],
          'idcanal' => (int)$row['idcanal'],          
          'hora_inicio_det' => $row['hora_inicio_det'],
          'hora_inicio_formato' => darFormatoHora($row['hora_inicio_det']),
          'hora_fin_det' => $row['hora_fin_det'],          
          'hora_fin_formato' => darFormatoHora($row['hora_fin_det']),
          'si_adicional' => $row['si_adicional'],
          'numero_cupo' => $row['numero_cupo'], 
          'idprogmedico' => (int)$row['idprogmedico'], 
          'fecha_programada' => $row['fecha_programada'],         
          'fecha_formato' => date('d-m-Y',strtotime($row['fecha_programada'])),
          
          'itemAmbiente' => array(
            'idambiente' => (int)$row['idambiente'],       
            'numero_ambiente' => $row['numero_ambiente'],
            ), 
          'itemEspecialidad' => array(
            'id' => (int)$row['idespecialidad'],
            'idespecialidad' => (int)$row['idespecialidad'],
            'especialidad' => $row['especialidad'], 
            ),
          'itemMedico' => array(
            'idmedico' => (int)$row['idmedico'],
            'medico' => $medico,   
            ),  
          'itemSede' => array(
            'id' => (int)$row['idsede'],   
            'idsede' => (int)$row['idsede'],   
            'sede' => $row['sede'],   
            ),
          'itemFamiliar' => array(
            'idusuariowebpariente' => empty($row['idusuariowebpariente']) ? 0 : $row['idusuariowebpariente'],
            'idparentesco' => empty($row['idparentesco']) ? 0 : $row['idparentesco'],
            'parentesco' => empty($row['parentesco']) ? 'TITULAR' : $row['parentesco'],
            'paciente' => $paciente, 
            ), 
          'nombre_usuario'  => $this->sessionCitasEnLinea['paciente'],
          'idventa'  => $row['idventa'],
          'iddetalle'  => $row['iddetalle'],
          'numero_comprobante'  => $row['numero_comprobante'],
          'fecha_comprobante'  => $row['fecha_comprobante'],
          'estado_comprobante'  => $row['estado_comprobante'],
          'icon_comprobante'  => $icon_comprobante,
          'font_size'  => $font_size,
          'color_comprobante'  => $color_comprobante,
          'nombre_archivo'  => $row['nombre_archivo'],
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
