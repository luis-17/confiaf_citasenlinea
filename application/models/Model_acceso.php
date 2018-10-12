<?php
class Model_acceso extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
 	// ACCESO AL SISTEMA
	public function m_logging_user($data){ 
		$this->db->select('',FALSE);
		$this->db->select('COUNT(*) AS logged, uw.idusuarioweb, uw.estado_uw, uw.nombre_usuario',FALSE);
		$this->db->from('ce_usuario_web uw');
		$this->db->where('uw.nombre_usuario', $data['usuario']);
		$this->db->where('uw.password', do_hash($data['clave'] , 'md5'));
		$this->db->where('uw.estado_uw <>', '0');
		$this->db->group_by('uw.idusuarioweb');
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	public function m_registrar_log_sesion($datos){
		$data = array(
			'ip_address' => $this->input->ip_address(),
			'iduser' => $datos['idusers'],
			'fecha_login' => date('Y-m-d H:i:s')
		);
		return $this->db->insert('login_session', $data);
	}
}
?>