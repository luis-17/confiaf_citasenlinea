<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProgramarCita extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security','reportes_helper','imagen_helper','fechas_helper','otros_helper'));
		$this->load->model(array('model_programar_cita',
								 'model_sede', 
								 'model_especialidad',
								 'model_prog_medico',
								 'model_prog_cita',
								 'model_control_evento_web'
								 ));

		//cache 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");
		$this->sessionCitasEnLinea = @$this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
	}

	public function cargar_planning(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$nuevafecha = strtotime ( '+6 day' , strtotime ( $allInputs['desde'] ) ) ;
		$allInputs['hasta'] = date ( 'Y-m-j' , $nuevafecha );
		

		/*print_r($allInputs);
		$allInputs['desde'] = date('d-m-Y', strtotime('23-03-2017'));
		$allInputs['hasta'] = date('d-m-Y', strtotime('29-03-2017'));*/

		if(empty($allInputs['itemSede'])){
			$arrData['planning']['mostrar'] = FALSE;
			$arrData['flag'] = 0;
			$arrData['planning']['mostraralerta'] = FALSE;
			$arrData['message'] = 'Seleccionar Sede.';
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
		    return;
		}

		if(empty($allInputs['itemEspecialidad']['id'])){
			$arrData['planning']['mostrar'] = FALSE;
			$arrData['flag'] = 0;
			$arrData['planning']['mostraralerta'] = TRUE;
			$arrData['message'] = 'Seleccionar Especialidad.';
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
		    return;
		}
		
		/*header*/
		$datos = array('anyo' => date("Y"));
		$feriados = $this->model_programar_cita->m_lista_feriados_cbo($datos); 
		$arrFeriados = array();
		foreach ($feriados as $row) {
			array_push($arrFeriados,  $row['fecha']); 
		}

		$arrFechas = get_rangofechas($allInputs['desde'],$allInputs['hasta'],TRUE);

		$arrHeader = array();
		foreach ($arrFechas as $fecha) {
			array_push($arrHeader, 
				array(
					'fecha' => $fecha,
					'dato' => date("d-m-Y", strtotime($fecha)),
					'class' =>  (date("w", strtotime($fecha)) == 0 || in_array($fecha, $arrFeriados)) ? 'fecha-header feriado ' : 'fecha-header ',
					'es_feriado' => (date("w", strtotime($fecha)) == 0 || in_array($fecha, $arrFeriados)) ? TRUE : FALSE
				)
			);
		}

		/*sidebar*/
		$sede = $this->model_sede->m_cargar_sede_por_id($allInputs['itemSede']['id']);
		$number = intval(explode(":",$sede['hora_final_atencion'])[0]);
		$hora_fin = str_pad($number-1,2,"0",STR_PAD_LEFT) . ':00:00';
		$horas = get_rangohoras($sede['hora_inicio_atencion'], $hora_fin);
		$arrHoras = array();
		foreach ($horas as $item => $hora) {
			array_push($arrHoras, 
				array(
					'hora' => $hora,
					'dato' => darFormatoHora($hora),
					'class' =>  'hora-sidebar ',
				)
			);	

			$segundos_horaInicial=strtotime($hora); 
			$segundos_minutoAnadir=30*60; 
			$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);
			$number = intval(explode(":",$nuevaHora)[0]);
			array_push($arrHoras, 
				array(
					'hora' => $nuevaHora,
					'dato' => darFormatoHora($nuevaHora),
					'class' => 'hora-sidebar '	,
				)
			);		
		}

		/*body*/
		$arrListado = array();
		$arrGridTotal = array();
				
		$lista = $this->model_programar_cita->m_cargar_programaciones($allInputs);

		$countHoras = count($arrHoras);
		$countFechas = count($arrFechas);
		$countProg = count($lista);

		$ind = 0;
		$i = 0;
		$j = 0;


		while ($j < $countFechas) {
			$fecha = $arrHeader[$j]['fecha'];
			$i = 0;
			$arrGrid = array();	
			while ($i < $countHoras){
				if(empty($arrGrid[$arrHoras[$i]['hora']])){
					$arrGrid[$arrHoras[$i]['hora']]= array(
						'dato' => '',
						'ids' => '',
						'idsmedicos' => '',
						'class' => 'cell-vacia',
						'rowspan' => 1,
						'unset' => FALSE,
					);
				}							

				foreach ($lista as $prog_row) {
					$segundos_horaFin=strtotime($prog_row['hora_fin']);
					$number = intval(explode(":",$prog_row['hora_fin'])[1]);
					if($number == 0 || $number == 30){
						$segundos_minutosResta=30*60;
						$hora_fin_comparar=date("H:i:s",$segundos_horaFin-$segundos_minutosResta);
					}else{
						$hora_fin_comparar=date("H:i:s",$segundos_horaFin);
					}

					if($arrHoras[$i]['hora'] >= $prog_row['hora_inicio'] 
						&& $arrHoras[$i]['hora'] <= $hora_fin_comparar 
						&& $arrFechas[$j] == $prog_row['fecha_programada']){
						$encontro = true;
						$arrGrid[$arrHoras[$i]['hora']]['dato'] = $prog_row['especialidad'];
						$arrGrid[$arrHoras[$i]['hora']]['ids'] .=  $prog_row['idprogmedico'] .',';
						$arrGrid[$arrHoras[$i]['hora']]['idsmedicos'] .= $prog_row['idmedico'] . ',';
						$arrGrid[$arrHoras[$i]['hora']]['class'] = 'cell-programacion ';						
						$arrGrid[$arrHoras[$i]['hora']]['especialidad'] = $prog_row['especialidad'];						
						$arrGrid[$arrHoras[$i]['hora']]['idespecialidad'] = $prog_row['idespecialidad'];
						$arrGrid[$arrHoras[$i]['hora']]['fecha_programada'] = date('d-m-Y',strtotime($prog_row['fecha_programada']));
						if(!empty($allInputs['itemMedico']['idmedico']) && $prog_row['idmedico'] == $allInputs['itemMedico']['idmedico']){
							$arrGrid[$arrHoras[$i]['hora']]['medico_favorito'] = true;
						}						
					}
				}

				$i++;	
			}

			$arrGridTotal[$fecha] = $arrGrid;
			$j++;
		}


		foreach ($arrGridTotal as $key => $grid) {
			$arrGridTotal[$key] = array_values($grid);
		}
		$arrGridTotal = array_values($arrGridTotal);

		$cellTotal = count($arrHoras);
		$cellColumn = count($arrFechas);

		foreach ($arrFechas as $i => $fecha) {
	    	$inicio = -1;
   			$fin = -1;
   			$anterior = '';
   			$ite = 0;
   			$total_prog = 0;
   			$arrGridTotal[$i][24]['total'] = $total_prog;
   			$arrGridTotal[$i][24]['unset'] = TRUE;
	    	foreach ($arrHoras as $row => $value) {  
	    		$actual =  empty($arrGridTotal[$i][$row]['ids']) ? '' : $arrGridTotal[$i][$row]['ids']; 

	    		if($ite == 0){
	    			$anterior = $actual;
	    			$ite++;
	    		}  	

	    		if($inicio == -1)
	    			$inicio = $row;

	    		if($actual != $anterior) {
	    			if($actual == ''){
	    				$fin = $row-1;
	    			}else if(!( 
	    				( strlen($actual) < strlen($anterior) && strpos($anterior,$actual) === TRUE) || 
	    				( strlen($actual) > strlen($anterior) && $anterior != '' && strpos($actual, $anterior) === TRUE) 
	    			)){
	    				$fin = $row-1;
	    			}	    				    			
	    		}		    		

	    		if($inicio != -1 && $fin != -1){
	    			$rowspan =($fin - $inicio) + 1;

	    			if(!empty($arrGridTotal[$i][$inicio]['ids'])){
	    				$total_prog += 1; 
	    				$arrGridTotal[$i][24]['total'] = $total_prog;
	    			}
	    			
	    			$arrGridTotal[$i][$inicio]['rowspan'] = $rowspan;
	    			$arrGridTotal[$i][$inicio]['inicio_bloque'] = $arrHoras[$inicio];
					$arrGridTotal[$i][$inicio]['fin_bloque'] = $arrHoras[$fin];
					for ($fila=$inicio+1; $fila <= $fin; $fila++) { 						
						$arrGridTotal[$i][$fila]['unset'] = TRUE;
						if(strlen($arrGridTotal[$i][$inicio+1]['ids']) < strlen($arrGridTotal[$i][$fila]['ids'])){
							$arrGridTotal[$i][$inicio]['ids'] = $arrGridTotal[$i][$fila]['ids'];
						}
						if(!empty($arrGridTotal[$i][$fila]['medico_favorito'])){
							$arrGridTotal[$i][$inicio]['medico_favorito'] = true;
						}
					} 				
					$inicio = $row;
					$fin = -1;
	    		}else if($row == $cellTotal-1){
	    			$fin = $row;
    				$rowspan =($fin - $inicio) + 1;
    				if(!empty($arrGridTotal[$i][$inicio]['ids'])){
	    				$total_prog += 1; 
	    				$arrGridTotal[$i][24]['total'] = $total_prog;
	    			}
					$arrGridTotal[$i][$inicio]['rowspan'] = $rowspan;
					$arrGridTotal[$i][$inicio]['inicio_bloque'] = $arrHoras[$inicio];
					$arrGridTotal[$i][$inicio]['fin_bloque'] = $arrHoras[$fin];
					for ($fila=$inicio+1; $fila <= $fin; $fila++) { 
    					$arrGridTotal[$i][$fila]['unset'] = TRUE;
    					if(strlen($arrGridTotal[$i][$inicio+1]['ids']) < strlen($arrGridTotal[$i][$fila]['ids'])){
							$arrGridTotal[$i][$inicio]['ids'] = $arrGridTotal[$i][$fila]['ids'];
						}
						if(!empty($arrGridTotal[$i][$fila]['medico_favorito'])){
							$arrGridTotal[$i][$inicio]['medico_favorito'] = true;
						}
    				}
    			}
				
				$anterior = empty($arrGridTotal[$i][$row]['ids']) ? '' : $arrGridTotal[$i][$row]['ids']; 

	    	}	    	 
	    }
		
		
		$arrData['datos'] = $lista;
		if(empty($lista)){
			$arrData['planning']['mostrar'] = FALSE;
			$arrData['flag'] = 0;
			$arrData['planning']['mostraralerta'] = TRUE;
			$arrData['message'] = 'No hay programaciones en la fecha seleccionada.';
		}else{
			$arrData['planning']['mostrar'] = TRUE;
		}
		$arrData['planning']['grid'] = $arrGridTotal;
		$arrData['planning']['horas'] = $arrHoras;
    	$arrData['planning']['fechas'] = $arrHeader;

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function ver_popup_turnos(){
		$this->load->view('programar-cita/turnos_formView');
	}

	public function ver_popup_aviso(){
		$this->load->view('mensajes/alerta');
	}

	public function cargar_turnos_disponibles(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$allInputs['ids'] =  substr($allInputs['ids'], 0, -1); 
		//print_r($allInputs['ids']);
		$ids = explode (',',$allInputs['ids']);

		$lista = $this->model_programar_cita->m_cargar_cupos_disponibles($ids);
		$arrGroup = array();
		foreach ($lista as $key => $row) {
			$hora_formato = darFormatoHora($row['hora_inicio_det']);
			$row['hora_formato'] = $hora_formato;
			$row['checked'] = false;
			if($row['si_adicional']	== 1){
				$row['si_adicional'] = true;
				$row['adicional'] = true;
			}else{
				$row['si_adicional'] = false;
				$row['adicional'] = false;
			}		

			$fecha_programada = date('d-m-Y',strtotime($row['fecha_programada']));		
			$medico = $row['med_nombres'] . ' ' . $row['med_apellido_paterno'] . ' ' . $row['med_apellido_materno'];			
			$arrGroup[$row['idprogmedico']]['cupos'][$row['iddetalleprogmedico']] = $row;
			$arrGroup[$row['idprogmedico']]['cupos'][$row['iddetalleprogmedico']]['medico'] = $medico;
			$arrGroup[$row['idprogmedico']]['cupos'][$row['iddetalleprogmedico']]['fecha_programada'] = $fecha_programada;
			$arrGroup[$row['idprogmedico']]['ambiente']['numero_ambiente'] = $row['numero_ambiente'];
			$arrGroup[$row['idprogmedico']]['ambiente']['idambiente'] = $row['idambiente'];

			$arrGroup[$row['idprogmedico']]['medico'] = $medico;	
			if( !empty($allInputs['medico']['idmedico']) && $row['idmedico'] == $allInputs['medico']['idmedico']){
				$arrGroup[$row['idprogmedico']]['medico_favorito'] = TRUE;
			}						
		}

		$arrData['datos']= $arrGroup;
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function lista_medicos_autocomplete(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true); // var_dump($allInputs); exit(); 		
		$lista = $this->model_programar_cita->m_cargar_medicos_autocomplete($allInputs);
		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado, 
				array(
					'idmedico' => $row['idmedico'],
					'medico' => $row['medico'],		
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

	public function actualizar_lista_citas_session(){		
	    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
	    //print_r($allInputs);
		//exit();

	    $arrData['datos'] = $_SESSION['sess_cevs_'.substr(base_url(),-8,7) ];
	    
	    $arrListado = array();
	    $total_productos = 0;

	    if(count($allInputs['compra']['listaCitas']) == 1){
	    	$arrData['datos']['compra']['itemEspecialidad'] = $allInputs['compra']['listaCitas'][0]['busqueda']['itemEspecialidad'];
	    	$arrData['datos']['compra']['itemSede'] = $allInputs['compra']['listaCitas'][0]['busqueda']['itemSede'];
	    }

	    foreach ($allInputs['compra']['listaCitas'] as $key => $cita) {
	    	$datosCupo = array(
	    		'iddetalleprogmedico' =>  $cita['seleccion']['iddetalleprogmedico'],
	    		'estado_cupo' => 5
	    	);
	    	$this->model_prog_medico->m_cambiar_estado_detalle_de_programacion($datosCupo);

	    	$datosCita = array(
	    		'idespecialidad' => $cita['busqueda']['itemEspecialidad']['id'],
	    		'especialidad' => $cita['busqueda']['itemEspecialidad']['descripcion'],
	    		'idsede' => $cita['busqueda']['itemSede']['idsede'],
	    		'idempresaadmin' => $cita['busqueda']['itemSede']['idempresaadmin'],
	    	);
	    	$row_precio = $this->model_especialidad->m_cargar_precio_cita($datosCita);
	    	//print_r($row_precio);
	    	$cita['producto'] = $row_precio[0];
	    	$total_productos += (float)$cita['producto']['precio_sede'];
	    	array_push($arrListado, $cita);	    	
	    }

	    $config = getConfig('pago');
	    $porcentaje = (float)$config['COMISION_VARIABLE_CULQI']; //3.99
	    $comision_fija = (float)$config['COMISION_FIJA_CULQI']; //0.50 S/.
	    $comision_fe = (float)$config['COMISION_FE']; //facturacion electronica 0.50 S/.
	    $igv = (float)$config['IGV'];
	    $total_servicio =  (($total_productos * $porcentaje / 100) + $comision_fija + $comision_fe) * $igv;
	    $total_pago = $total_productos + $total_servicio;

	    $arrData['datos']['compra']['listaCitas'] = $arrListado;
	    $arrData['datos']['compra']['totales']['total_productos'] = number_format(round($total_productos,2),2);
	    $arrData['datos']['compra']['totales']['total_servicio'] = number_format(round($total_servicio,2),2);
	    $arrData['datos']['compra']['totales']['total_pago'] = number_format(round($total_pago,2),2);
	    
	    $str_total = (string)$arrData['datos']['compra']['totales']['total_pago'];
	    $arrData['datos']['compra']['totales']['total_pago_culqi'] = str_replace('.', '',$str_total);

	    $this->session->set_userdata('sess_cevs_'.substr(base_url(),-8,7),$arrData['datos']);
	    $arrData['flag'] = 1;

	    $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode($arrData));
	    return;
  	}

	public function verifica_estado_cita(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Sólo puedes reprogramar citas sin atención y máximo 24horas previas a la fecha programada.';
		$arrData['flag'] = 0;		

		$cita = $this->model_prog_cita->m_consulta_cita($allInputs['idprogcita']);
		$hoy = strtotime(date('Y-m-d H:i:s'));
		$fecha_atencion = strtotime($cita['fecha_atencion_cita']); 
		$fecha_resta = strtotime($cita['fecha_atencion_cita']. ' -24hours'); 
					
		if($cita['estado_cita'] == 2 && $hoy < $fecha_resta){
			$arrData['message'] = 'Reprogramar';
			$arrData['flag'] = 1;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($arrData));
	}

	public function ver_popup_planning(){
		$this->load->view('programar-cita/planningReprogramar_formView');
	}

	public function cambiar_cita(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true); 
		$arrData['message'] = 'La cita no pudo ser reprogramada. Intente nuevamente';
    	$arrData['flag'] = 0;
    	
    	if($this->model_prog_cita->m_cita_tiene_atencion($allInputs['oldCita'])){
    		$arrData['message'] = 'No puede reprogramar citas con atención registrada.';
    		$arrData['flag'] = 0;
    		$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	} 

    	$cita = $this->model_prog_cita->m_consulta_cita($allInputs['oldCita']['idprogcita']);
    	if($cita['estado_cita'] != 2){
    		$arrData['message'] = 'Solo puede reprogramar citas en estado Pendiente.';
    		$arrData['flag'] = 0;
    		$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
    	}

    	$this->db->trans_start();
 		//cupo a disponible
		$data = array(
			'estado_cupo' => 2,
			'iddetalleprogmedico' => $allInputs['oldCita']['iddetalleprogmedico'],
			);	
		$resulDetalle = $this->model_prog_medico->m_cambiar_estado_detalle_de_programacion($data);
		//si cancelo y no es un adicional debo actualizar encabezado programacion
		$resultOldCuposCanal = FALSE;
		$resultOldCuposProg = FALSE;
		if(!$allInputs['oldCita']['si_adicional']){
			//actualizo cantidad de cupos disponibles y ocupados 
			$resultOldCuposCanal = $this->model_prog_medico->m_revertir_cupos_canales($allInputs['oldCita']); 
			$resultOldCuposProg = $this->model_prog_medico->m_revertir_cupos_programacion($allInputs['oldCita']);						
		}else{
			$resultOldCuposCanal = TRUE;
			$resultOldCuposProg = TRUE;
		}
     	
		if($resulDetalle && $resultOldCuposCanal && $resultOldCuposProg){
			//cambio id de cupo en cita
			$resultCita = FALSE;
			$datos = array(
				'idprogcita' => $allInputs['oldCita']['idprogcita'],
				'iddetalleprogmedico' => $allInputs['seleccion']['iddetalleprogmedico'],
				'fecha_atencion_cita' => $allInputs['seleccion']['fecha_programada'] . ' ' . $allInputs['seleccion']['hora_inicio_det'],
				);
			$resultCita = $this->model_prog_cita->m_cambiar_datos_en_cita($datos); //cita con nuevo iddetalleprogmedico

							
			if($resultCita ){
				/*if(!$allInputs['seleccion']['si_adicional']){
					$resultCuposCanal = $this->model_prog_medico->m_cambiar_cupos_canales($allInputs['seleccion']); 
					$resultCuposProg = $this->model_prog_medico->m_cambiar_cupos_programacion($allInputs['seleccion']);	
				}else{
					$resultCuposCanal = TRUE;
					$resultCuposProg = TRUE;
				}*/
				if($allInputs['seleccion']['idcanal'] != 3){
					$data = array(
						'idcanal' => 3,
						'iddetalleprogmedico' => $allInputs['seleccion']['iddetalleprogmedico'],
					);
					
					if($this->model_prog_medico->m_ajustar_canal($allInputs['seleccion']) &&
						$this->model_prog_medico->m_agregar_uno_canal_web($allInputs['seleccion']) &&
						$this->model_prog_medico->m_cambiar_canal_cupo($data)){

						$seleccion = array(
							'idprogmedico' => $allInputs['seleccion']['idprogmedico'],
							'idcanal' => 3,
						);

						if(!$allInputs['seleccion']['si_adicional']){
							$resultCuposCanal = $this->model_prog_medico->m_cambiar_cupos_canales($seleccion); 
							$resultCuposProg = $this->model_prog_medico->m_cambiar_cupos_programacion($seleccion); 
						}else{
							$resultCuposCanal = TRUE;
							$resultCuposProg  = TRUE;
						}
					} 
				}else{							
					/*solo si NO es adicional*/
					if(!$allInputs['seleccion']['si_adicional']){
						$data = array(
							'idprogmedico' => $cita['seleccion']['idprogmedico'],
							'idcanal' => $cita['seleccion']['idcanal']
							);
						$resultCuposCanal = $this->model_prog_medico->m_cambiar_cupos_canales($data);

						$data = array(
							'idprogmedico' => $cita['seleccion']['idprogmedico'],
							);
						$resultCuposProg = $this->model_prog_medico->m_cambiar_cupos_programacion($data);
					}else{
						$resultCuposCanal = TRUE;
						$resultCuposProg  = TRUE;
					}												
				}
				
				$data = array(
					'estado_cupo' => 1,
					'iddetalleprogmedico' => $allInputs['seleccion']['iddetalleprogmedico'],
					);	
				$resulDetalleNuevo = $this->model_prog_medico->m_cambiar_estado_detalle_de_programacion($data);

				
				if($resulDetalle && $resultOldCuposCanal && $resultOldCuposProg && $resultCita && $resultCuposCanal && $resultCuposProg && $resulDetalleNuevo){
					$arrData['message'] = 'La cita ha sido reprogramada correctamente';
	    			$arrData['flag'] = 1;					
					//envio de mails
			        $listaDestinatarios = array();
			        array_push($listaDestinatarios, $this->sessionCitasEnLinea['email']);			        
			        $setFromAleas = 'Vitacloud';
			        $subject = 'Reprogramación de Cita';
					$cuerpo = $this->genera_body_mail_reprogramacion($allInputs);

					$dataPDF = array(
						'idprogcita' => $allInputs['oldCita']['idprogcita'],
						'fecha_formato' => $allInputs['seleccion']['fecha_programada'],
						'hora_inicio_formato' => $allInputs['seleccion']['hora_inicio_det'],
						'itemSede' => $allInputs['oldCita']['itemSede'],
						'itemEspecialidad' => $allInputs['oldCita']['itemEspecialidad'],
						'itemFamiliar' => $allInputs['oldCita']['itemFamiliar'],
						'itemMedico' => $allInputs['oldCita']['itemMedico'],
						'itemAmbiente' => $allInputs['oldCita']['itemAmbiente'],
						);

					//assets/img/dinamic/pdfTemporales/tempPDFreprogramacion.pdf

					$result = enviar_mail($subject, $setFromAleas,$cuerpo,$listaDestinatarios);
					$arrData['flagMail']  = $result[0]['flag'];
					$arrData['msgMail']  = $result[0]['msgMail'];

					//generacion notificacion
					$texto_notificacion = generar_notificacion_evento(18, 'key_citas_en_linea', $allInputs);
					$data = array(						
						'idtipoevento' => 18,
						'identificador' => $allInputs['oldCita']['idprogcita'],
						'idusuarioweb' => $this->sessionCitasEnLinea['idusuario'],
						'texto_notificacion' => $texto_notificacion,
						'idresponsable' => $this->sessionCitasEnLinea['idusuario'],
						'fecha_evento' => date('Y-m-d H:i:s'),
						);
					$this->model_control_evento_web->m_registrar_notificacion_evento($data);
				}
			}						
		}	    		
    	
		$this->db->trans_complete();   

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function ver_popup_confirmacion(){
		$this->load->view('programar-cita/confirmaReprogramacion_formView');
	}

	private function genera_body_mail_reprogramacion($allInputs){
  		$cuerpo = '<!DOCTYPE html>
					<html lang="es">
					<head>
					    <meta charset="utf-8">
					    <meta name="author" content="Vitacloud">								    
					</head>';
        $cuerpo .= '<body style="font-family: sans-serif;padding: 10px 40px;" > 
	                  <div style="text-align: center;">
	                    <img style="max-width: 800px;" alt="Hospital Vitacloud" src="'.base_url(). 'assets/img/dinamic/empresa/header-mail.jpg">
	                  </div>';
	    $cuerpo .= '  <div style="max-width: 750px;align-content: center;margin-left: auto; margin-right: auto;padding-left: 10%; padding-right: 10%;">';
        $cuerpo .= '  <div style="font-size:16px;">  
                        Estimado(a) usuario: '. $this->sessionCitasEnLinea['paciente'] .',';
        $cuerpo .= '    Has reprogramado una de tus citas.</br> Nueva cita:';
        $cuerpo .=    '</div>';
  		
  		$cuerpo .=	'  <div style="">
	                    <div class="cita" style="font-size: 13px;">
	                      <div style="height: 30px;" >
	                      	<div style="width:31px;float: left;">👪 </div>
	                      	Cita para: <span class="cita-familiar">'. strtoupper($allInputs['oldCita']['itemFamiliar']['paciente']).' ('.$allInputs['oldCita']['itemFamiliar']['parentesco'].')</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">🏨 </div> 
	                      	Sede: <span class="cita-sede">'.$allInputs['oldCita']['itemSede']['sede'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;font-size: 20px;">⚕️ </div> 
	                      	Especialidad: <span class="cita-esp">'.$allInputs['oldCita']['itemEspecialidad']['especialidad'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">👨‍ </div> 
	                      	Médico: <span class="cita-medico">'.$allInputs['seleccion']['medico'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">🗓 </div> 
	                      	Fecha: <span class="cita-turno">'.$allInputs['seleccion']['fecha_programada'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">🕑 </div>
	                      	Hora: <span class="cita-turno">'.$allInputs['seleccion']['hora_formato'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;"> 📍 </div>
	                      	Consultorio: <span class="cita-ambiente">'.$allInputs['seleccion']['numero_ambiente'].'</span>
	                      </div>
	                    </div>                            
	                    </div>';
	    $cuerpo .=    '</div>';
	    $cuerpo .= '<div style="text-align: center;">
	    				<img style="max-width: 800px;" alt="Hospital Vitacloud" src="'.base_url(). 'assets/img/dinamic/empresa/footer-mail.jpg">
	    			</div>';
      	$cuerpo .= '</body>';
        $cuerpo .= '</html>';

      	return $cuerpo;
  	}

  	public function report_comprobante_cita(){ 
	    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
	    $arrDataPDF = genera_pdf_cita($allInputs);
	    $arrData['dataPDF'] = $arrDataPDF;
	    $arrData['message'] = 'OK';
	    $arrData['flag'] = 1;
	    $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode($arrData));
	}

	public function ver_popup_compra_exitosa(){
		$this->load->view('mensajes/compra-exitosa');
	}

	public function libera_lista_citas_session(){		
	    $allInputs = json_decode(trim($this->input->raw_input_stream),true);		
    	$arrData['flag'] = 0;
	    $arrData['message'] = 'Ha ocurrido un error. Contacta nuestro personal de soporte a citasenlinea@vitacloud.pe';

	    $arrData['datos'] = $_SESSION['sess_cevs_'.substr(base_url(),-8,7) ];

	    $error = FALSE;
	    foreach ($allInputs['compra']['listaCitas'] as $key => $cita) {
	    	$datosCupo = array(
	    		'iddetalleprogmedico' =>  $cita['seleccion']['iddetalleprogmedico'],
	    		'estado_cupo' => 2
	    	);
	    	if(!$this->model_prog_medico->m_cambiar_estado_detalle_de_programacion($datosCupo)){
	    		$error = TRUE;	    		 
	    	}	    	
	    }

	    $arrData['datos']['compra']['listaCitas'] = [];
	    $arrData['datos']['timer']['activeCount'] = FALSE;
	    $this->session->set_userdata('sess_cevs_'.substr(base_url(),-8,7),$arrData['datos']);
	    if(!$error){
	    	$arrData['flag'] = 1;
	    	$arrData['message'] = 'liberados cupos';	    	
	    }

	    $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode($arrData));
	    return;
  	}

  	public function libera_cupo_quitar_lista(){		
	    $allInputs = json_decode(trim($this->input->raw_input_stream),true);		
	    $arrData['flag'] = 0;
	    $arrData['message'] = 'Ha ocurrido un error. Contacta nuestro personal de soporte a citasenlinea@vitacloud.pe';

	    $datosCupo = array(
    		'iddetalleprogmedico' =>  $allInputs['seleccion']['iddetalleprogmedico'],
    		'estado_cupo' => 2
    	);
    	
    	if($this->model_prog_medico->m_cambiar_estado_detalle_de_programacion($datosCupo)){
    		$arrData['flag'] = 1;
    		$arrData['message'] = 'liberados cupos';
    	}    	

	    $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode($arrData));
	    return;
  	}
}