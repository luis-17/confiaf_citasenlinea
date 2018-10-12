<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function getPlantillaGeneralReporte($arrContent,$datos,$paramPageOrientation=FALSE,$paramPageSize=FALSE,$arrPageMargins=FALSE){
    $ci2 =& get_instance();
    $fConfig = $ci2->model_config->m_cargar_empresa_usuario_activa();
    // var_dump(base_url('assets/img/dinamic/empresa/'.$fConfig['nombre_logo'])); exit();
    $arrImages = array( 
      'imageHeaderPage'=> convertImageToBase64('assets/img/dinamic/empresa/'.$fConfig['nombre_logo']) // base_url('assets/img/dinamic/empresa/'.$fConfig['nombre_logo'])
    );
    $arrHeader = array( 
      	array( 
  	      	'text'=> 'USUARIO: '.strtoupper($ci2->sessionHospital['username']).'    /   FECHA DE IMPRESIÓN: '.date('Y-m-d H:i:s'),
  	      	'style'=> 'headerPage'
      	)
  	);
    $arrFooter = array();
    $arrStyles = array( 
      	'headerTitle'=> array( 
	        'fontSize'=> 17,
	        'bold'=> true
	        // 'alignment'=> 'center'
      	),
      	'filterTitle'=> array( 
	        'fontSize'=> 12,
	        'bold'=> true
	        // 'alignment'=> 'center'
      	),
      	'headerPage'=> array(
	        'fontSize'=> 6,
	        'alignment'=> 'right',
	        'italic' => true
      	),
      	'tableHeader'=> array(
    			'bold'=> true,
    			'fontSize'=> 10,
    			'color'=> 'black'
    		),
        'tableHeaderLG' => array(
          'bold'=> true,
          'fontSize'=> 12,
          'color'=> 'black',
          'alignment'=> 'center'
        )
    );
    // var_dump($fConfig); exit(); 
    $strLines = null;
    for ($i=0; $i < 148; $i++) { 
      $strLines.= '_';
    }
    // $arrContent = array(
      
    // );
    $arrMainContent = array( 
    	array(
	    	'image'=> 'imageHeaderPage',
	      	'alignment'=> 'left',
	      	'width' => 150,
	      	'margin' => array(-30,-5,0,0)
	    ),
	    array( 
	    	'text'=> $fConfig['razon_social'],
	    	'style'=> array('headerPage',array('alignment'=> 'left','fontSize'=> 8) ),
	    	'margin' => array(13,-15,0,0)
	    ),
	    array( 
	    	'text'=> $fConfig['domicilio_fiscal'],
	    	'style'=> array('headerPage',array('alignment'=> 'left','fontSize'=> 4) ),
	    	'margin' => array(13,0,0,0)
	    ),
	    array(
	        'text'=> $datos['titulo'],
	        'style'=> 'headerTitle',
	        'margin'=> array(270,-20,0,0)
	    ),
	    array( 
	        'text'=> $strLines,
	        'margin'=> array(-30,0,0,0)
	    )
    );
    $arrDataPDF = array( 
      //'background'=> null,
      'header'=> $arrHeader,
      'footer'=> $arrFooter,
      'content'=> array_merge($arrMainContent,$arrContent),
      'styles' => $arrStyles,
      'images' => $arrImages,
      'pageSize' => ($paramPageSize === FALSE ? 'A4' : $paramPageSize), 
      'pageOrientation' => ($paramPageOrientation === FALSE ? 'portrait' : $paramPageOrientation),  // $paramPageOrientation || 'portrait', // portrait/landscape
      'pageMargins' => ($arrPageMargins === FALSE ? array(50, 12, 20, 12) : $arrPageMargins)  // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
    );
    return $arrDataPDF;
    // return $fData['id'];
}

function getPlantillaGeneralReporteHTML($objPdf,$htmlContent,$datos,$paramPageOrientation=FALSE,$paramPageSize=FALSE,$arrPageMargins=FALSE){
  $ci2 =& get_instance();
  $fConfig = $ci2->model_config->m_cargar_empresa_usuario_activa(); 
  $style = ' <style>
      @page { margin-top: 0.2cm; margin-left: 0.7cm; margin-right: 0.5cm; margin-bottom: 0.6cm; }
      .header-mini { font-size: 6px; text-align: right; }
      .header-logo { margin-top: -8px;} 
      .block { display: block !important;}
      .headerTitle { font-size: 20px; font-weight:bold; margin-left: 360px; margin-top: -28px; color: #313a3e; }
      .razon_social { font-size: 10px; margin-left: 58px; margin-top: -20px; }
      .domicilio_fiscal { font-size: 5px; margin-left: 58px; margin-top: -1px; }
      .filterTitle { font-size: 12px; font-weight; bold; } 
      .text-center { text-align: center; }
      table {
        margin-top: 2pt;
        margin-bottom: 5pt;
        border-collapse: collapse;
      }
      thead td, thead th, tfoot td, tfoot th {
          font-variant: small-caps;
      }

      table.mainTable th { 
          vertical-align: top;
          padding-top: 3mm;
          padding-bottom: 3mm;
      }
      table.subTable th { 
        font-size: 11px;
      }
      table.detalleTable th { 
        font-size: 11px;
      }
      table.detalleTable td { 
        font-size: 10px;

      }
      table.detalleTable tfoot td { 
        font-size: 10px;
        font-weight: bold;
      }
    </style>
  ';
  $htmlPlantilla = '';
  $htmlPlantilla .= '<html> <head> '.$style.' </head> <body>';
  $htmlPlantilla .= '<div class="header-mini"> USUARIO:'.strtoupper($ci2->sessionHospital['username']).'    /   FECHA DE IMPRESIÓN: '.date('Y-m-d H:i:s') .'</div>';
  $htmlPlantilla .= '<div class="header-logo"> <img width="200" src="'.base_url('assets/img/dinamic/empresa/'.$fConfig['nombre_logo']).'" /> '; 
  $htmlPlantilla .= '<div class="block razon_social">'.$fConfig['razon_social'].'</div>';
  $htmlPlantilla .= '<div class="block domicilio_fiscal">'.$fConfig['domicilio_fiscal'].'</div>';
  $htmlPlantilla .= '</div>';
  $htmlPlantilla .= '<div class="headerTitle">'.$datos['titulo'].'</div> <hr />';
  $htmlPlantilla .= $htmlContent;
  $htmlPlantilla .= '</body></html>';
  return $htmlPlantilla;
}

function getPlantillaComprobanteCita($arrContent,$datos,$titulo,$paramPageOrientation=FALSE,$paramPageSize=FALSE,$arrPageMargins=FALSE){
    $ci2 =& get_instance();
    $fConfig = $ci2->model_sede->m_cargar_sede_por_id($datos['itemSede']['id']);
    $fDataCita = $ci2->model_prog_cita->m_consulta_cita_venta($datos['idprogcita']);
    
    // var_dump(base_url('assets/img/dinamic/empresa/'.$fConfig['nombre_logo'])); exit();
    $arrImages = array( 
      'imageHeaderPage'=> convertImageToBase64('assets/img/dinamic/empresa/logo-original.png')
    );
    $arrHeader = array( 
        array(
        'text'=> array(' ')
      ),
    );
    $arrFooter = array(
      array( 
          'text'=> '',
      )
    );
    $arrStyles = array( 
        'headerTitle'=> array( 
          'fontSize'=> 15,
          'bold'=> true
          // 'alignment'=> 'center'
        ),
        'filterTitle'=> array( 
          'fontSize'=> 10,
          'bold'=> true
          // 'alignment'=> 'center'
        ),
        'headerPage'=> array(
          'fontSize'=> 6,
          'alignment'=> 'right',
          'italic' => true
        ),
    );
    // var_dump($fConfig); exit(); 
    $strLines = null;
    for ($i=0; $i < 53; $i++) { 
      $strLines.= '_';
    }

    $arrMainContent = array( 
      array(
        'text'=> array(' ')
      ),
      array(
        'image'=> 'imageHeaderPage',
          'alignment'=> 'center',
          'width' => 160,
          'margin' => array(0,0,0,0)
      ),
      array( 
        'text'=> $fConfig['direccion_se'],
        'style'=> array('headerPage',array('alignment'=> 'center','fontSize'=> 10) ),
        'margin' => array(0,0,0,0)
      ),
      array(
        'text'=> array(' ')
      ),
      array(
          'text'=> $titulo,
          'style'=> array('headerTitle',array('alignment'=> 'center','fontSize'=> 12)),
      ),
      array( 
          'text'=> $strLines,
          'margin'=> array(0,0,0,0)
      )
    );

    /*codigo QR*/
    $ci2->load->library('qr_php');    
    $textoQR = 'Este comprobante pertenece a una cita del Hospital Vitacloud. Sede: ' . $fConfig['descripcion'] ;
    $textoQR .= '. Proveniente de la orden número: '.$fDataCita['orden_venta'];

    QRcode::png($textoQR,"assets/img/dinamic/temp/01.png",QR_ECLEVEL_L,2);
    /*fin codigo QR*/

    $arrFooterContent = array( 
      array( 
          'text'=> $strLines,
          'margin'=> array(0,0,0,0)
      ),
      array(
        'text'=> array(' ')
      ), 
      array(
        'image'=> convertImageToBase64('assets/img/dinamic/temp/01.png'),
        'alignment'=> 'center',
      ), 
      array(
        'text'=> array(' ')
      ),      
      array(
        'text'=> array('Recuerda llegar 30 minutos antes de tu cita.'),
        'style'=> array('headerPage',array('alignment'=> 'center','fontSize'=> 9) ),
      ),
      array(
        'text'=> array('Gracias por confiarnos tu salud...'),
        'style'=> array('headerPage',array('alignment'=> 'center','fontSize'=> 9) ),
      ),
      array(
        'text'=> array('Vitacloud, Te Cuida!'),
        'style'=> array('headerTitle',array('alignment'=> 'center','fontSize'=> 9)),
      ),      
      array(
        'text'=> array(' '),
      ),
      array(
        'text'=> array(' '),
      ),
      array(
        'text'=> 'USUARIO: '.strtoupper($ci2->sessionCitasEnLinea['nombre_usuario']).'    /   FECHA DE IMPRESIÓN: '.date('Y-m-d H:i:s'),
        'style'=> 'headerPage',
        //'margin' => array(0,0,300,0),
        'style'=> array('headerPage',array('alignment'=> 'right','fontSize'=> 4)),
      )

    );
    $arrDataPDF = array( 
      //'background'=> null,
      'header'=> $arrHeader,
      'footer'=> $arrFooter,
      'content'=> array_merge($arrMainContent,$arrContent,$arrFooterContent),
      'styles' => $arrStyles,
      'images' => $arrImages,
      'pageSize' => ($paramPageSize === FALSE ? 'A4' : $paramPageSize), 
      'pageOrientation' => ($paramPageOrientation === FALSE ? 'portrait' : $paramPageOrientation),  // $paramPageOrientation || 'portrait', // portrait/landscape
      'pageMargins' => ($arrPageMargins === FALSE ? array(30,5,280,400) : $arrPageMargins)  // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
    );
    return $arrDataPDF;
    // return $fData['id'];
}

function genera_pdf_cita($allInputs){
  $sede = empty($allInputs['itemSede']['sede']) ? $allInputs['itemSede']['descripcion'] : $allInputs['itemSede']['sede'];
  $especialidad = empty($allInputs['itemEspecialidad']['especialidad']) ? $allInputs['itemEspecialidad']['descripcion'] : $allInputs['itemEspecialidad']['especialidad'];
  $fecha = empty($allInputs['fecha_formato']) ? $allInputs['seleccion']['fecha_programada'] : $allInputs['fecha_formato'];
  $hora = empty($allInputs['hora_inicio_formato']) ? $allInputs['seleccion']['hora_formato'] : $allInputs['hora_inicio_formato'];
  
  $arrDatos = array( 
    array(
      'text'=> array(' ')
    ),
    array(
      'text'=> array(
          array(
            'text'=>'Sede: ',
            'style'=> 'filterTitle'
          ),
          $sede
      )
    ),
    array(
      'text'=> array(
          array(
            'text'=>'Paciente: ',
            'style'=> 'filterTitle'
          ),
          strtoupper($allInputs['itemFamiliar']['paciente'])
      )
    ),
    array(
      'text'=> array(
          array(
            'text'=>'Fecha/Hora: ',
            'style'=> 'filterTitle'
          ),
          $fecha . ' ' . $hora
      )
    ),
    array(
      'text'=> array(
          array(
            'text'=>'Médico: ',
            'style'=> 'filterTitle'
          ),
          $allInputs['itemMedico']['medico']
      )
    ),
    array(
      'text'=> array(
          array(
            'text'=>'Especialidad: ',
            'style'=> 'filterTitle'
          ),
          $especialidad
      )
    ),
    array(
      'text'=> array(
          array(
            'text'=>'Consultorio: ',
            'style'=> 'filterTitle'
          ),
          $allInputs['itemAmbiente']['numero_ambiente']
      )
    ),
  );

  $arrContent[] = array( 
    $arrDatos,
  );
  $arrData['message'] = '';
  $arrData['flag'] = 1;
  $arrDataPDF = getPlantillaComprobanteCita($arrContent,$allInputs,'COMPROBANTE DE CITA','portrait','A4');
  return $arrDataPDF;
}