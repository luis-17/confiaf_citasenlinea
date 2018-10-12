<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resultadolaboratorio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->helper(array('security'));
		$this->load->model(array('model_resultadolaboratorio'));
		$this->load->helper(array('otros_helper','fechas_helper','security'));		
		//cache 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");
	}

	public function carga_resultados_usuario(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		//$paramPaginate = $allInputs['paginate'];

		$lista = $this->model_resultadolaboratorio->m_cargar_resultados_usuario($allInputs);
		//$totalRows = $this->model_resultadolaboratorio->m_count_resultados_usuario($allInputs,$paramPaginate);		

		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado,
				array(
					'id' => $row['idmuestrapaciente'],
					'orden_lab' => $row['orden_lab'] ,
					'orden_venta' => $row['orden_venta'],
					'idhistoria' => $row['idhistoria'],
					'fecha_recepcion' => $row['fecha_recepcion'],
					'tipomuestra' => $row['tipomuestra'],
					'sede' => $row['sede'],
					'idsedeempresaadmin' => $row['idsedeempresaadmin'],
					'idempresaadmin' => $row['idempresaadmin']
					
				)
			);
		}
    	$arrData['datos'] = $arrListado;
    	//$arrData['paginate']['totalRows'] = $totalRows;    	
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function listarPacientesConResultados(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrFilter = array();
		$arrFilter['searchTipo'] = FALSE;
		$arrFilter['searchColumn'] = 'mp.orden_lab';
		$arrFilter['searchText'] = $allInputs['orden_lab']; 
		// VERIFICAR ESTRUCTURA
		$listaAnalisis = $this->model_resultadolaboratorio->m_cargar_analisis_por_orden($allInputs['orden_lab'], $allInputs['idsedeempresaadmin'] );
		
		foreach ($listaAnalisis as $key => $row) {
			$arrEstructura = $this->model_resultadolaboratorio->m_verificar_estructura($row['idanalisis'] , $allInputs['idsedeempresaadmin'] );
			if( empty( $arrEstructura ) ){
				$arrData['message'] = 'El Analisis '. $row['descripcion_anal'] . ' no tiene estructura.';
		    	$arrData['flag'] = 0;
		    	$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			    return;
			}
		}

		$lista_combo = array();
		$lista_cbo = array();
		$lista = $this->model_resultadolaboratorio->m_cargar_parametros_analisis_por_orden($arrFilter , $allInputs['idsedeempresaadmin'] );
		
		if (!empty($lista)){
			$arrPrincipal = array( 
				'idcliente'=> null,
				'idhistoria'=> null,
				'idmuestrapaciente' => null,
				'orden_lab' => null,
				'orden_venta' => null,
				'idventa' => null,
				'ticket' => null,
				'paciente' => null,
				'edad' => null,
				'sexo' => null,
				'fecha_muestra'=> null,
				'secciones' => array()
			);
			/*CAB. PACIENTES - SECCIONES*/ 
			$pacienteObtenido = FALSE;
			foreach ($lista as $key => $row) { 
				if( $pacienteObtenido === FALSE ){
					$arrPrincipal['idcliente'] = $row['idcliente'];
					$arrPrincipal['idhistoria'] = $row['idhistoria'];
					$arrPrincipal['idmuestrapaciente'] = $row['idmuestrapaciente'];
					$arrPrincipal['orden_lab'] = $row['orden_lab'];
					$arrPrincipal['orden_venta'] = $row['orden_venta'];
					$arrPrincipal['idventa'] = $row['idventa'];
					$arrPrincipal['ticket'] = $row['ticket_venta'];
					$arrPrincipal['paciente'] = $row['apellido_paterno'] . ' ' . $row['apellido_materno'] . ', ' . $row['nombres'];
					//$arrPrincipal['edad'] = $row['edad'].' años';
					$arrPrincipal['edad'] = devolverEdadDetalle($row['fecha_nacimiento']);
					$arrPrincipal['fecha_muestra'] = $row['fecha_recepcion'];
					$arrPrincipal['sexo'] = strtoupper($row['sexo']);
					$pacienteObtenido = TRUE;
				}
				$arrAuxSeccion = array(
					'idseccion'=> $row['idseccion'],
					'seccion'=> $row['seccion'],
					'seleccionado' => FALSE,
					'analisis'=> array()
				); 
				$arrPrincipal['secciones'][$row['idseccion']] = $arrAuxSeccion;
			}
			 
			/* ANALISIS */
			foreach ($lista as $key => $row) {
				$arrAuxAnalisis = array(
					'idanalisis'=> $row['idanalisis'],
					'descripcion_anal' => $row['descripcion_anal'],
					'idanalisispaciente' => $row['idanalisispaciente'],
					'iddetalle' => $row['iddetalle'],
					'producto' => $row['producto'],
					'paciente_atendido_det' => $row['paciente_atendido_det'],
					'cantidad' => $row['cantidad'],
					'numero_impresiones' => $row['numero_impresiones'],
					'metodo' => $row['metodo'],
					'seleccionado' => FALSE,
					'estado_ap' => $row['estado_ap'],
					'fecha_resultado' => $row['fecha_resultado'],
					'parametros'=> array()
				); 
				$arrPrincipal['secciones'][$row['idseccion']]['analisis'][$row['idanalisispaciente']] = $arrAuxAnalisis;
			}

			/* PARAMETROS */
			foreach ($lista as $key => $row) {
				if($row['combo'] == 1){
					switch ($row['idparametro']) {
						case '528':
							$lista_combo = $this->model_resultadolaboratorio->m_cargar_parasito_heces_cbo();
							break;
						case '472':
							$lista_combo = $this->model_resultadolaboratorio->m_cargar_parasito_heces_cbo();
							break;
						default:
							$lista_combo = $this->model_resultadolaboratorio->m_cargar_lista_combo($row['nombre_combo']);
							break;
					};
					
					$lista_cbo[0] = array('id' => '--Seleccione Opcion--', 'descripcion' => '--Seleccione Opción--');
					foreach ($lista_combo as $key => $value) {
						array_push($lista_cbo, array(
							'id' => $value['elemento_combo'],
							'descripcion' => $value['elemento_combo']
							)
						);
					}
				}
				
				$arrAuxParametros = array(
					'idanalisisparametro'=> $row['idanalisisparametro'],
					'idparametro'=> $row['idparametro'],
					'parametro'=> $row['parametro'],
					'separador' => $row['separador'],
					'combo' => $row['combo'],
					'lista_combo' => $lista_cbo,
					'orden_parametro' => $row['orden_parametro'],
					'subparametros'=> array()
				); 
				if( empty($row['idsubparametro']) ){ 
					if($row['valor_ambos'] == 0 && $row['sexo'] == 'F'){
						$valornormal = $row['valor_normal_m'];
					}else{
						$valornormal = $row['valor_normal_h'];
					}
					$arrAuxParametros['valor_normal'] = $valornormal;
					$arrAuxParametros['valor_ambos'] = $row['valor_ambos'];
					$arrAuxParametros['iddetalleresultado'] = $row['iddetalleresultado'];
					$arrAuxParametros['autocalculable'] = $row['autocalculable'];
					$arrAuxParametros['formula'] = $row['formula'];
					$arrAuxParametros['requiere_texto_adicional'] = $row['requiere_texto_adicional'];
					$arrAuxParametros['texto_adicional'] = $row['texto_adicional'];
					$arrAuxParametros['resultado'] = ($row['combo'] == 1 && $row['resultado'] == '')? '--Seleccione Opcion--': $row['resultado'];
					unset($arrAuxParametros['subparametros']);
				}	
				$arrPrincipal['secciones'][$row['idseccion']]['analisis'][$row['idanalisispaciente']]['parametros'][$row['idparametro']] = $arrAuxParametros; 
				$lista_cbo = array();
			}

			/* SUBPARAMETROS */
			foreach ($lista as $key => $row) {
				if(!empty($row['subcombo']) && $row['subcombo'] == 1){
					switch ($row['idsubparametro']) {
						
						case '528':
							$lista_combo = $this->model_resultadolaboratorio->m_cargar_parasito_heces_cbo();
							break;
						case '472':
							$lista_combo = $this->model_resultadolaboratorio->m_cargar_parasito_heces_cbo();
							break;
						default:
							$lista_combo = $this->model_resultadolaboratorio->m_cargar_lista_combo($row['nombre_subcombo']);
							break;
					}
					$lista_cbo[0] = array('id' => '--Seleccione Opcion--', 'descripcion' => '--Seleccione Opción--');
					foreach ($lista_combo as $key => $value) {
						array_push($lista_cbo, array(
							'id' => $value['elemento_combo'],
							'descripcion' => $value['elemento_combo']
							)
						);
					}
				}
				$arrAuxParametros = array(
					'idanalisisparametro'=> $row['idanalisisparametro'],
					'idsubparametro'=> $row['idsubparametro'],
					'subparametro'=> $row['subparametro'],
					'subcombo' => $row['subcombo'],
					'lista_combo' => $lista_cbo,
					'orden_subparametro' => $row['orden_subparametro']
				); 
				if( !empty($row['idsubparametro']) ){ 
					if($row['valor_ambos'] == 0 && $row['sexo'] == 'F'){
						$valornormal = $row['valor_normal_m'];
					}else{
						$valornormal = $row['valor_normal_h'];
					}
					
					$arrAuxParametros['valor_normal'] = $valornormal;
					$arrAuxParametros['valor_ambos'] = $row['valor_ambos'];
					$arrAuxParametros['iddetalleresultado'] = $row['iddetalleresultado'];
					$arrAuxParametros['autocalculable'] = $row['autocalculable'];
					$arrAuxParametros['formula'] = $row['formula'];
					$arrAuxParametros['requiere_texto_adicional'] = $row['requiere_texto_adicional'];
					$arrAuxParametros['texto_adicional'] = $row['texto_adicional'];
					$arrAuxParametros['resultado'] = ($row['subcombo'] == 1 && $row['resultado'] == '')? '--Seleccione Opcion--': $row['resultado']; 

					$arrPrincipal['secciones'][$row['idseccion']]['analisis'][$row['idanalisispaciente']]['parametros'][$row['idparametro']]['subparametros'][$row['idsubparametro']] = $arrAuxParametros; 
				}
				$lista_cbo = array();	
			}
			// var_dump("<pre>",$arrPrincipal); exit(); 
			$arrPaciente = $arrPrincipal;
			unset($arrPaciente['secciones']);

			$arrAnalisis = array(); // para mostrar en la grilla
			foreach ($arrPrincipal['secciones'] as $key => $value) {
				foreach ($arrPrincipal['secciones'][$key]['analisis'] as $key3 => $row) {
					if( $row['estado_ap'] == 0 ){
						$estado = 'ANULADO';
						$clase = 'label-default';
					}
					if( $row['estado_ap'] == 1 ){
						$estado = 'SIN RESULTADOS';
						$clase = 'label-default';
					}
					if( $row['estado_ap'] == 2 ){
						$estado = 'CON RESULTADOS';
						$clase = 'label-info';
					}
					if( $row['estado_ap'] == 3 ){
						$estado = 'APROBADO';
						$clase = 'label-primary';
					}
					if( $row['estado_ap'] == 4 ){
						$estado = 'ENTREGADO';
						$clase = 'label-success';
					}
					array_push($arrAnalisis, 
						array (
							'idanalisis' => $row['idanalisis'],
							'descripcion_anal' => $row['descripcion_anal'],
							'producto' => $row['producto'],
							'idseccion' => $value['idseccion'],
							'seccion' => $value['seccion'],
							'idanalisispaciente' => $row['idanalisispaciente'],
							'fecha_resultado' => $row['fecha_resultado'],
							'numero_impresiones' => $row['numero_impresiones'],
							'estado' => $estado
						)
					);	
				}
			}
			// APLICAMOS ARRAY_VALUES PARA REORDENAR LOS INDICES
			$arrPrincipal['secciones'] = array_values($arrPrincipal['secciones']);
			foreach ($arrPrincipal['secciones'] as $key => $row) {
				$arrPrincipal['secciones'][$key]['analisis'] = array_values($arrPrincipal['secciones'][$key]['analisis']); 
				foreach ($arrPrincipal['secciones'][$key]['analisis'] as $key2 => $row2) {
					$arrPrincipal['secciones'][$key]['analisis'][$key2]['parametros'] = array_values($arrPrincipal['secciones'][$key]['analisis'][$key2]['parametros']); 
					foreach ($arrPrincipal['secciones'][$key]['analisis'][$key2]['parametros'] as $key3 => $row3) {
						@$arrPrincipal['secciones'][$key]['analisis'][$key2]['parametros'][$key3]['subparametros'] = array_values($arrPrincipal['secciones'][$key]['analisis'][$key2]['parametros'][$key3]['subparametros'] ); 
					}
				}
			}

			$arrData['datos'] = $arrPaciente;
			$arrData['arrSecciones'] = $arrPrincipal['secciones'];
			$arrData['arrAnalisis'] = $arrAnalisis; // para la grilla
	    	$arrData['message'] = 'Paciente encontrado.';
	    	$arrData['flag'] = 1;
		}else{
			$arrData['datos'] = null;
			$arrData['arrSecciones'] = null;
	    	$arrData['message'] = 'El Paciente no tiene Exámenes para ingresar resultados';
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));

	}

}