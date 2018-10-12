<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

require_once APPPATH.'third_party/spout242/src/Spout/Autoloader/autoload.php';
//lets Use the Spout Namespaces
use Box\Spout\Reader\ReaderFactory; 
use Box\Spout\Writer\WriterFactory; 
use Box\Spout\Common\Type; 

class CentralReportes extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->helper(array('security','reportes_helper','imagen_helper','fechas_helper','otros_helper'));
    $this->load->model(array('model_config'));
    $this->load->library('excel');
    //cache 
    $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
    $this->output->set_header("Pragma: no-cache");

    $this->sessionCitasEnLinea = @$this->session->userdata('sess_cevs_'.substr(base_url(),-8,7));
    date_default_timezone_set("America/Lima");
    //if(!@$this->user) redirect ('inicio/login');
    //$permisos = cargar_permisos_del_usuario($this->user->idusuario);
  } 
  public function ver_popup_reporte()
  {
    $this->load->view('centralReporte/popup_reporte');
  }
  public function ver_popup_grafico()
  {
    $this->load->view('centralReporte/popup_grafico');
  }
  public function guardar_pdf_en_temporal(){
    $data = substr($_POST['data'], strpos($_POST['data'], ",") + 1); 
    $decodedData = base64_decode($data);
    // subir_fichero();
    $arrData['message'] = 'ERROR';
    $arrData['flag'] = 2;
    if(file_put_contents('assets/img/dinamic/pdfTemporales/tempPDF_'.date('YmdHis').'.pdf', $decodedData)){
      $arrData['message'] = 'OK';
      $arrData['flag'] = 1;
    }
    $arrData = array(
      'urlTempPDF'=> 'assets/img/dinamic/pdfTemporales/tempPDF_'.date('YmdHis').'.pdf'
    );
    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arrData));
  }

  public function hola_excel()
  { 

    
    // use Box\Spout\Common\Type;
    try {
      $writer = WriterFactory::create(Type::XLSX); // for XLSX files
      //$writer = WriterFactory::create(Type::CSV); // for CSV files
      //$writer = WriterFactory::create(Type::ODS); // for ODS files

      //$writer->openToFile($filePath); // write data to a file or to a PHP stream 
      $fileName = 'GenerateExcel.xls'; 
      $writer->openToBrowser($fileName); // stream data directly to the browser 
      $singleRow = array('Hola','Chau','Buenos días','buenas tardes'); 
      $writer->addRow($singleRow); // add a row at a time 

      } catch (Exception $e) {

              echo $e->getMessage();
              exit;   
      }
    //$writer->addRows($multipleRows); // add multiple rows at a time

    $writer->close();
  }
}