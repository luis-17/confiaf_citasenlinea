<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security', 'fechas_helper', 'otros_helper'));
		$this->load->model(array('model_programar_cita',
								 'model_venta',
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

  	public function validar_citas(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$ocupado = FALSE;
		$inhabilitado = FALSE;
		$arrData['flag'] = 0;
		$arrData['message'] = 'Hemos refrescado tu lista de citas, tenias seleccionadas citas que ya fueron tomadas.';
		$arrayEliminar= array();
		$arrayDefinitivas= array();
	
		foreach ($allInputs['compra']['listaCitas'] as $key => $cita) {			
			$cupo = $this->model_prog_medico->m_consulta_cupo($cita['seleccion']['iddetalleprogmedico']);
			$fechaSeleccionada = strtotime($cita['seleccion']['fecha_programada'] . ' ' .$cita['seleccion']['hora_fin_det']);

			if(($cupo['estado_cupo'] != 5 && $cupo['estado_cupo'] != 2)){
				$ocupado = TRUE;
				array_push($arrayEliminar, $key);				
			}else if($fechaSeleccionada < time()){
				$inhabilitado = TRUE;
				array_push($arrayEliminar, $key);	
			}else{
				array_push($arrayDefinitivas, $cita);
			}
		}
  		
  		if($ocupado){
  			$arrData['flag'] = 0;
			$arrData['message'] = 'Hemos refrescado tu lista de citas, tenias seleccionadas citas que ya fueron tomadas.';
  		}

  		if($inhabilitado){
  			$arrData['flag'] = 0;
			$arrData['message'] = 'Hemos refrescado tu lista de citas, tenias seleccionadas citas de turnos pasados.';
  		}

  		if(!$ocupado && !$inhabilitado){
  			$arrData['flag'] = 1;
			$arrData['message'] = 'Ok';
  		}

  		$arrData['listaEliminar'] = $arrayEliminar;
  		$arrData['listaDefinitiva'] = $arrayDefinitivas;
  		$this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode($arrData));
	      return;
  		
	}

  	public function generar_venta_citas(){
  		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$config = getConfig('pago', $allInputs['usuario']['compra']['itemSede']['idsedeempresaadmin'], TRUE);
		$arrData['flag'] = 0;
		$arrData['message'] = 'Ha ocurrido un error procesando tu pago. Contacta a nuestro equipo de soporte mediante: citasenlinea@vitacloud.pe';
		
		$ocupado = FALSE;
		$arrayEliminar = array();
		$arrayDefinitivas= array();
		foreach ($allInputs['usuario']['compra']['listaCitas'] as $key => $cita) {			
			$cupo = $this->model_prog_medico->m_consulta_cupo($cita['seleccion']['iddetalleprogmedico']);
			if($cupo['estado_cupo'] != 5 && $cupo['estado_cupo'] != 2){
				$ocupado = TRUE;
				array_push($arrayEliminar, $key);				
			}else{
				array_push($arrayDefinitivas, $cita);
			}
		}

		if($ocupado){
			$arrData['flag'] = 2;
			$arrData['message'] = 'Una o más de tus citas seleccionadas, ya no está disponible. Selecciona nuevas citas.';
			$arrData['listaEliminar'] = $arrayEliminar;
	  		$arrData['listaDefinitiva'] = $arrayDefinitivas;
	  		$this->output
		        ->set_content_type('application/json')
		        ->set_output(json_encode($arrData));
		      return;
		}

		$this->db->trans_begin();	  
		$listaCitas = $allInputs['usuario']['compra']['listaCitas'];
		$listaCitasGeneradas = $allInputs['usuario']['compra']['listaCitas'];
		$listNotificaciones = array();
		
		//registro de la venta
		$orden_venta = $this->genera_codigo_orden($allInputs['usuario']['compra']['itemSede']['id'],$allInputs['usuario']['compra']['itemSede']['idsedeempresaadmin']);
		$idmediopago = $this->model_venta->m_consulta_medio_pago($allInputs['token']['iin']['card_brand']);
		$dataVenta = array(
			'idcliente' => $allInputs['usuario']['idcliente'],
			'idusuarioweb' => $allInputs['usuario']['idusuario'],
			'orden_venta' => $orden_venta,
			'idtipodocumento' => 11, //de momento solo boleta
			'sub_total' => round($allInputs['usuario']['compra']['totales']['total_pago'] / $config['IGV'],2), 
			'total_igv' => round($allInputs['usuario']['compra']['totales']['total_pago'] - ($allInputs['usuario']['compra']['totales']['total_pago'] / $config['IGV']),2),
			'monto_comision' => $allInputs['usuario']['compra']['totales']['total_servicio'], 
			'monto_productos' => $allInputs['usuario']['compra']['totales']['total_productos'], 
			'total_a_pagar' => $allInputs['usuario']['compra']['totales']['total_pago'],
			'idmediopago' => $idmediopago, //según la marca de tarjeta
			'idsedeempresaadmin' => $allInputs['usuario']['compra']['itemSede']['idsedeempresaadmin'],
			'fecha_venta' =>  date('Y-m-d H:i:s'),
			'createdAt' =>  date('Y-m-d H:i:s'),
			'updatedAt' =>  date('Y-m-d H:i:s'),
		);
		$resultVenta = $this->model_venta->m_registrar_venta($dataVenta);
		$idventa = GetLastId('idventa','ce_venta');

		if($resultVenta){
			$error = FALSE;
			foreach ($listaCitas as $key => $cita) {
				$result = FALSE;
				$resultDetalle = FALSE;
				$resultCanales = FALSE;
				$resultProg = FALSE;

				if($cita['busqueda']['itemFamiliar']['idusuariowebpariente'] == 0){
					$cliente = $allInputs['usuario']['idcliente'];
				}else{
					$cliente = $cita['busqueda']['itemFamiliar']['idclientepariente'];
				} 

				//registro de cita
				$data = array(
					'iddetalleprogmedico' => $cita['seleccion']['iddetalleprogmedico'],
					'fecha_reg_reserva' => date('Y-m-d H:i:s'),
					'fecha_reg_cita' => date('Y-m-d H:i:s'),
					'fecha_atencion_cita' => $cita['seleccion']['fecha_programada']. " " . $cita['seleccion']['hora_inicio_det'],
					'idcliente' => $cliente,
					'idempresacliente' =>  NULL,
					'estado_cita' => 2,
					'idproductomaster' => $cita['producto']['idproductomaster'],
					'idsedeempresaadmin' => $cita['producto']['idsedeempresaadmin'],
					);
				$result = $this->model_prog_cita->m_registrar($data);

				if($result){
					$idprogcita = GetLastId('idprogcita','pa_prog_cita');
					//registro de detalle venta
					$dataDetalle = array(
						'idventa' => $idventa,
						'idespecialidad' => $cita['busqueda']['itemEspecialidad']['id'],
						//'idespecialidad' => $allInputs['usuario']['compra']['itemEspecialidad']['id'],
						'cantidad' => 1,
						'precio_unitario' => $cita['producto']['precio_sede'],
						'total_detalle' => $cita['producto']['precio_sede'],
						'idproductomaster' => $cita['producto']['idproductomaster'],
						'idprogcita' => $idprogcita,
						'createdAt' =>  date('Y-m-d H:i:s'),
						'updatedAt' =>  date('Y-m-d H:i:s'),
					);
					$resultVentaDetalle = $this->model_venta->m_registrar_detalle_venta($dataDetalle);
					$iddetalle = GetLastId('iddetalle','ce_detalle');
					//fin registro detalle venta

					if($resultVentaDetalle){
						$listaCitasGeneradas[$key]['idprogcita'] =  $idprogcita;
						$listaCitasGeneradas[$key]['idventa'] =  $idventa;
						$listaCitasGeneradas[$key]['iddetalle'] =  $iddetalle;
						$listaCitasGeneradas[$key]['itemFamiliar'] =  $cita['busqueda']['itemFamiliar'];
						$listaCitasGeneradas[$key]['itemSede'] =  $cita['busqueda']['itemSede'];
						$listaCitasGeneradas[$key]['itemEspecialidad'] =  $cita['busqueda']['itemEspecialidad'];
						$listaCitasGeneradas[$key]['itemAmbiente'] =  array(
																			'idambiente' => $cita['seleccion']['idambiente'],
																			'numero_ambiente' => $cita['seleccion']['numero_ambiente'],
																		);
						$listaCitasGeneradas[$key]['itemMedico'] =  array(
																			'idmedico' => $cita['seleccion']['idmedico'],
																			'medico' => $cita['seleccion']['medico'],
																		);

						$texto_notificacion = generar_notificacion_evento(17, 'key_citas_en_linea', $cita);
						$data = array(
							'fecha_evento' => date('Y-m-d H:i:s'),
							'idtipoevento' => 17,
							'identificador' => $idprogcita,
							'idusuarioweb' => $this->sessionCitasEnLinea['idusuario'],
							'texto_notificacion' => $texto_notificacion,
							'idresponsable' => $this->sessionCitasEnLinea['idusuario'],
							);
						array_push($listNotificaciones, $data);						

						//actualización de programacion
							$data = array(
								'iddetalleprogmedico' => $cita['seleccion']['iddetalleprogmedico'],
								'estado_cupo' => 1
								);
							$resultDetalle = $this->model_prog_medico->m_cambiar_estado_detalle_de_programacion($data);
							/*si NO ES CUPO WEB ajusto los canales antes de actualizar*/
							if($cita['seleccion']['idcanal'] != 3){
								$data = array(
									'idcanal' => 3,
									'iddetalleprogmedico' => $cita['seleccion']['iddetalleprogmedico'],
								);
								
								if($this->model_prog_medico->m_ajustar_canal($cita['seleccion']) &&
									$this->model_prog_medico->m_agregar_uno_canal_web($cita['seleccion']) &&
									$this->model_prog_medico->m_cambiar_canal_cupo($data)){

									$seleccion = array(
										'idprogmedico' => $cita['seleccion']['idprogmedico'],
										'idcanal' => 3,
									);

									if(!$cita['seleccion']['si_adicional']){
										$resultCanales = $this->model_prog_medico->m_cambiar_cupos_canales($seleccion); 
										$resultProg = $this->model_prog_medico->m_cambiar_cupos_programacion($seleccion); 
									}else{
										$resultCanales = TRUE;
										$resultProg  = TRUE;
									}
								} 
							}else{							
								/*solo si NO es adicional*/
								if(!$cita['seleccion']['si_adicional']){
									$data = array(
										'idprogmedico' => $cita['seleccion']['idprogmedico'],
										'idcanal' => $cita['seleccion']['idcanal']
										);
									$resultCanales = $this->model_prog_medico->m_cambiar_cupos_canales($data);

									$data = array(
										'idprogmedico' => $cita['seleccion']['idprogmedico'],
										);
									$resultProg = $this->model_prog_medico->m_cambiar_cupos_programacion($data);
								}else{
									$resultCanales = TRUE;
									$resultProg  = TRUE;
								}												
							}
						//fin actualizacion de programacion -- 

						//registro de relacion cita - usuario web
						if($resultDetalle && $resultCanales && $resultProg){
							$data=array(
								'idusuarioweb' => $allInputs['usuario']['idusuario'],
								'idprogcita' => $idprogcita,
								'idcliente' => $cliente,
								'idparentesco' => $cita['busqueda']['itemFamiliar']['idusuariowebpariente']
								);
							if(!$this->model_programar_cita->m_registrar_usuarioweb_cita($data)){
								$error = TRUE;
							}
						}else{
							$error = TRUE;
						}
					}else{
						$error = TRUE;
					}
					
				}else{
					$error = TRUE;
				}					
			}

			if(!$error){
				try{
					$this->load->library(array('Culqi_php')); 
					$culqi = new Culqi\Culqi(array('api_key' => $config['CULQI_PRIVATE_KEY']));
					$charge = $culqi->Charges->create(
						array(
							"amount" 		=> $allInputs['usuario']['compra']['totales']['total_pago_culqi'],
							"currency_code" => "PEN",
							"email" 		=> $allInputs['token']['email'],
							"description" 	=> $config['DESCRIPCION_CARGO'],
							"installments" 	=> 0,
							"source_id" 	=> $allInputs['token']['id'] ,
							"metadata" 		=> array(
												"orden_venta" => $orden_venta,
												"idventa" => $idventa,
												"idusuario" => $allInputs['usuario']['idusuario']
							    				)						   
							)
					);					

					$arrData['datos']['cargo'] = get_object_vars($charge);			
				  	if(!empty($arrData['datos']['cargo']['id'])){
				  		$arrData['flag'] = 1;	
				  		$arrData['datos']['cargo']['outcome']= get_object_vars($arrData['datos']['cargo']['outcome']);	 
				  		//registro del pago
						$dataPago = array(
							'idusuarioweb' => $allInputs['usuario']['idusuario'],
							'idventa' => $idventa,
							'idculqitracking' => $arrData['datos']['cargo']['id'],
							'codigo_referencia_culqi' => $arrData['datos']['cargo']['reference_code'],
							'descripcion_cargo' => $config['DESCRIPCION_CARGO'],
							'fecha_pago' =>  date('Y-m-d H:i:s'),
							'createdAt' =>  date('Y-m-d H:i:s'),
							'updatedAt' =>  date('Y-m-d H:i:s'),
						);
						$resultPago = $this->model_venta->m_registrar_pago($dataPago); 

						$arrData['datos']['session'] = $_SESSION['sess_cevs_'.substr(base_url(),-8,7) ];
						$arrData['datos']['session']['compra']['listaCitas'] = array();
						$arrData['datos']['session']['compra']['listaCitasGeneradas'] = $listaCitasGeneradas;
						$this->session->set_userdata('sess_cevs_'.substr(base_url(),-8,7),$arrData['datos']['session']);					
						$arrData['message'] = $arrData['datos']['cargo']['outcome']['user_message'];
						$arrData['message'] .= '. Tu comprobante está en proceso de ser emitido. Recibirás un mail cuando esté listo!';

						//envio de mails
				        $listaCitasGeneradas = $allInputs['usuario']['compra']['listaCitas'];
				        $listaDestinatarios = array();
				        array_push($listaDestinatarios, $allInputs['usuario']['email']);			        
				        $setFromAleas = 'Vitacloud';
				        $subject = 'Citas Programadas';
				        $cuerpo = $this->genera_body_mail_citas($allInputs, $listaCitasGeneradas);
				        $result = enviar_mail($subject, $setFromAleas,$cuerpo,$listaDestinatarios);
				        $arrData['flagMail']  = $result[0]['flag'];
						$arrData['msgMail']  = $result[0]['msgMail'];

						foreach ($listNotificaciones as $key => $noti) {
							$this->model_control_evento_web->m_registrar_notificacion_evento($noti);
						}		
				  	}else{
				  		$error = TRUE;
				  	}
				}catch (Exception $e) {
				  	$arrData['datos']['error'] = get_object_vars(json_decode($e->getMessage()));
				  	$arrData['message'] = $arrData['datos']['error']['merchant_message'];
				  	$arrData['flag'] = 0;
				  	$error = TRUE;
				}				
			}
		}else{
			$error = TRUE;
		}
		
		if ($this->db->trans_status() === TRUE && !$error){
		    $this->db->trans_commit();
		}else{
			$this->db->trans_rollback();        
		}		

	    $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode($arrData));
	      return;
  	}
  	
  	private function genera_codigo_orden($idsede,$idsedeempresaadmin){
		// "W" + IDSEDE + 0 + DIA + MES + AÑO + CORRELATIVO 
		$codigoOrden = 'W';
		$codigoOrden .= $idsede . '0';
		$codigoOrden .= date('dmy');
		
		//OBTENER ULTIMA ORDEN DE VENTA WEB 
		$fUltimaVenta = $this->model_venta->m_cargar_ultima_venta_web($idsedeempresaadmin);
		// var_dump($fUltimaVenta); exit();
		if( empty($fUltimaVenta) ){
			$numberToOrden = 1;
		}else{ 
			$numberToOrden = substr($fUltimaVenta['orden_venta'], -6, 6); 
			//var_dump($numberToOrden); var_dump(substr($fUltimaVenta['orden_venta'], -12, 6)); var_dump(date('dmy')); 
			if( substr($fUltimaVenta['orden_venta'], -12, 6) == date('dmy') ){ 
				$numberToOrden = (int)$numberToOrden + 1;
			}else{
				$numberToOrden = 1;
			}
		}
		//var_dump($numberToOrden); exit(); 
		$codigoOrden .= str_pad($numberToOrden, 6, '0', STR_PAD_LEFT);
		return $codigoOrden;
	}

  	private function genera_body_mail_citas($allInputs, $listaCitasGeneradas){
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
                        Estimado(a) usuario: '.$allInputs['usuario']['paciente'] .', <br /> <br /> ';
        $cuerpo .= '    Han sido regitradas las siguientes citas en tu cuenta:';
        $cuerpo .=    '</div>';
  		$cuerpo .= '<div class="citas scroll-pane" style="width: 100%;">';
		foreach ($listaCitasGeneradas as $key => $cita) {			
			$cuerpo .= 	'<div class="cita" style="font-size: 13px;">
	                      <div style="height: 30px;" >
	                      	<div style="width:31px;float: left;">👪 </div>
	                      	Cita para: <span class="cita-familiar">'.$cita['busqueda']['itemFamiliar']['descripcion'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">🏨 </div> 
	                      	Sede: <span class="cita-sede">'.$cita['busqueda']['itemSede']['descripcion'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;font-size: 20px;">⚕️ </div> 
	                      	Especialidad: <span class="cita-esp">'.$cita['busqueda']['itemEspecialidad']['descripcion'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">👨‍ </div> 
	                      	Médico: <span class="cita-medico">'.$cita['seleccion']['medico'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">🗓 </div> 
	                      	Fecha: <span class="cita-turno">'.$cita['seleccion']['fecha_programada'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">🕑 </div>
	                      	Hora: <span class="cita-turno">'.$cita['seleccion']['hora_formato'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;"> 📍 </div>
	                      	Consultorio: <span class="cita-ambiente">'.$cita['seleccion']['numero_ambiente'].'</span>
	                      </div>
	                      <div style="height: 30px;">
	                      	<div style="width:31px;float: left;">💰 </div>
	                      	Precio S/.: <span class="cita-precio">'.$cita['producto']['precio_sede'].'</span>
	                      </div>
			            </div>';
			if($key < count($listaCitasGeneradas)-1){
				$cuerpo .= '<div style="border-bottom: 1px dotted rgb(154, 153, 157); margin-bottom: 15px;width: 50%;"> </div>';
			}   
		} 

		$cuerpo .= '</div>';

		$cuerpo .= '<div style="border-top: 2px dotted rgb(97, 97, 97); margin-bottom: 5px;"> </div>';

		$cuerpo .= '<div style="padding: 3px 8px;
					  height: 18px;
					  font-size: 11px;
					  font-weight: 800;
					  color: #616161;">
					  <span style="text-align:left;width:60%;">TOTAL PRODUCTOS: S/. </span>
					  <span style="text-align:right;width:40%;">'.$allInputs['usuario']['compra']['totales']['total_productos'].'</span>
					</div>';
		$cuerpo .= '<div style="padding: 3px 8px;
					  height: 18px;
					  font-size: 11px;
					  font-weight: 800;
					  color: #616161;">
					  <span style="text-align:left;width:60%;">USO SERVICIO WEB: S/.</span>
					  <span style="text-align:right;width:40%;">'.$allInputs['usuario']['compra']['totales']['total_servicio'].'</span>
					</div>';
		$cuerpo .= '<div style="padding: 3px 8px;
					  height: 18px;
					  font-size: 11px;
					  font-weight: 800;
					  color: #212121;">
					  <span style="text-align:left;width:60%;">TOTAL A PAGAR: S/. </span>
					  <span style="text-align:right;width:40%;">'.$allInputs['usuario']['compra']['totales']['total_pago'].'</span>
					</div>';
		$cuerpo .= '<div style="margin-top:15px;color:#ce1d19;font-weight:bold;text-align:center;">Tu comprobante está en proceso de ser emitido. Recibirás un mail cuando esté listo! </div>';
		$cuerpo .= '</div>';		
	    $cuerpo .= '<div style="text-align: center;">
	    				<img style="max-width: 800px;" alt="Hospital Vitacloud" src="'.base_url(). 'assets/img/dinamic/empresa/footer-mail.jpg">
	    			</div>';
      	$cuerpo .= '</body>';
        $cuerpo .= '</html>';

      	return $cuerpo;
  	}

}