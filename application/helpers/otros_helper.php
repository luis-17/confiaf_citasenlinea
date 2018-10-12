<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// if(empty($_SESSION['sess_vs_talario'])){
//    var_dump($_COOKIE);
//    // var_dump($_SESSION);
//    exit();
//   }

function GetLastId($campoId,$table){
    $ci2 =& get_instance();
    $ci2->db->select('MAX('.$campoId.') AS id',FALSE);
    $ci2->db->from($table);
    $fData = $ci2->db->get()->row_array();
    return $fData['id'];
}

function getConfig($tipo = FALSE, $id = FALSE, $incluye_privados = FALSE){
  $ci2 =& get_instance();
  $ci2->db->select('cc.key, cc.value');
  $ci2->db->from('ce_configuracion cc');

  if($tipo){
    $ci2->db->where('cc.tipo ', $tipo);

    if($id){
      $ci2->db->where('cc.idsedeempresaadmin IS NULL OR cc.idsedeempresaadmin = '.$id);
    }
  }
  
  if(!$incluye_privados){
    $ci2->db->where('cc.si_key_publico', 1);
  }

  $fData = $ci2->db->get()->result_array();

  $data = array();
  foreach ($fData as $key => $value) {
    $data[$value['key']] = $value['value'];
  }
  return $data;
}

// para verificar si un string esta compuesto de solo numeros sin comas ni puntos
function soloNumeros($laCadena) {
    $carsValidos = "0123456789";
    for ($i=0; $i<strlen($laCadena); $i++) {
      if (strpos($carsValidos, substr($laCadena,$i,1))===false) {
         return false; 
      }
    }
    return true; 
}

function edad($fecha){
    $fecha = str_replace("/","-",$fecha);
    $fecha = date('Y/m/d',strtotime($fecha));
    $hoy = date('Y/m/d');
    $edad = $hoy - $fecha;
    return $edad;
}

function strtoupper_total($string){ 
  return strtr(strtoupper($string),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
}

function strtolower_total($string){ 
  return strtr(strtolower($string),"ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ","àèìòùáéíóúçñäëïöü");
}

function comprobar_email($email){ 
    $mail_correcto = FALSE; 
    //compruebo unas cosas primeras 
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 
        if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 
          //miro si tiene caracter . 
          if (substr_count($email,".")>= 1){ 
              //obtengo la terminacion del dominio 
              $term_dom = substr(strrchr ($email, '.'),1); 
              //compruebo que la terminación del dominio sea correcta 
              if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
                //compruebo que lo de antes del dominio sea correcto 
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
                if ($caracter_ult != "@" && $caracter_ult != "."){ 
                    $mail_correcto = 1; 
                } 
              } 
          } 
        } 
    } 
    return $mail_correcto; 
}

function enviar_mail($asunto, $setFromAleas, $cuerpo, $listaDestinatarios, $listaAdjuntos = FALSE){
  $ci2 =& get_instance();
  $ci2->load->library('My_PHPMailer');

  //carga de configuracion mail
  $fDataMail = getConfig('mail');
  //print_r($fDataMail);

  date_default_timezone_set('UTC');
  define('SMTP_HOST',$fDataMail['SMTP_HOST']);
  define('SMTP_PORT',$fDataMail['SMTP_PORT']);
  define('SMTP_USERNAME',$fDataMail['SMTP_USERNAME']);
  define('SMTP_PASSWORD',$fDataMail['SMTP_PASSWORD']);
  
  $mail = new PHPMailer();
  $mail->IsSMTP(true);
  $mail->SMTPAuth = true;
  //$mail->SMTPDebug = true;
  $mail->SMTPSecure = "tls";
  $mail->Host = SMTP_HOST;
  $mail->Port = SMTP_PORT;
  $mail->Username =  SMTP_USERNAME;
  $mail->Password = SMTP_PASSWORD;
  $mail->SetFrom(SMTP_USERNAME,$setFromAleas);
  $mail->AddReplyTo(SMTP_USERNAME,$setFromAleas);
  $mail->Subject = $asunto;
  $mail->IsHTML(true);
  $mail->AltBody = $cuerpo;
  $mail->MsgHTML($cuerpo);
  $mail->CharSet = 'UTF-8';
  /*$mail->AddBCC("ymartinez@villasalud.pe");
  $mail->AddBCC("yeralmmf@gmail.com");
  $mail->AddBCC("yerald02@hotmail.com");*/

/*  if($listaAdjuntos){
    foreach ($listaAdjuntos as $key => $fileUrl) {
      $fichero = file_get_contents($fileUrl);
      $mail->addStringAttachment($fichero, 'comprobanteCita.pdf');
    }
  }*/
  
  //print_r($mail);
  $response = array();
  foreach ($listaDestinatarios as $key => $email) {
    if($email != null && $email != ''){
      if(comprobar_email($email)){
        $mail->AddAddress($email);
        //print_r($email);
        if($mail->Send()){
          array_push($response, 
                      array(
                        'flag' => 1,
                        'msgMail' => 'Notificación de correo enviada exitosamente.')
                      );
        }else{
          $mail->ErrorInfo;
          array_push($response, 
                      array(
                        'flag' => 0,
                        'msgMail' => 'Notificación de correo NO enviada.')
                      );
        }
      }else{
        array_push($response, 
                      array(
                        'flag' => 2,
                        'msgMail' => 'Notificación de correo NO enviada. Correo invalido.')
                      );
      }
    }else{
      array_push($response, 
                      array(
                        'flag' => 3,
                        'msgMail' => 'Notificación de correo NO enviada. Correo no registrado.')
                      );
    }
  }  

  return $response;
}

function getIndexArrayByValue($arr,$arrFields,$arrValores){
	$arrKeys = array();
  foreach($arr as $key => $value){ 
  	$siCumple = TRUE;
		foreach ($arrValores as $keyV => $value2) { 
			if ( $value[$arrFields[$keyV]] == $value2 ){
				$arrKeys[] = $key;
			}else{
				$siCumple = FALSE;
			}
		}	
		if( $siCumple ){
			return $key;
		}
  }
  return false;
}

function generar_notificacion_evento($idtipoevento, $key_evento, $data){
  //print_r($data);
  $texto = '';
  if($idtipoevento == 17 && $key_evento='key_citas_en_linea'){
    $texto = 'Has generado una cita para: '. $data['busqueda']['itemFamiliar']['descripcion'] . '. En la Sede: ' .$data['busqueda']['itemSede']['descripcion']; 
    $texto .= '. Especialidad: ' . $data['busqueda']['itemEspecialidad']['descripcion']. '. Médico: '. $data['seleccion']['medico'];
    $texto .= '. Fecha: ' . $data['seleccion']['fecha_programada'] . ' Hora: ' . $data['seleccion']['hora_formato'] . '. Consultorio: ' . $data['seleccion']['numero_ambiente'];
  }  

  if($idtipoevento == 18 && $key_evento='key_citas_en_linea'){
    $texto = 'Has reprogramado una de tus citas. Nueva cita para: '. $data['oldCita']['itemFamiliar']['paciente'] . '. En la Sede: ' .$data['oldCita']['itemSede']['sede']; 
    $texto .= '. Especialidad: ' . $data['oldCita']['itemEspecialidad']['especialidad']. '. Médico: '. $data['seleccion']['medico'];
    $texto .= '. Fecha: ' . $data['seleccion']['fecha_programada'] . ' Hora: ' . $data['seleccion']['hora_formato'] . '. Consultorio: ' . $data['seleccion']['numero_ambiente'];
  }  

  if($idtipoevento == 19 && $key_evento='key_citas_en_linea'){
    $texto = 'Has registrado un nuevo familiar. '. $data['apellido_paterno'] . ' '. $data['apellido_materno'] . ' ' . $data['nombres'] . '. Parentesco: ' .$data['parentesco']['descripcion']; 
  } 

  return $texto;   
}

