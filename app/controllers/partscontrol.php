<?php

class Partscontrol extends Controller {
    public function __construct(){
		if( isset($_SESSION['usr']) ){
		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
      $check = $this->model('Home_model')->checkUsermenu('partscontrol','Read');
      if ($check){
          $data['title'] = 'WAREHOUSE ISSUANCE';
          $data['menu']  = 'WAREHOUSE ISSUANCE';  

          $data['lines'] = $this->model('Line_model')->getListProductionLines();

          $this->view('templates/header_a', $data);
          $this->view('partscontrol/index', $data);
          $this->view('templates/footer_a');
      }else{
          $this->view('templates/401');
      }    
    }

    public function saveWhIssuance(){
      // echo json_encode($_POST);
      if( $this->model('Partscontrol_model')->saveWHIssuance($_POST) > 0 ) {
        Flasher::setMessage('Warehouse Issuance Created','','success');
        header('location: '. BASEURL . '/partscontrol');
        exit;			
      }else{
        Flasher::setMessage('Create Warehouse Issuance Failed','','danger');
        header('location: '. BASEURL . '/partscontrol');
        exit;	
      }
    }
}