<?php
class Model_especialidad extends CI_Model {
	public function __construct()	{
		parent::__construct();
	}

	public function m_cargar_especialidades_prog_asistencial($datos){
		$this->db->select('esp.idespecialidad, (esp.nombre) AS especialidad');
		$this->db->from('especialidad esp');
		$this->db->join('pa_sede_especialidad seesp','esp.idespecialidad = seesp.idespecialidad AND seesp.idsede = '.$datos['idsede'], 'left');
		$this->db->where('esp.estado', 1); // especialidad
		$this->db->where('seesp.tiene_venta_prog_cita', 1); // especialidad
		$this->db->order_by('esp.nombre ASC');		
		return $this->db->get()->result_array();
	}

	public function m_cargar_precio_cita($datos){
		$this->db->select('pm.idproductomaster, pm.descripcion');
		$this->db->select('pps.idproductopreciosede, (pps.precio_sede)::NUMERIC AS precio_sede, pps.idsedeempresaadmin');
		$this->db->from('producto_master pm');
		$this->db->where('pm.estado_pm', 1); 
		$this->db->where('pm.idtipoproducto', 12); 
		$this->db->where('pm.idespecialidad', (int)$datos['idespecialidad']); 
		//$this->db->where("pm.descripcion LIKE 'CONSULTA %" . $datos['especialidad'] ."%'");
		$this->db->join('producto_precio_sede pps','pm.idproductomaster = pps.idproductomaster 
													AND pps.estado_pps = 1
													AND pps.es_precio_web = 1');
		$this->db->join('sede_empresa_admin sea','sea.idsedeempresaadmin = pps.idsedeempresaadmin 
													AND sea.idsede = '.$datos['idsede'] . '
													AND sea.idempresaadmin = '.$datos['idempresaadmin'] . '
													AND sea.estado_sea = 1');
		//$this->db->group_by('pm.descripcion, pps.idproductopreciosede, pps.precio_sede, pps.idsedeempresaadmin');
		
		return $this->db->get()->result_array();
	}

}