<?php
class Model_historial_citas extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_carga_historial_citas($datos){ 
		$this->db->select('uwc.idusuariowebcita, uwc.idusuarioweb',FALSE);
		$this->db->select('ppc.idprogcita, ppc.estado_cita, ppc.idcliente');
		$this->db->select('dpm.iddetalleprogmedico, dpm.idcanal, dpm.hora_inicio_det, dpm.hora_fin_det, dpm.si_adicional, dpm.numero_cupo');
		$this->db->select('prm.idprogmedico, prm.idmedico, prm.fecha_programada');
		$this->db->select('med.med_nombres, med.med_apellido_paterno, med.med_apellido_materno, med.colegiatura_profesional');	

		$this->db->select('am.idambiente, am.numero_ambiente, esp.idespecialidad, esp.nombre AS especialidad'); 
		$this->db->select('cli.nombres, cli.apellido_paterno, cli.apellido_materno');	//datos del familiar

		$this->db->select('se.idsede, se.descripcion as sede');	
		$this->db->select('uwp.idusuariowebpariente, uwp.idparentesco, cp.descripcion as parentesco');

		$this->db->select('cd.idventa, cd.iddetalle');
		$this->db->select('wp.numero_comprobante, wp.fecha_comprobante, wp.estado_comprobante, wp.nombre_archivo');
		
		$this->db->from('ce_usuario_web_cita uwc');
		$this->db->join('pa_prog_cita ppc','ppc.idprogcita = uwc.idprogcita ');
		$this->db->join('pa_detalle_prog_medico dpm','ppc.iddetalleprogmedico = dpm.iddetalleprogmedico AND dpm.idcanal = 3');	
		$this->db->join('pa_prog_medico prm','dpm.idprogmedico = prm.idprogmedico');
		$this->db->join('medico med', 'med.idmedico = prm.idmedico');	

		$this->db->join('ce_detalle cd','cd.idprogcita = uwc.idprogcita');		
		$this->db->join('ce_usuario_web_pago wp','cd.idventa = wp.idventa');		

		$this->db->join('pa_ambiente am','prm.idambiente = am.idambiente');	
		if(!empty($datos['especialidad']['id'])){
			$this->db->join('especialidad esp','prm.idespecialidad = esp.idespecialidad AND esp.idespecialidad = '. $datos['especialidad']['id']);
		}else{
			$this->db->join('especialidad esp','prm.idespecialidad = esp.idespecialidad');
		}		

		if(!empty($datos['familiar']['idclientepariente'])){
			$this->db->join('cliente cli','cli.idcliente = ppc.idcliente AND cli.idcliente = '. $datos['familiar']['idclientepariente']);
		}else{
			$this->db->join('cliente cli','cli.idcliente = ppc.idcliente');
		}
		
		$this->db->join('ce_usuario_web_pariente uwp','cli.idcliente = uwp.idclientepariente','left');
		$this->db->join('ce_parentesco cp','uwp.idparentesco = cp.idparentesco','left');

		$this->db->join('sede_empresa_admin sea','sea.idsedeempresaadmin = ppc.idsedeempresaadmin');
		if(!empty($datos['sede']['id'])){
			$this->db->join('sede se','sea.idsede = se.idsede AND se.idsede = '. $datos['sede']['id']);
		}else{
			$this->db->join('sede se','sea.idsede = se.idsede');
		}					

		$this->db->where('uwc.idusuarioweb', $datos['idusuario']);

		$this->db->where('ppc.estado_cita <>', '0');
		if($datos['tipoCita']['id'] == 'P'){
			$this->db->where('ppc.estado_cita', 2);
		}
		if($datos['tipoCita']['id'] == 'R'){
			$this->db->where('ppc.estado_cita', 5);
		}

		$this->db->order_by('prm.fecha_programada DESC');

		return $this->db->get()->result_array();
	}

	public function m_count_cant_citas($datos){
		$this->db->select('COUNT(*) AS contador',FALSE);
		$this->db->from('ce_usuario_web_cita uwc');
		$this->db->join('pa_prog_cita ppc','ppc.idprogcita = uwc.idprogcita ');
		$this->db->where('uwc.idusuarioweb', $datos['idusuario']);
		$this->db->where('ppc.estado_cita', $datos['estado_cita']);

		$fData = $this->db->get()->row_array();
		return $fData['contador'];
	}


}
?>