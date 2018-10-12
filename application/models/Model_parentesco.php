<?php
class Model_parentesco extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_carga_parentesco_cbo($datos = FALSE){ 
		$this->db->select('*',FALSE);
		$this->db->from('ce_parentesco cp');
		$this->db->where('cp.estado_p <>', '0');
		$this->db->order_by('cp.idparentesco');
		return $this->db->get()->result_array();
	}
}
?>