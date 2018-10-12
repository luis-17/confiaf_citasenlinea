<?php
class Model_control_evento_web extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function m_registrar_notificacion_evento($data){ 
		return $this->db->insert('ce_usuario_web_control_evento', $data);
	}

	public function m_cargar_notificaciones_usuario($idusuario){
		$this->db->select('uce.idusuariowebcontrolevento, uce.fecha_evento, uce.fecha_leido, uce.idtipoevento, 
			uce.identificador, uce.idusuarioweb, uce.texto_notificacion, uce.estado_uce, uce.idresponsable'); 
		$this->db->select('te.descripcion_te, te.key_evento');
		
		$this->db->from('ce_usuario_web_control_evento uce');
		$this->db->join('tipo_evento te', 'te.idtipoevento = uce.idtipoevento AND estado_te = 1');

		$this->db->where('uce.idusuarioweb', intval($idusuario));
		$this->db->where('uce.estado_uce <> 0');
		$this->db->order_by('uce.fecha_evento DESC');
		return $this->db->get()->result_array();
	}

	public function m_count_notificaciones_sin_leer_usuario($idusuario){
		$this->db->select('COUNT(*) AS result');
		
		$this->db->from('ce_usuario_web_control_evento uce');
		$this->db->join('tipo_evento te', 'te.idtipoevento = uce.idtipoevento AND estado_te = 1');

		$this->db->where('uce.idusuarioweb', intval($idusuario));
		$this->db->where('uce.estado_uce = 1');

		$fData = $this->db->get()->row_array();
		return $fData['result'];
	}

	public function m_update_leido_notificacion($idusuariowebcontrolevento){
		$data = array( 
			'estado_uce'=> 2,
			'fecha_leido'=> date('Y-m-d H:i:s') 
		);
		$this->db->where('idusuariowebcontrolevento',$idusuariowebcontrolevento); 
		return $this->db->update('ce_usuario_web_control_evento', $data ); 
	}
}