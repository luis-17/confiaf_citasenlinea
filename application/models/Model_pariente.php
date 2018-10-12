<?php
class Model_pariente extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function m_cargar_parientes($datos, $paramPaginate=FALSE){ 
		$this->db->select('uwp.idusuariowebpariente, uwp.idusuarioweb, uwp.idclientepariente, uwp.estado_uwp');
		$this->db->select('c.num_documento, c.nombres, c.apellido_paterno, c.apellido_materno, c.sexo, c.fecha_nacimiento, c.email');
		$this->db->select('uwp.idparentesco, cp.descripcion AS parentesco');
		$this->db->select('uwp.idclientepariente');
		$this->db->from('ce_usuario_web_pariente uwp');
		$this->db->join('cliente c','uwp.idclientepariente = c.idcliente AND estado_cli = 1','left');
		$this->db->join('ce_parentesco cp','uwp.idparentesco = cp.idparentesco');
		$this->db->where('uwp.idusuarioweb', $datos['idusuario']); 
		$this->db->where('estado_uwp', 1); // activo
		if($paramPaginate){
			if( $paramPaginate['search'] ){
				foreach ($paramPaginate['searchColumn'] as $key => $value) {
					if( !empty($value) ){
						$this->db->ilike('CAST('.$key.' AS TEXT )', $value);
					}
				}
			}
			if( $paramPaginate['sortName'] ){
				$this->db->order_by($paramPaginate['sortName'], $paramPaginate['sort']);
			}
			if( $paramPaginate['firstRow'] || $paramPaginate['pageSize'] ){
				$this->db->limit($paramPaginate['pageSize'],$paramPaginate['firstRow'] );
			}
		}

		$this->db->order_by('uwp.idusuariowebpariente','ASC');		
		return $this->db->get()->result_array();
	}

	public function m_count_parientes($datos,$paramPaginate=FALSE){
		$this->db->select('COUNT(*) AS contador',FALSE);
		$this->db->from('ce_usuario_web_pariente uwp');
		$this->db->join('cliente c','uwp.idclientepariente = c.idcliente AND estado_cli = 1','left');
		$this->db->join('ce_parentesco cp','uwp.idparentesco = cp.idparentesco');
		$this->db->where('uwp.idusuarioweb', $datos['idusuario']); 
		$this->db->where('estado_uwp', 1); // activo
		if($paramPaginate){
			if( $paramPaginate['search'] ){
				foreach ($paramPaginate['searchColumn'] as $key => $value) {
					if( !empty($value) ){
						$this->db->ilike('CAST('.$key.' AS TEXT )', $value);
					}
				}
			}
		}

		$fData = $this->db->get()->row_array();
		return $fData['contador'];
	}

	public function m_cargar_por_documento($datos){
		$this->db->select('c.idcliente, c.num_documento, c.nombres, c.apellido_paterno, c.apellido_materno, 
							c.sexo, c.telefono, c.celular, c.fecha_nacimiento, c.email', FALSE); 
		$this->db->select('uw.idusuarioweb, uw.nombre_imagen, uw.estado_uw', FALSE); 
		$this->db->select('uwp.idusuariowebpariente, uwp.estado_uwp', FALSE); 
		$this->db->from('cliente c');
		$this->db->join('ce_usuario_web uw','uw.idcliente = c.idcliente AND uw.estado_uw <> 0', 'left');
		$this->db->join('ce_usuario_web_pariente uwp','uwp.idclientepariente = c.idcliente AND uwp.estado_uwp <> 0 AND uwp.idusuarioweb = '.$datos['idusuario'], 'left');
		$this->db->where('c.estado_cli', 1); 
		$this->db->where('c.num_documento',$datos['num_documento']);
		return $this->db->get()->row_array();
	}

	public function m_registrar_pariente($data){
		return $this->db->insert('ce_usuario_web_pariente', $data);
	}

	public function m_anular_pariente($id){
		$data = array(
			'estado_uwp' => 0
			);
		$this->db->where('idusuariowebpariente', $id);
		return $this->db->update('ce_usuario_web_pariente', $data);
	}

	public function m_cargar_parientes_cbo($datos){
		$this->db->select('uwp.idusuariowebpariente, uwp.idusuarioweb, uwp.idclientepariente, uwp.estado_uwp');
		$this->db->select('c.num_documento, c.nombres, c.apellido_paterno, c.apellido_materno, c.sexo, c.fecha_nacimiento, c.email');
		$this->db->select('uwp.idparentesco, cp.descripcion AS parentesco');
		$this->db->select('uwp.idclientepariente');
		$this->db->select("DATE_PART('YEAR',AGE(c.fecha_nacimiento)) AS edad",FALSE);
		$this->db->from('ce_usuario_web_pariente uwp');
		$this->db->join('cliente c','uwp.idclientepariente = c.idcliente AND estado_cli = 1','left');
		$this->db->join('ce_parentesco cp','uwp.idparentesco = cp.idparentesco');
		$this->db->where('uwp.idusuarioweb', $datos['idusuario']); 
		$this->db->where('estado_uwp', 1); // activo

		$this->db->order_by('uwp.idusuariowebpariente','ASC');		
		return $this->db->get()->result_array();
	}
}
?>