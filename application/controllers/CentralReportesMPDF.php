<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CentralReportesMPDF extends CI_Controller { 

  public function __construct()
  {
    parent::__construct();
    $this->load->helper(array('security','reportes_helper','imagen_helper','fechas_helper','otros_helper','pdf_helper','contable_helper'));
    $this->load->model(array('model_config','model_resultadolaboratorio')); 
    //cache 
    $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
    $this->output->set_header("Pragma: no-cache");
    //$this->sessionHospital = @$this->session->userdata('sess_vs_'.substr(base_url(),-8,7));
    // $this->load->library('pdf'); 
    $this->load->library('excel');
    $this->load->library('fpdfext');
    date_default_timezone_set("America/Lima");
    //if(!@$this->user) redirect ('inicio/login');
    //$permisos = cargar_permisos_del_usuario($this->user->idusuario);
  } 
  public function ver_popup_reporte()
  {
    $this->load->view('centralReporte/popup_reporte');
  }

  public function report_resultado_laboratorio()
  {
    $allInputs = json_decode(trim($this->input->raw_input_stream),true); 
    $this->pdf = new Fpdfext(); 
    $fechaRecepcion = $allInputs['resultado']['fecha_muestra']; 
    $idempresaadmin = $allInputs['resultado']['idempresaadmin'];
    $formatFechaRecepcion = date('d/m/Y',strtotime("$fechaRecepcion"));
    $this->pdf->setNumeroHistoria($allInputs['resultado']['idhistoria']);  
    $this->pdf->setNumeroExamen($allInputs['resultado']['orden_lab']);  
    $this->pdf->setSexoPaciente($allInputs['resultado']['sexo']);
    $this->pdf->setEdadPaciente($allInputs['resultado']['edad']); 
    $this->pdf->setFechaRecepcion($formatFechaRecepcion); 
    $this->pdf->setPaciente(utf8_decode($allInputs['resultado']['paciente'])); 
 
    $empresaAdmin = $this->model_resultadolaboratorio->m_cargar_esta_empresa_por_codigo($idempresaadmin);
    //var_dump($empresaAdmin);
    $empresaAdmin['estado'] = $empresaAdmin['estado_emp'];
    $empresaAdmin['mode_report'] = FALSE;

    mostrar_plantilla_pdf($this->pdf,$allInputs['titulo'],FALSE,$allInputs['tituloAbv'],$empresaAdmin);
    // $this->pdf->SetFont('Courier','',12);
    $this->pdf->AddPage('P','A4');
    $this->pdf->AliasNbPages(); 
    
    /* CABECERA $this->pdf->SetMargins(8,8,7); SetFillColor */ 
    /* END */
    
    $this->pdf->Ln();
    $this->pdf->SetFont('Arial','B',9); 
    // var_dump($allInputs['resultado']); exit();
    if (!empty($allInputs['resultado']['arrSecciones'])){
      foreach ($allInputs['resultado']['arrSecciones'] as $seccion) { 
        $this->pdf->SetFont('Arial','B',9);
        if( $seccion['seleccionado'] ){
          $this->pdf->Ln(4);
          $this->pdf->Cell(0,7,$seccion['seccion'],1,0,'C'); 
          $this->pdf->Ln(10);
        //var_dump($seccion['seleccionado']); exit(); 
        
          $this->pdf->SetFont('Arial','',8);
          $analisisRepetido = FALSE;
          foreach ($seccion['analisis'] as $key => $analisis) {
            if($analisis['seleccionado']){ 
              if( $analisis['descripcion_anal'] ==  $analisis['parametros'][0]['parametro']){ 
                $analisisRepetido = TRUE;
              }else{
                $this->pdf->SetFont('Arial','B',8);
                $this->pdf->Cell(0,6,$analisis['descripcion_anal'],0,0,'L'); 
                $this->pdf->Ln();
                $this->pdf->SetFont('Arial','',7);
                $analisisRepetido = FALSE;
              }
              
              foreach ($analisis['parametros'] as $keyParam => $parametro) { 
                if(@trim($parametro['resultado']) == '--Seleccione Opcion--'){ 
                  @$parametro['resultado'] = '';
                }
                $arrBolds = FALSE;
                if($analisisRepetido){
                  $arrBolds = array('B','','','');
                  $this->pdf->Ln(2);
                }else{
                  $parametro['parametro'] = '  '.$parametro['parametro'];
                }
                if( $parametro['separador'] == 1 ){ 
                  $arrBolds = array('B','','',''); 
                  //$analisis['metodo'] = 'ASD';
                }else{
                  
                }
                $breaks = array("<br />","<br>","<br/>");  
                $parametroFormat = trim(str_ireplace($breaks, "\r\n", @$parametro['valor_normal']));
                $this->pdf->Row( 
                  array(
                    utf8_decode($parametro['parametro']),
                    @utf8_decode($parametro['resultado']),
                    @utf8_decode($parametroFormat),
                    strtoupper($analisis['metodo'])
                  ),
                  FALSE,
                  0,
                  $arrBolds,
                  3
                );
                $this->pdf->Ln(1);
                if( !empty($parametro['subparametros']) ){ 
                  if( $parametro['idparametro'] == 57 ){
                    $arrGroupByRes = array();
                    foreach ($parametro['subparametros'] as $keySP => $rowSP) { 

                      if(  trim($rowSP['resultado']) != '--Seleccione Opcion--' ){
                        $arrGroupByRes[$rowSP['resultado']] = array(
                          // 'resultado'=> $rowSP['resultado'],
                          'detalle'=> array() 
                        );
                      }
                    }
                    foreach ($parametro['subparametros'] as $keySP => $rowSP) { 
                      if(  trim($rowSP['resultado']) != '--Seleccione Opcion--' ){
                        $arrGroupByRes[$rowSP['resultado']]['detalle'][] = array( 
                          'parametro' => $rowSP['subparametro']
                        );
                      }
                    }
                    $arrFormatGB = array();
                    foreach ($arrGroupByRes as $key => $rowRes) { 
                      $arrFormatGB[0][$key] = $key;
                    }
                    foreach ($arrGroupByRes as $key => $rowRes) {
                      foreach ($rowRes['detalle'] as $keyDet => $rowDet) { 
                        $arrFormatGB[$keyDet+1][$key] = $rowDet['parametro'];
                      }
                    }
                    foreach ($arrFormatGB as $key => $row) { 
                      $this->pdf->SetFont('Arial','',7);
                      
                      if( $key === 0 ){
                        $this->pdf->SetFont('Arial','B',8); 
                        @$row['INTERMEDIO'] = '    '.@$row['INTERMEDIO']; 
                      }else{
                        @$row['INTERMEDIO'] = '      '.@$row['INTERMEDIO'];
                        @$row['RESISTENTE'] = '  '.@$row['RESISTENTE'];
                        @$row['SENSIBLE'] = '  '.@$row['SENSIBLE'];
                      }
                      $this->pdf->Cell(70,4,@$row['INTERMEDIO'],0,0,'TB'); 
                      $this->pdf->Cell(70,4,@$row['SENSIBLE'],0,0,'TB'); 
                      $this->pdf->Cell(70,4,@$row['RESISTENTE'],0,0,'TB'); 
                      $this->pdf->Ln(); 
                    } 
                    // var_dump($arrFormatGB); exit(); 
                  }else{ 
                    foreach ($parametro['subparametros'] as $keySubParam => $subparametro) { 
                      if( trim($subparametro['resultado']) !== '--Seleccione Opcion--' ){ 
                        $breaks = array("<br />","<br>","<br/>");  
                        $subParametroFormat = trim(str_ireplace($breaks, "\r\n", $subparametro['valor_normal']));
                        $this->pdf->Row( 
                          array(
                            '    '.utf8_decode($subparametro['subparametro']),
                            @utf8_decode($subparametro['resultado']),
                            utf8_decode($subParametroFormat),
                            strtoupper($analisis['metodo'])
                          ),
                          FALSE,
                          0,
                          FALSE,
                          3
                        ); 
                        $this->pdf->Ln(0); 
                        // $htmlData .= '<tr> <td  class="col_examen" style="padding-left: 40px;">- '. $subparametro['subparametro'] .' </td> <td class="col_resultado"> ' . @$subparametro['resultado'] .' </td> <td class="col_valor_normal">'. @$subparametro['valor_normal'] .' </td> <td class="col_metodo">  '. $analisis['metodo'] .' </td></tr>';
                      }
                    }
                  }
                }

              }
            }
          }
        }
        
      }
    }
    $arrData['message'] = 'ERROR';
    $arrData['flag'] = 2;
    $timestamp = date('YmdHis');
    if($this->pdf->Output( 'F','assets/img/dinamic/pdfTemporales/LAB_tempPDF_'. $timestamp .'.pdf' )){
      $arrData['message'] = 'OK';
      $arrData['flag'] = 1;
    }
    $arrData = array(
      'urlTempPDF'=> 'assets/img/dinamic/pdfTemporales/LAB_tempPDF_'. $timestamp .'.pdf'
    );
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
  }



}
?>