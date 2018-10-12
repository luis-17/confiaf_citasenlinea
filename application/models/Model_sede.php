<?php
class Model_sede extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_sedes_cbo($datos = FALSE){ 
		$this->db->select('se.idsede, se.descripcion, se.estado_se, se.hora_inicio_atencion, se.hora_final_atencion');
		$this->db->select('sea.idempresaadmin, sea.idsedeempresaadmin');
		$this->db->from('sede se');
		$this->db->join('sede_empresa_admin sea','se.idsede = sea.idsede AND sea.tiene_venta_ce = 1');
		$this->db->where('se.estado_se', 1); // activo
		if( $datos ){
			if($datos['nameColumn'] == 'tiene_prog_cita')
				$this->db->where($datos['nameColumn'] .' = ' . $datos['search']);
			else
				$this->db->like('LOWER('.$datos['nameColumn'].')', strtolower($datos['search']));
		}else{
			$this->db->limit(100);
		}
		$this->db->order_by('idsede','ASC');
		return $this->db->get()->result_array();
	}

	public function m_cargar_sede_por_id($id){
		$this->db->select('idsede, descripcion, direccion_se, hora_inicio_atencion, hora_final_atencion, intervalo_sede');
		$this->db->from('sede');
		$this->db->where('estado_se',1);
		$this->db->where('idsede',$id);
		return $this->db->get()->row_array();
	}	

}