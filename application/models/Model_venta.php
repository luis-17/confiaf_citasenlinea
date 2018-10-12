<?php
class Model_venta extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function m_cargar_ultima_venta_web($idsedeempresaadmin){
		$this->db->select('idventa, orden_venta');
		$this->db->from('ce_venta cv');
		$this->db->where('idsedeempresaadmin', $idsedeempresaadmin); 
		$this->db->order_by('idventa','DESC');
		$this->db->limit(1); 
		return $this->db->get()->row_array();
	}

	public function m_registrar_venta($data){
		return $this->db->insert('ce_venta', $data);
	}

	public function m_registrar_pago($data){
		return $this->db->insert('ce_usuario_web_pago', $data);
	}	

	public function m_registrar_detalle_venta($data){
		return $this->db->insert('ce_detalle', $data);
	}

	public function m_consulta_medio_pago($tipotarjeta){
		$this->db->select('idmediopago');
		$this->db->from('medio_pago mp');
		$this->db->where('mp.key_marca', strtolower($tipotarjeta)); 
		$this->db->limit(1); 
		$row = $this->db->get()->row_array();

		return  $row['idmediopago'];
	}
}