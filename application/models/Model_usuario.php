<?php
class Model_usuario extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function m_cargar_por_documento($datos){
		$this->db->select('c.idcliente, c.num_documento, c.nombres, c.apellido_paterno, c.apellido_materno, 
							c.sexo, c.telefono, c.celular, c.fecha_nacimiento, c.email', FALSE); 
		$this->db->select('uw.idusuarioweb, uw.nombre_imagen, uw.estado_uw', FALSE); 
		$this->db->from('cliente c');
		$this->db->join('ce_usuario_web uw','uw.idcliente = c.idcliente AND uw.estado_uw <> 0', 'left');
		$this->db->where('c.estado_cli', 1); 
		$this->db->where('c.num_documento',$datos['num_documento']);
		return $this->db->get()->row_array();
	}	

	public function m_cargar_usuario($datos){
		$this->db->select('c.idcliente, c.num_documento, c.nombres, c.apellido_paterno, c.apellido_materno, 
							c.sexo, c.telefono, c.celular, c.fecha_nacimiento, c.email', FALSE); 
		$this->db->select("DATE_PART('YEAR',AGE(c.fecha_nacimiento)) AS edad",FALSE);
		$this->db->select('uw.idusuarioweb, uw.nombre_usuario, uw.nombre_imagen, uw.estado_uw', FALSE); 
		$this->db->select('uw.peso, uw.estatura, uw.tipo_sangre', FALSE); 
		$this->db->from('ce_usuario_web uw');
		$this->db->join('cliente c','uw.idcliente = c.idcliente AND c.estado_cli = 1', 'left');
		$this->db->where('uw.estado_uw', 1); 
		$this->db->where('uw.nombre_usuario',$datos['nombre_usuario']);
		return $this->db->get()->row_array();
	}

	public function m_verificar_email($datos){
		$this->db->select('uw.idusuarioweb, uw.estado_uw', FALSE);
		$this->db->from('ce_usuario_web uw');
		$this->db->where('uw.estado_uw <>', 0); 
		$this->db->where('UPPER(uw.nombre_usuario)',strtoupper($datos['email']));
		return $this->db->get()->row_array();
	}

	public function m_registrar_cliente($data){
		return $this->db->insert('cliente', $data);
	}	

	public function m_registrar_historia($datos){
		$data = array(
			'idcliente' => $datos['idcliente'],
			'fecha_creacion' => date('Y-m-d'),
			'createdAt' => date('Y-m-d H:i:s'),
			'updatedAt' => date('Y-m-d H:i:s')
		);
		return $this->db->insert('historia', $data);
	}

	public function m_update_cliente($data, $id){
		$this->db->where('idcliente',$id);
		return $this->db->update('cliente', $data);
	}

	public function m_registrar_usuario($data){
		return $this->db->insert('ce_usuario_web', $data);
	}	

	public function m_update_estado_usuario($data, $id){
		$this->db->where('idusuarioweb',$id);
		$this->db->where('estado_uw ',2);
		$this->db->where('idcliente IS NOT NULL');
		return $this->db->update('ce_usuario_web', $data);
	}	

	public function m_cargar_este_usuario($datos){ 
		$this->db->select('uw.idusuarioweb, uw.estado_uw', FALSE);
		$this->db->from('ce_usuario_web uw');
		$this->db->where('uw.estado_uw', 1); 
		$this->db->where('idusuarioweb',$datos['idusuario']);
		return $this->db->get()->row_array();
	}

	public function m_verifica_password($datos){ 
		$this->db->select('*');
		$this->db->from('ce_usuario_web uw');
		$this->db->where('idusuarioweb',$datos['idusuario']);
		$this->db->where('password', do_hash($datos['clave'] , 'md5'));
		$this->db->where('estado_uw <>', '0');
		$this->db->limit(1);
		
		return $this->db->get()->row_array();
	}

	public function m_actualizar_password($datos){
		$data = array(
			'password' => do_hash($datos['claveNueva'],'md5'),
			'updatedAt' => date('Y-m-d H:i:s')
		);
		$this->db->where('idusuarioweb',$datos['idusuario']);
		return $this->db->update('ce_usuario_web', $data);
	}

	public function m_subir_foto_perfil($datos){
		$data = array(
			'nombre_imagen' => $datos['nuevoNombreArchivo'],
			'updatedAt' => date('Y-m-d H:i:s')
		);
		$this->db->where('idusuarioweb',$datos['idusuario']);
		return $this->db->update('ce_usuario_web', $data);
	}

	public function m_actualizar_perfil_clinico($datos){
		$data = array(
			'peso' => $datos['peso'],
			'estatura' => $datos['estatura'],
			'tipo_sangre' => $datos['tipo_sangre']['id'],
			'updatedAt' => date('Y-m-d H:i:s')
		);
		$this->db->where('idusuarioweb',$datos['idusuario']);
		return $this->db->update('ce_usuario_web', $data);
	}

	public function m_cargar_para_mail($idusuario){
		$this->db->select('c.idcliente, c.num_documento, c.nombres, c.apellido_paterno, c.apellido_materno, 
							c.sexo, c.telefono, c.celular, c.fecha_nacimiento, c.email', FALSE); 
		$this->db->select('uw.idusuarioweb, uw.nombre_imagen, uw.estado_uw', FALSE); 
		$this->db->from('ce_usuario_web uw');
		$this->db->join('cliente c','uw.idcliente = c.idcliente AND c.estado_cli = 1', 'left');
		$this->db->where('uw.estado_uw', 1); 
		$this->db->where('uw.idusuarioweb',$idusuario);
		return $this->db->get()->row_array();
	}
}