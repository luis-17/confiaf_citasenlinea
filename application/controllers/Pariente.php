<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pariente extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security','otros_helper'));
		$this->load->model(array('model_pariente','model_usuario','model_control_evento_web'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");		
		$this->sessionCitasEnLinea = @$this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
	}

  public function lista_parientes(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    $paramPaginate = $allInputs['paginate'];
    $datos = $this->sessionCitasEnLinea;
    $lista = $this->model_pariente->m_cargar_parientes($datos, $paramPaginate);
    $totalRows = $this->model_pariente->m_count_parientes($datos,$paramPaginate);
    $arrListado = array();
    foreach ($lista as $row) {
      $pariente = strtoupper($row['nombres']) . ' ' . 
                  strtoupper($row['apellido_paterno']) . ' ' . 
                  strtoupper($row['apellido_materno']);  

      if($row['sexo']=='F'){
        $color = '#f4b2b0';
        $icon = 'fa fa-female';
        $desc = 'FEMENINO';
      }else{
        $color = '#2d527c';
        $icon = 'fa fa-male';
        $desc = 'MASCULINO';
      }

      array_push($arrListado, 
        array(
          'idusuariowebpariente' => $row['idusuariowebpariente'],
          'idusuarioweb' => $row['idusuarioweb'],
          'idclientepariente' => $row['idclientepariente'],
          'estado_uwp' => $row['estado_uwp'],
          'nombres' => strtoupper($row['nombres']),
          'apellido_paterno' => strtoupper($row['apellido_paterno']),
          'apellido_materno' => strtoupper($row['apellido_materno']),
          'sexo' => $row['sexo'],
          'color_sexo' => $color,
          'idparentesco' => $row['idparentesco'],
          'parentesco' => $row['parentesco'],
          'pariente' => $pariente,
          'num_documento' => $row['num_documento'],
          'fecha_nacimiento' => date('d-m-Y',strtotime($row['fecha_nacimiento'])),
          'email' => $row['email'],
          'idclientepariente' => $row['idclientepariente'],
          'icon' => $icon,
          'desc_sexo' => $desc,
        )
      );
    }
      $arrData['datos'] = $arrListado;
      $arrData['paginate']['totalRows'] = $totalRows;
      $arrData['message'] = '';
      $arrData['flag'] = 1;
    if(empty($lista)){
      $arrData['flag'] = 0;
    }
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
  }

  public function ver_popup_formulario(){
    $this->load->view('pariente/pariente_formView');
  }  

  public function ver_popup_aviso(){
    $this->load->view('mensajes/confirmacion-previa');
  }

  public function verificar_pariente_por_documento(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    $allInputs['idusuario'] = $this->sessionCitasEnLinea['idusuario'];
    $arrData['message'] = 'Aún no es nuestro paciente. Por favor, ingresa sus datos para finalizar el registro.';
    $arrData['flag'] = 0;

    $usuario = $this->model_pariente->m_cargar_por_documento($allInputs);

    if(!empty($usuario)){
      $usuario['fecha_nacimiento'] = Date('d-m-Y',strtotime($usuario['fecha_nacimiento']));
      $arrData['usuario'] = $usuario;
      $arrData['message'] = 'Ya es nuestro paciente. Por favor, completa sus datos para finalizar el registro.';
      $arrData['flag'] = 2;

      if(!empty($usuario['idusuarioweb'])){
        $arrData['message'] = 'Estimado paciente, tu familiar ya es usuario en nuestro sistema en linea.';
        $arrData['flag'] = 1;
        $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($arrData));
        return;
      }       

      if(!empty($usuario['idusuariowebpariente'])){
        $arrData['message'] = 'Estimado paciente, tu familiar está registrado en nuestro sistema en linea.';
        $arrData['flag'] = 1;
        $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($arrData));
        return;
      }
    }   

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
  }

  public function registrar_pariente(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    $arrData['message'] = 'No se pudo finalizar el registro. Intente nuevamente.';
    $arrData['flag'] = 0;
    $allInputs['idusuario'] = $this->sessionCitasEnLinea['idusuario'];

    if(empty($allInputs['num_documento'])){
    $arrData['message'] = 'Debe ingresar un Número de documento.';
    $arrData['flag'] = 0;    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
      return;       
    }

    if($allInputs['parentesco']['id']==1 && edad($allInputs['fecha_nacimiento']) > 17){
    $arrData['message'] = 'Solo puede registrar hijos menos de 18 años.';
    $arrData['flag'] = 0;    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
      return;       
    } 

    $usuario = $this->model_pariente->m_cargar_por_documento($allInputs);
    if(!empty($usuario) && !empty($usuario['idusuarioweb'])){
    $arrData['message'] = 'Estimado paciente, tu familiar ya es usuario en nuestro sistema en linea.';
    $arrData['flag'] = 0;    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
      return;       
    } 

    if(!empty($usuario['idusuariowebpariente'])){
      $arrData['message'] = 'Estimado paciente, tu familiar ya está registrado en nuestro sistema en linea.';
      $arrData['flag'] = 0;
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
      return;
    }
    $this->db->trans_start();
    if(empty($usuario)){
      //registrar usuario
      $datos = array(
        'num_documento' => $allInputs['num_documento'], 
        'nombres' => strtoupper($allInputs['nombres']), 
        'apellido_paterno' => strtoupper($allInputs['apellido_paterno']), 
        'apellido_materno' => strtoupper($allInputs['apellido_materno']), 
        'email' => empty($allInputs['email']) ? null : $allInputs['email'], 
        'sexo' => $allInputs['sexo'], 
        'fecha_nacimiento' => $allInputs['fecha_nacimiento'], 
        'si_registro_web' => 1, 
        'idprocedencia' => 13, 
        'createdAt' => date('Y-m-d H:i:s'),
        'updatedAt' => date('Y-m-d H:i:s')
        );
      $resultCliente = $this->model_usuario->m_registrar_cliente($datos);
      $idcliente = GetLastId('idcliente','cliente');
      $data['idcliente'] = $idcliente;
      $this->model_usuario->m_registrar_historia($data);
    }else{
      //actualizar usuario
      $datos = array(
        'nombres' => strtoupper($allInputs['nombres']), 
        'apellido_paterno' => strtoupper($allInputs['apellido_paterno']), 
        'apellido_materno' => strtoupper($allInputs['apellido_materno']), 
        'email' => empty($allInputs['email']) ? null : $allInputs['email'], 
        'sexo' => $allInputs['sexo'], 
        'fecha_nacimiento' => $allInputs['fecha_nacimiento'],  
        'idprocedencia' => 13,
        'updatedAt' => date('Y-m-d H:i:s')
        );
      $idcliente =  $usuario['idcliente'];
      $resultCliente = $this->model_usuario->m_update_cliente($datos, $idcliente);        
    }

    if($resultCliente){
      $datos = array(
        'idusuarioweb' => $allInputs['idusuario'],
        'idclientepariente' => $idcliente,
        'idparentesco' => $allInputs['parentesco']['id'],
        'createdAt' => date('Y-m-d H:i:s'),
        'updatedAt' => date('Y-m-d H:i:s')
        );
      $resultPariente = $this->model_pariente->m_registrar_pariente($datos);
      $idpariente = GetLastId('idusuariowebpariente','ce_usuario_web_pariente');
    }
    $this->db->trans_complete();

    if($resultCliente && $resultPariente){
      $arrData['message'] = 'Se ha registrado tu familiar exitosamente.';
      $arrData['flag'] = 1;

      //generacion notificacion
      $texto_notificacion = generar_notificacion_evento(19, 'key_citas_en_linea', $allInputs);
      $noti = array(            
        'idtipoevento' => 19,
        'identificador' => $idpariente,
        'idusuarioweb' => $this->sessionCitasEnLinea['idusuario'],
        'texto_notificacion' => $texto_notificacion,
        'idresponsable' => $this->sessionCitasEnLinea['idusuario'],
        'fecha_evento' => date('Y-m-d H:i:s'),
        );
      $this->model_control_evento_web->m_registrar_notificacion_evento($noti);
    }

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($arrData));  
  }

  public function eliminar_pariente(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    $arrData['message'] = 'No pudo ser anulado el familiar. Intenta nuevamente.';
    $arrData['flag'] = 0;

    if($this->model_pariente->m_anular_pariente($allInputs['idusuariowebpariente'])){
      $arrData['message'] = 'El familiar fue eliminado correctamente.';
      $arrData['flag'] = 1;
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
  }

  public function editar_pariente(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    $arrData['message'] = 'No se pudo finalizar el registro. Intente nuevamente.';
    $arrData['flag'] = 0;

    if($allInputs['parentesco']['id']==1 && edad($allInputs['fecha_nacimiento']) > 17){
    $arrData['message'] = 'Solo puede registrar hijos menos de 18 años.';
    $arrData['flag'] = 0;    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
      return;       
    } 

    $datos = array(
      'nombres' => strtoupper($allInputs['nombres']), 
      'apellido_paterno' => strtoupper($allInputs['apellido_paterno']), 
      'apellido_materno' => strtoupper($allInputs['apellido_materno']), 
      'email' => $allInputs['email'], 
      'sexo' => $allInputs['sexo'], 
      'fecha_nacimiento' => $allInputs['fecha_nacimiento'],
      'updatedAt' => date('Y-m-d H:i:s')
      );

    if($this->model_usuario->m_update_cliente($datos, $allInputs['idclientepariente'])){
      $arrData['message'] = 'Han sido actualizados los datos de tu familiar.';
      $arrData['flag'] = 1;
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));  
  }

  public function lista_parientes_cbo(){
    $allInputs = json_decode(trim($this->input->raw_input_stream),true);
    $paramPaginate = $allInputs['paginate'];
    $datos = $this->sessionCitasEnLinea;
    $lista = $this->model_pariente->m_cargar_parientes_cbo($datos, $paramPaginate);     

    $arrListado = array();
    foreach ($lista as $row) {
      $paciente = strtoupper($row['nombres']) . ' ' . 
                  strtoupper($row['apellido_paterno']) . ' ' . 
                  strtoupper($row['apellido_materno']);

      array_push($arrListado, 
        array(
          'idusuariowebpariente' => $row['idusuariowebpariente'],
          'idusuarioweb' => $row['idusuarioweb'],
          'idclientepariente' => $row['idclientepariente'],
          'estado_uwp' => $row['estado_uwp'],
          'descripcion' => strtoupper($row['nombres']) . '[' . $row['parentesco'] .']', 
          'nombres' => strtoupper($row['nombres']),
          'apellido_paterno' => strtoupper($row['apellido_paterno']),
          'apellido_materno' => strtoupper($row['apellido_materno']),
          'sexo' => $row['sexo'],
          'edad' => $row['edad'],
          'idparentesco' => $row['idparentesco'],
          'parentesco' => $row['parentesco'],
          'num_documento' => $row['num_documento'],
          'fecha_nacimiento' => date('d-m-Y',strtotime($row['fecha_nacimiento'])),
          'email' => $row['email'],
          'paciente' => $paciente,
        )
      );
    }
      $arrData['datos'] = $arrListado;
      $arrData['message'] = '';
      $arrData['flag'] = 1;
    if(empty($lista)){
      $arrData['flag'] = 0;
    }
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
  }
}
