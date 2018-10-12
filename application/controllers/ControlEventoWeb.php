<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ControlEventoWeb extends CI_Controller { 
	public function __construct()	{
		parent::__construct();
		$this->load->helper(array('security', 'fechas_helper', 'otros_helper'));
		$this->load->model(array('model_control_evento_web','model_prog_cita'));
		
		//cache 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");		
		$this->sessionCitasEnLinea = @$this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
	}

	public function update_leido_notificacion(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = '';
		$arrData['flag'] = 1;
		
		if($allInputs['estado_uce'] === 1){
			if(!$this->model_control_evento_web->m_update_leido_notificacion($allInputs['idusuariowebcontrolevento'])){
				$arrData['message'] = 'Ha ocurrido un error. Intente nuevamente';
				$arrData['flag'] = 0;
			}
		}				

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function ver_popup_notificacion_evento(){
		$this->load->view('control-evento/viewDetalleNotificacion_formView');
	}

	public function carga_objeto_notificacion(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = '';
		$arrData['flag'] = 1;
		
		if($allInputs['idtipoevento'] === 18 || $allInputs['idtipoevento'] === 17){
			//cargar la cita
			$row = $this->model_prog_cita->m_carga_esta_cita($allInputs['identificador']); 
			if(!empty($row)){
				$medico = $row['med_nombres'] . ' ' . $row['med_apellido_paterno'] . ' ' . $row['med_apellido_materno'];
				$paciente = ucwords(strtolower( $row['nombres'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']));

				if($row['estado_cita'] == 2){
					$icon_cita = 'fa fa-calendar';
					$color_cita = 'btn-warning';
				}else if($row['estado_cita'] == 5){
					$icon_cita = 'fa fa-check-square-o';
					$color_cita = 'btn-success';
				}

				$cita = array(
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
					'nombre_usuario'  => $this->sessionCitasEnLinea['paciente']
				);
				$arrData['cita'] = $cita;
			}
		}				

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));		
	}
}