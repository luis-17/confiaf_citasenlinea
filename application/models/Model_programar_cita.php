<?php
class Model_programar_cita extends CI_Model {
	public function __construct()	{
		parent::__construct();
	}

	public function m_cargar_programaciones($datos){
		$this->db->select('prm.idprogmedico, prm.fecha_programada, prm.hora_inicio, prm.hora_fin');
		$this->db->select('am.idambiente, am.numero_ambiente, esp.idespecialidad, esp.nombre AS especialidad'); 
		$this->db->select('prm.idmedico'); 
		$this->db->from('pa_prog_medico prm');
		$this->db->join('pa_ambiente am','prm.idambiente = am.idambiente');		
		$this->db->join('especialidad esp','prm.idespecialidad = esp.idespecialidad');		
		$this->db->where('DATE(prm.fecha_programada) BETWEEN '. $this->db->escape(date('Y-m-d',strtotime($datos['desde']))). ' AND '. $this->db->escape($datos['hasta'])); 

		if(!empty($datos['itemEspecialidad']) && !empty($datos['itemEspecialidad']['id'])){
			$this->db->where('esp.idespecialidad',$datos['itemEspecialidad']['id']);
		}

		$this->db->where('am.estado_amb', 1);		 
		$this->db->where('prm.estado_prm', 1);		 
		$this->db->where('prm.activo', 1);		 
		$this->db->where('prm.idsede', $datos['itemSede']['id']); 
		$this->db->where('prm.tipo_atencion_medica', 'CM'); 
		$this->db->where("(	SELECT count(*) 
						   	FROM pa_detalle_prog_medico dpm
							WHERE dpm.idprogmedico = prm.idprogmedico 
							AND dpm.estado_cupo = 2
							AND to_timestamp(prm.fecha_programada || ' ' || dpm.hora_inicio_det, 'YYYY-MM-DD HH24:MI:SS') > to_timestamp('". date('Y-m-d H:i:s') ."','YYYY-MM-DD HH24:MI:SS'
							)) > 0");

		//$this->db->group_by('prm.fecha_programada, am.numero_ambiente, am.idambiente, esp.idespecialidad'); 
		$this->db->order_by('prm.fecha_programada ASC, prm.hora_inicio ASC'); 
		return $this->db->get()->result_array(); 
	}

	public function m_cargar_cupos_disponibles($idsprogmedicos){
		$this->db->select('dpm.iddetalleprogmedico, dpm.idcanal, dpm.hora_inicio_det, dpm.hora_fin_det');
		$this->db->select('dpm.si_adicional, dpm.numero_cupo');
		$this->db->select('med.med_nombres, med.med_apellido_paterno, med.med_apellido_materno, med.colegiatura_profesional');		
		$this->db->select('prm.idprogmedico, prm.idmedico, prm.fecha_programada');	
		$this->db->select('am.idambiente, am.numero_ambiente'); 	

		$this->db->from('pa_detalle_prog_medico dpm');
		$this->db->join('pa_prog_medico prm','dpm.idprogmedico = prm.idprogmedico');
		$this->db->join('pa_ambiente am','prm.idambiente = am.idambiente');
		$this->db->join('medico med', 'med.idmedico = prm.idmedico');		 
		$this->db->where('dpm.estado_cupo', 2);		 
		$this->db->where("to_timestamp(prm.fecha_programada || ' ' || dpm.hora_inicio_det, 'YYYY-MM-DD HH24:MI:SS') > to_timestamp('". date('Y-m-d H:i:s') ."','YYYY-MM-DD HH24:MI:SS')");		 
		//$this->db->where('dpm.idcanal', 3); //canal web		 
		$this->db->where_in('dpm.idprogmedico', $idsprogmedicos);		 
		
		$this->db->order_by('prm.idprogmedico ASC, dpm.si_adicional DESC, dpm.hora_inicio_det ASC');
		return $this->db->get()->result_array(); 
	}

	public function m_registrar_usuarioweb_cita($data){
		return $this->db->insert('ce_usuario_web_cita', $data);
	}

	public function m_cargar_medicos_autocomplete($datos){
		$this->db->distinct();
		$this->db->select("m.idmedico");
		$this->db->select("(med_nombres || ' ' || med_apellido_paterno || ' ' || med_apellido_materno ) AS medico", FALSE);		
		$this->db->from('medico m'); //medico
		$this->db->join('empresa_medico emme','m.idmedico = emme.idmedico'); //empresa_medico
		$this->db->join('empresa_especialidad emes','emme.idempresaespecialidad = emes.idempresaespecialidad');		
		$this->db->join('especialidad esp','emes.idespecialidad = esp.idespecialidad', "emme.idsede = " .(int)$datos['itemSede']['idsede']);			
		$this->db->where('esp.idespecialidad',(int)$datos['itemEspecialidad']['id']);
	
		$this->db->ilike("med_nombres || ' ' || med_apellido_paterno || ' ' || med_apellido_materno", strtoupper($datos['search']));		
				
		$this->db->limit(10);
		return $this->db->get()->result_array();
	}

	public function m_lista_feriados_cbo($paramDatos){ 
		$anioSig = intval($paramDatos['anyo'] + 1);
		$this->db->select('idferiado, fecha, estado_fe, descripcion');
		$this->db->from('rh_feriado');
		$this->db->where('estado_fe', 1); // activo
		$this->db->where("date_part('year', fecha) = ".$paramDatos['anyo'] . " OR  date_part('year', fecha) = " . $anioSig);
		$this->db->order_by('fecha', 'ASC');
		
		return $this->db->get()->result_array();
	}
}