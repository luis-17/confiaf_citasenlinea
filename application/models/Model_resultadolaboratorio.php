<?php
class Model_resultadolaboratorio extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_resultados_usuario($datos){ 
		$this->db->select('m.idmuestrapaciente, m.orden_lab, m.orden_venta, m.idhistoria, m.fecha_recepcion, m.idsedeempresaadmin');
		$this->db->select('tm.descripcion as tipomuestra, m.idsedeempresaadmin');
		//$this->db->select('s.descripcion as sede , ea.idempresaadmin');
		$this->db->from('muestra_paciente m');
		$this->db->join('tipomuestra tm','tm.idtipomuestra = m.idtipomuestra');
		/*$this->db->join('sede_empresa_admin sea','sea.idsedeempresaadmin = m.idsedeempresaadmin');
		$this->db->join('empresa_admin ea','sea.idempresaadmin = ea.idempresaadmin');
		$this->db->join('sede s','s.idsede = sea.idsede');	*/			
		$this->db->where('m.estado_mp <>', 0);
		//$this->db->where_in('ea.estado_emp', array(1,2));
		$this->db->where('m.idcliente', $datos['idcliente']);		

		return $this->db->get()->result_array();
	}
	public function m_count_resultados_usuario($datos,$paramPaginate)
	{
		$this->db->select('COUNT(*) AS contador',FALSE);
		$this->db->from('muestra_paciente m');
		$this->db->join('tipomuestra tm','tm.idtipomuestra = m.idtipomuestra');
		$this->db->join('sede_empresa_admin sea','sea.idsedeempresaadmin = m.idsedeempresaadmin');
		$this->db->join('empresa_admin ea','sea.idempresaadmin = ea.idempresaadmin');
		$this->db->join('sede s','s.idsede = sea.idsede');				
		$this->db->where('m.estado_mp <>', 0);
		$this->db->where('ea.estado_emp', 1);
		$this->db->where('m.idcliente', $datos['idcliente']);

		if( $paramPaginate['search'] ){
			foreach ($paramPaginate['searchColumn'] as $key => $value) {
				if( !empty($value) ){
					$this->db->ilike('CAST('.$key.' AS TEXT )', $value);
				}
			}
		}
		$fData = $this->db->get()->row_array();
		return $fData['contador'];
	}	

	public function m_verificar_si_existe_orden_laboratorio($orden_lab){
		$this->db->from('muestra_paciente');
		$this->db->where('orden_lab', $orden_lab);
		$this->db->where('idsedeempresaadmin', $this->sessionHospital['idsedeempresaadmin']);
	//	$this->db->where('estado_mp <>', 0);
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	public function m_verificar_estructura($idanalisis,$idsea){
		$this->db->from('analisis_parametro apar');
		$this->db->where('idanalisis', $idanalisis);
		$this->db->where('idsedeempresaadmin', $idsea);
		$this->db->where('estado_apar', 1);
		// $this->db->order_by('idanalisispaciente', 'ASC');
		return $this->db->get()->result_array();
	}

	public function m_cargar_analisis_por_orden($orden_lab,$idsea){
		$this->db->select('ap.idanalisispaciente, ap.idanalisis, ap.idmuestrapaciente, anal.descripcion_anal');

		$this->db->from('muestra_paciente mp');
		$this->db->join('analisis_paciente ap','mp.idmuestrapaciente = ap.idmuestrapaciente');
		$this->db->join('analisis anal','ap.idanalisis = anal.idanalisis');
		$this->db->where('orden_lab', $orden_lab);
		$this->db->where('mp.idsedeempresaadmin', $idsea);
		// $this->db->order_by('idanalisispaciente', 'ASC');
		return $this->db->get()->result_array();
	}	

	// FUNCION PRINCIPAL ---- NO BORRAR¡¡¡¡¡¡¡¡¡¡¡
	public function m_cargar_parametros_analisis_por_orden($datos,$idsea){
		$this->db->select('h.idhistoria, ap.idcliente, , cl.nombres, cl.apellido_paterno, cl.apellido_materno, cl.sexo, fecha_nacimiento');
		$this->db->select('mp.orden_lab, mp.orden_venta, v.ticket_venta, v.idventa, mp.fecha_recepcion');
		$this->db->select('s.idseccion, s.descripcion_sec as seccion, apar.idanalisis, anal.descripcion_anal, cantidad,
			(CASE WHEN (par.separador = 0 ) THEN apar.idanalisisparametro ELSE apar2.idanalisisparametro END), 
			par.idparametro AS idparametro,	par.descripcion_par AS parametro, par2.idparametro AS idsubparametro, par2.descripcion_par AS subparametro, par.combo, par2.combo AS subcombo, par.nombre_combo, par2.nombre_combo AS nombre_subcombo,
			(CASE WHEN (par.separador = 0 ) THEN apar.orden_parametro ELSE apar2.orden_subparametro END),
			(CASE WHEN (par.separador = 0 ) THEN par.autocalculable ELSE par2.autocalculable END),
			(CASE WHEN (par.separador = 0 ) THEN par.formula ELSE par2.formula END),
			(CASE WHEN (par.separador = 0 ) THEN par.requiere_texto_adicional ELSE par2.requiere_texto_adicional END),
			(CASE WHEN (par.separador = 0 ) THEN par.texto_adicional ELSE par2.texto_adicional END),
			(CASE WHEN (par.separador = 0 ) THEN dr.resultado ELSE dr2.resultado END),
			(CASE WHEN (par.separador = 0 ) THEN dr.iddetalleresultado ELSE dr2.iddetalleresultado END),
			(CASE WHEN (par.separador = 0 ) THEN pvs.valor_normal_h ELSE pvs2.valor_normal_h END),
			(CASE WHEN (par.separador = 0 ) THEN pvs.valor_normal_m ELSE pvs2.valor_normal_m END),
			(CASE WHEN (par.separador = 0 ) THEN pvs.valor_ambos ELSE pvs2.valor_ambos END),
		 	met.descripcion AS metodo, par.separador, apar.idparent, apar2.idparent, 
		 	ap.idanalisispaciente, ap.idmuestrapaciente, ap.iddetalle,pm.descripcion as producto, d.paciente_atendido_det, ap.fecha_resultado, ap.numero_impresiones, apar.orden_parametro, apar2.orden_subparametro ,estado_ap',FALSE);
	    $this->db->from('analisis_parametro apar');
	    $this->db->join('analisis anal','apar.idanalisis = anal.idanalisis');
	    $this->db->join('seccion s','anal.idseccion = s.idseccion');
	    
	    $this->db->join('parametro par','apar.idparametro = par.idparametro');
	    $this->db->join('parametro_valor_sede pvs', 'par.idparametro = pvs.idparametro AND pvs.idsedeempresaadmin = '.$idsea , 'left' );
	    $this->db->join('metodo met','anal.idmetodo = met.idmetodo','left');
	    $this->db->join('analisis_paciente ap','anal.idanalisis = ap.idanalisis');
	    $this->db->join('detalle_resultado dr','ap.idanalisispaciente = dr.idanalisispaciente AND apar.idanalisisparametro = dr.idanalisisparametro','left');
	    $this->db->join('detalle d','ap.iddetalle = d.iddetalle');
	    $this->db->join('producto_master pm','ap.idproductomaster = pm.idproductomaster');
		$this->db->join('muestra_paciente mp','ap.idmuestrapaciente = mp.idmuestrapaciente');
		$this->db->join('venta v', 'mp.orden_venta = v.orden_venta');
		$this->db->join('cliente cl','ap.idcliente = cl.idcliente');
		$this->db->join('historia h','cl.idcliente = h.idcliente');
		$this->db->join('analisis_parametro apar2','apar.idanalisisparametro = apar2.idparent AND apar2.estado_apar = 1
			AND apar2.idsedeempresaadmin = '.$idsea, 'left');
		$this->db->join('detalle_resultado dr2','ap.idanalisispaciente = dr2.idanalisispaciente AND apar2.idanalisisparametro = dr2.idanalisisparametro','left');
	    $this->db->join('parametro par2','apar2.idparametro = par2.idparametro', 'left');
	    $this->db->join('parametro_valor_sede pvs2', 'par2.idparametro = pvs2.idparametro AND pvs2.idsedeempresaadmin = '.$idsea, 'left' );
		//$this->db->where('mp.orden_lab', $datos['orden_lab']);
		$this->db->where('apar.idparent', 0);
		$this->db->where('apar.estado_apar', 1);
		$this->db->where('v.estado', 1);
		$this->db->where('anal.estado_anal <> 0');
		$this->db->where('mp.idsedeempresaadmin', $idsea);
		$this->db->where('apar.idsedeempresaadmin', $idsea);
		if( $datos['searchTipo'] == 'PP' ) { 
			$this->db->where($datos['searchColumn'].' = ', $datos['searchText']); 
		}else{
			$this->db->where($datos['searchColumn'], $datos['searchText']);
		}
		$this->db->order_by('cl.idcliente','ASC');
		$this->db->order_by('anal.idseccion','ASC');
		$this->db->order_by('apar.idanalisis','ASC');
		$this->db->order_by('apar.orden_parametro','ASC');
		$this->db->order_by('apar2.orden_subparametro','ASC');
		$this->db->order_by('apar.idanalisisparametro','ASC');
		$this->db->order_by('apar2.idanalisisparametro','ASC');
		
		return $this->db->get()->result_array();
	}

	public function m_cargar_parasito_heces_cbo()
	{
		$this->db->select('idparasito, descripcion as elemento_combo');
		$this->db->from('parasito_heces');
		$this->db->order_by('idparasito', 'ASC');
		$this->db->where('estado_p', 1);
		return $this->db->get()->result_array();
	}

	public function m_cargar_lista_combo($datos)
	{
		$this->db->select('nombre_combo, elemento_combo');
		$this->db->from('combo_laboratorio');
		$this->db->where('nombre_combo', $datos);
		$this->db->where('estado_combo', 1);
		$this->db->order_by('elemento_combo');
		return $this->db->get()->result_array(); 	
	}		
	/*------------- EMPRESA ADMIN -----------*/
	public function m_cargar_esta_empresa_por_codigo($datos)
	{
		$this->db->select('idempresaadmin, razon_social, nombre_legal, domicilio_fiscal, 
			direccion, ruc, nombre_logo, estado_emp, rs_facebook, rs_twitter, rs_youtube');
		$this->db->from('empresa_admin');
		$this->db->where('idempresaadmin', $datos);
		$this->db->where('estado_emp <>', 0);
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}		
	
}