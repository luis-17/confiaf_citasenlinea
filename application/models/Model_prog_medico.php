<?php
class Model_prog_medico extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function m_cambiar_estado_detalle_de_programacion($datos) {
		$data = array( 
			'estado_cupo'=> $datos['estado_cupo'],
			'updatedAt' =>  date('Y-m-d H:i:s'),
		);
		$this->db->where('iddetalleprogmedico',$datos['iddetalleprogmedico']); 
		return $this->db->update('pa_detalle_prog_medico', $data ); 
	}
	
	public function m_cambiar_cupos_canales($datos) {
		return $this->db->simple_query("update pa_canal_prog_medico 
							SET cupos_ocupados = cupos_ocupados + 1, 
								cupos_disponibles = cupos_disponibles -1
							WHERE idprogmedico = ".$datos['idprogmedico'] . " AND idcanal = " . $datos['idcanal']);
	}

	public function m_cambiar_cupos_programacion($datos) {
		return $this->db->simple_query("update pa_prog_medico 
							SET total_cupos_ocupados = total_cupos_ocupados + 1 													
							WHERE idprogmedico = ".intval($datos['idprogmedico']));
	}

	public function m_consulta_cupo($iddetalleprogmedico){
		$this->db->select('*'); 
		$this->db->from("pa_detalle_prog_medico dpm");		
		$this->db->where("dpm.iddetalleprogmedico", intval($iddetalleprogmedico)); //cupo
		return $this->db->get()->row_array();
	}

	public function m_revertir_cupos_canales($datos) {
		return $this->db->simple_query("update pa_canal_prog_medico 
							SET cupos_ocupados = cupos_ocupados -1, 
								cupos_disponibles = cupos_disponibles +1
							WHERE idprogmedico = ".$datos['idprogmedico'] . " AND idcanal = " . $datos['idcanal']);
	}

	public function m_revertir_cupos_programacion($datos) {
		return $this->db->simple_query("update pa_prog_medico 
							SET total_cupos_ocupados = total_cupos_ocupados -1 													
							WHERE idprogmedico = ".intval($datos['idprogmedico']));
	}

	public function m_ajustar_canal($datos) {
		return $this->db->simple_query("update pa_canal_prog_medico 
							SET total_cupos = total_cupos - 1,
							cupos_disponibles = cupos_disponibles - 1
							WHERE idprogmedico = ".$datos['idprogmedico'] . " AND idcanal = " . $datos['idcanal']);
	}

	public function m_agregar_uno_canal_web($datos) {		
		return $this->db->simple_query("update pa_canal_prog_medico 
							SET total_cupos = total_cupos + 1,
							cupos_disponibles = cupos_disponibles +1
							WHERE idprogmedico = ".$datos['idprogmedico'] . " AND idcanal = 3");
	}

	public function m_cambiar_canal_cupo($datos) {
		$data = array( 
			'idcanal'=> $datos['idcanal'] 
		);
		$this->db->where('iddetalleprogmedico',$datos['iddetalleprogmedico']); 
		return $this->db->update('pa_detalle_prog_medico', $data ); 
	}

}