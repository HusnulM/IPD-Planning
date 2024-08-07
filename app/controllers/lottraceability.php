<?php
class Lottraceability extends Controller {
    public function __construct(){
		if( isset($_SESSION['usr']) ){
		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('lottraceability/registration','Read');
		if ($check){
			$data['title'] = 'QR CODE BOARD REGISTRATION';
			$data['menu']  = 'QR CODE BOARD REGISTRATION';  

			$data['lines'] = $this->model('Line_model')->getListProductionLines();

			$this->view('templates/header_a', $data);
			$this->view('traceability/index', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		} 
	}

	public function mounter(){
		$check = $this->model('Home_model')->checkUsermenu('lottraceability/registration','Read');
		if ($check){
			
			$data['title'] = 'QR CODE BOARD MOUNTER';
			$data['menu']  = 'QR CODE BOARD MOUNTER';  

			$data['lines'] = $this->model('Line_model')->getListProductionLines();

			$this->view('templates/header_a', $data);
			$this->view('traceability/mounter', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		} 
	}

	public function smtvi(){
		$check = $this->model('Home_model')->checkUsermenu('lottraceability/registration','Read');
		if ($check){
			
			$data['title'] = 'QR CODE SMT VI';
			$data['menu']  = 'QR CODE SMT VI';  

			$data['lines'] = $this->model('Line_model')->getListProductionLines();

			$this->view('templates/header_a', $data);
			$this->view('traceability/smtvi', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		} 
	}

	public function smtaoi(){
		$check = $this->model('Home_model')->checkUsermenu('lottraceability/registration','Read');
		if ($check){
			
			$data['title'] = 'QR CODE SMT AOI';
			$data['menu']  = 'QR CODE SMT AOI';  

			$data['lines'] = $this->model('Line_model')->getListProductionLines();

			$this->view('templates/header_a', $data);
			$this->view('traceability/smtaoi', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		} 
	}

	public function smtai(){
		$check = $this->model('Home_model')->checkUsermenu('lottraceability/registration','Read');
		if ($check){
			
			$data['title'] = 'QR CODE SMT AI';
			$data['menu']  = 'QR CODE SMT AI';  

			$data['lines'] = $this->model('Line_model')->getListProductionLines();

			$this->view('templates/header_a', $data);
			$this->view('traceability/smtai', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		} 
	}

	public function smtmi(){
		$check = $this->model('Home_model')->checkUsermenu('lottraceability/registration','Read');
		if ($check){
			
			$data['title'] = 'QR CODE SMT MI';
			$data['menu']  = 'QR CODE SMT MI';  

			$data['lines'] = $this->model('Line_model')->getListProductionLines();

			$this->view('templates/header_a', $data);
			$this->view('traceability/smtmi', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		} 
	}

	public function mod1(){
		$check = $this->model('Home_model')->checkUsermenu('lottraceability/mod1','Read');
		if ($check){
			
			$data['title'] = 'QR CODE MOD1';
			$data['menu']  = 'QR CODE MOD1';  

			$data['lines'] = $this->model('Line_model')->getListProductionLines();

			$this->view('templates/header_a', $data);
			$this->view('traceability/mod1', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		} 
	}

	public function mod2(){
		$check = $this->model('Home_model')->checkUsermenu('lottraceability/mod2','Read');
		if ($check){
			
			$data['title'] = 'QR CODE MOD2';
			$data['menu']  = 'QR CODE MOD2';  

			$data['lines'] = $this->model('Line_model')->getListProductionLines();

			$this->view('templates/header_a', $data);
			$this->view('traceability/mod2', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		} 
	}

	public function getqrdetails($qrcode){
		$data['header'] = $this->model('Qrtrace_model')->getQrDetails($qrcode);
		$data['detail'] = $this->model('Qrtrace_model')->getQrBoardDetails($qrcode);

		echo json_encode($data);
	}

	public function latestprocess($qrcode){
		$data = $this->model('Qrtrace_model')->checkLatestProcess($qrcode);

		echo json_encode($data);
	}
	

	public function saveqrcode(){
		// $this->model('Qrtrace_model')->saveQrRegistration($_POST);
		if( $this->model('Qrtrace_model')->saveQrRegistration($_POST) > 0 ) {
			$result = array(
				"msgtype" => "1",
				"message" => "Success"
			);
			echo json_encode($result);
			exit;			
		}else{
			$result = array(
				"msgtype" => "2",
				"message" => "QR registration fail"
			);
			echo json_encode($result);
			exit;	
	    }
    }

	public function saveprocess(){
		// $this->model('Qrtrace_model')->saveQrRegistration($_POST);
		if( $this->model('Qrtrace_model')->saveQRProcess($_POST) > 0 ) {
			$result = array(
				"msgtype" => "1",
				"message" => "Success"
			);
			echo json_encode($result);
			exit;			
		}else{
			$result = array(
				"msgtype" => "2",
				"message" => "QR registration fail"
			);
			echo json_encode($result);
			exit;	
	    }
    }
}