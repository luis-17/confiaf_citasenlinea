<?php
class Model_prog_cita extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function m_registrar($data){
		return $this->db->insert('pa_prog_cita', $data);
	}

	public function m_consulta_cita($idprogcita){
		$this->db->select('*'); 
		$this->db->from("pa_prog_cita ppc");		
		$this->db->where("ppc.idprogcita", intval($idprogcita)); //cita
		return $this->db->get()->row_array();
	}

	public function m_cambiar_datos_en_cita($datos){
		$data = array( 
			'iddetalleprogmedico'=> $datos['iddetalleprogmedico'], 
			'fecha_atencion_cita'=> $datos['fecha_atencion_cita'], 
		);
		$this->db->where("idprogcita", $datos['idprogcita']);
		return $this->db->update('pa_prog_cita', $data ); 
	}

	public function m_consulta_cita_venta($idprogcita){
		$this->db->select('cv.idventa, cv.orden_venta, cv.fecha_venta'); 
		$this->db->select('cd.iddetalle, cd.paciente_atendido_det, cd.fecha_atencion_det'); 
		$this->db->select('ppc.idprogcita, ppc.estado_cita'); 
		$this->db->from("pa_prog_cita ppc");
		$this->db->join('ce_detalle cd','cd.idprogcita  = ppc.idprogcita');		
		$this->db->join('ce_venta cv','cd.idventa  = cv.idventa');		
		$this->db->where("ppc.idprogcita", intval($idprogcita)); //cita
		$this->db->where("ppc.idprogcita <> ", 0); //estado cita (no cancelada)
		return $this->db->get()->row_array();
	}

	public function m_carga_esta_cita($idprogcita){ 
		$this->db->select('ppc.idprogcita, ppc.estado_cita, ppc.idcliente');
		$this->db->select('dpm.iddetalleprogmedico, dpm.idcanal, dpm.hora_inicio_det, dpm.hora_fin_det, dpm.si_adicional, dpm.numero_cupo');
		$this->db->select('prm.idprogmedico, prm.idmedico, prm.fecha_programada');
		$this->db->select('med.med_nombres, med.med_apellido_paterno, med.med_apellido_materno, med.colegiatura_profesional');	

		$this->db->select('am.idambiente, am.numero_ambiente, esp.idespecialidad, esp.nombre AS especialidad'); 
		$this->db->select('cli.nombres, cli.apellido_paterno, cli.apellido_materno');	//datos del familiar

		$this->db->select('se.idsede, se.descripcion as sede');	
		$this->db->select('uwp.idusuariowebpariente, uwp.idparentesco, cp.descripcion as parentesco');
		
		$this->db->from('pa_prog_cita ppc');
		$this->db->join('pa_detalle_prog_medico dpm','ppc.iddetalleprogmedico = dpm.iddetalleprogmedico AND dpm.idcanal = 3');	
		$this->db->join('pa_prog_medico prm','dpm.idprogmedico = prm.idprogmedico');
		$this->db->join('medico med', 'med.idmedico = prm.idmedico');			

		$this->db->join('pa_ambiente am','prm.idambiente = am.idambiente');	
		$this->db->join('especialidad esp','prm.idespecialidad = esp.idespecialidad');
		$this->db->join('cliente cli','cli.idcliente = ppc.idcliente');		
		
		$this->db->join('ce_usuario_web_pariente uwp','cli.idcliente = uwp.idclientepariente','left');
		$this->db->join('ce_parentesco cp','uwp.idparentesco = cp.idparentesco','left');

		$this->db->join('sede_empresa_admin sea','sea.idsedeempresaadmin = ppc.idsedeempresaadmin');
		$this->db->join('sede se','sea.idsede = se.idsede');							

		$this->db->where('ppc.idprogcita', $idprogcita);
		$this->db->where('ppc.estado_cita <>', '0');

		return $this->db->get()->row_array();
	}

	public function m_cita_tiene_atencion($datos){
		$this->db->select('COUNT(*) AS result'); 
		$this->db->from("pa_prog_cita ppc, ce_detalle d, atencion_medica am");		
		$this->db->where("ppc.idprogcita", intval($datos['idprogcita'])); //cita
		$this->db->where("d.idprogcita = ppc.idprogcita");	
		$this->db->where("am.iddetalle = d.iddetalle"); //detalle			
		$this->db->where("d.paciente_atendido_det = 1"); 
		$this->db->where("am.estado_am = 1"); //atencion
		$this->db->where("am.origen_venta = 'W'");	

		$fData = $this->db->get()->row_array();
		return empty($fData['result']) ? FALSE : TRUE; 	
	}

}