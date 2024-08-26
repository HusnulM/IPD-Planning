<?php

class Production extends Controller {
    public function __construct(){
		if( isset($_SESSION['usr']) ){
		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
        $check = $this->model('Home_model')->checkUsermenu('production','Create');
        if ($check){
            $data['title'] = 'Input Production Planning';
            $data['menu']  = 'Input Production Planning';  

            $data['lines'] = $this->model('Line_model')->getListProductionLines();   

            $this->view('templates/header_a', $data);
            $this->view('production/index', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }        
    }

    public function updateplanning(){
        $check = $this->model('Home_model')->checkUsermenu('production','Create');
        if ($check){
            $data['title'] = 'Update Production Planning';
            $data['menu']  = 'Update Production Planning';  

            $data['lines'] = $this->model('Line_model')->getListProductionLines();   

            $this->view('templates/header_a', $data);
            $this->view('production/update', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }        
    }

    public function inputactualqty(){
        $check = $this->model('Home_model')->checkUsermenu('production/inputactualqty','Create');
        if ($check){
            $data['title'] = 'Input Actual Production Quantity';
            $data['menu']  = 'Input Actual Production Quantity';

            $data['lines'] = $this->model('Line_model')->getListProductionLines();   
            $data['hrtimes'] = $this->model('Production_model')->getHourlyTimes();

            $this->view('templates/header_a', $data);
            $this->view('production/inputActual', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }         
    }
    
    public function save(){
		if( $this->model('Production_model')->savePlanning($_POST) > 0 ) {
			Flasher::setMessage('Planning Created','','success');
			header('location: '. BASEURL . '/production');
			exit;			
		}else{
			Flasher::setMessage('Failed,','','danger');
			header('location: '. BASEURL . '/production');
			exit;	
	    }
    }

    public function saveupdate(){
		if( $this->model('Production_model')->savePlanning($_POST) > 0 ) {
			Flasher::setMessage('Planning Updated','','success');
			header('location: '. BASEURL . '/production/updateplanning');
			exit;			
		}else{
			Flasher::setMessage('Failed,','','danger');
			header('location: '. BASEURL . '/production/updateplanning');
			exit;	
	    }
    }

    public function deleteplanning(){
        // echo json_encode($_POST);
        $data = $this->model('Production_model')->getPlanningInfo($_POST);
        // echo json_encode($data);
        if(!$data){
            if( $this->model('Production_model')->deletePlanning($_POST) > 0 ) {
                $result = array(
                    "msgtype" => "1",
                    "message" => "Success"
                );
                echo json_encode($result);
                exit;			
            }else{
                $result = array(
                    "msgtype" => "2",
                    "message" => "Failed delete planning"
                );
                echo json_encode($result);
                exit;	
            }
        }else{
            $result = array(
                "msgtype" => "2",
                "message" => "Actual Output already exists"
            );
            echo json_encode($result);
            exit;	
        }
    }

    public function getplanning(){
        $data = $this->model('Production_model')->getPlanningData($_POST);
        echo json_encode($data);
    }

    public function getactualdata(){
        $data = $this->model('Production_model')->getActualData($_POST);
        echo json_encode($data);
    }

    public function getdailyplanning($pDate, $pLine, $pShift, $pSection){
        $data = $this->model('Production_model')->getDaylyPlanning($pDate, $pLine, $pShift, $pSection);
        echo json_encode($data);
    }

    public function hourlymonitoringview($params){
        $url  = parse_url($_SERVER['REQUEST_URI']);
        $data = parse_str($url['query'], $params);
        // $plandate = $params['plandate'];
        // $prodline = $params['prodline'];
        // $shift    = $params['shift'];
        $plandate = date('Y-m-d');

        $data['title'] = 'Hourly Production Monitoring';
        $data['menu']  = 'Hourly Production Monitoring';
        // echo json_encode($prodline, $prodline, $shift);
        // $data['title'] = 'Hourly Production Monitoring';
        // $data['menu']  = 'Hourly Production Monitoring';
        // // $data['rdata'] = $this->model('')->getHourlyMonitoring($_GET);

        $data['chour'] = $this->model('Production_model')->getServerHour();

        if($data['chour']['serverhour'] >= 6 && $data['chour']['serverhour'] <= 18){
            $shift = 1;
        }else{
            $shift = 2;
        }

        // $data['lines']    = $this->model('Line_model')->getListProductionLines();
        $data['lines']    = $this->model('Production_model')->getListProductionLines($plandate, $shift);
        $data['rdata']    = $this->model('Production_model')->getHourlyMonitoringViewV2($plandate, $shift);
        $data['plandate'] = $plandate;
        // $data['prodline'] = $prodline;
        $data['shift']    = $shift;

        $this->view('templates/header_a', $data);
        $this->view('production/hourlymonitoringview', $data);
        $this->view('templates/footer_a');
    }

    public function hourlymonitoring(){

        // echo json_encode($_GET);
        $data['title'] = 'Hourly Production Monitoring';
        $data['menu']  = 'Hourly Production Monitoring';
        // $data['rdata'] = $this->model('')->getHourlyMonitoring($_GET);

        $data['lines'] = $this->model('Line_model')->getListProductionLines();

        $this->view('templates/header_a', $data);
        $this->view('production/hourlymonitoring', $data);
        $this->view('templates/footer_a');
    }

    public function gethourlyoutput(){
        $data = $this->model('Production_model')->getHourlyMonitoring($_POST);
        echo json_encode($data);
    }

    public function productionview(){
        $datenow = date('Y-m-d');
        $date = $datenow." 07:01:00";
        $date1 = str_replace('-', '/', $date);
        
        // echo $tomorrow;
        // $displayDate 

        $data['title'] = 'Production View';
        $data['menu']  = 'Production View';
        $viewDate = date('Y-m-d H:m:s');
        if(isset($_SESSION['prodviewdateID'])){
            if($_SESSION['prodviewdateID'] === "0"){
                Helpers::setID(1);
                $tomorrow = date('Y-m-d H:m:s',strtotime($date1 . "+1 days"));
                $viewDate = $tomorrow;//
                $date = new DateTime('+1 day');
            }else{
                Helpers::setID(2);    
                $date = new DateTime();
                $viewDate = date('Y-m-d H:m:s');
            }
        }else{
            Helpers::setID(2);
            $date = new DateTime();
            $viewDate = date('Y-m-d H:m:s');
        }

        $displayDate = $date->format('Y-m-d');

        // Helpers::setProdViewDate(date('Y-m-d'));

        $data['rdata'] = $this->model('Production_model')->planningMonitoring($viewDate);
        $data['rday1'] = $this->model('Production_model')->planningMonitoringDay1($viewDate);
        $data['rday2'] = $this->model('Production_model')->planningMonitoringDay2($viewDate);
            
        $data['rday3'] = $this->model('Production_model')->planningMonitoringDay3($viewDate);
        $data['hdata'] = $this->model('Production_model')->planningMonitoringDate($viewDate);

        $data['ctime'] = $this->model('Production_model')->getServerTime();
        $data['chour'] = $this->model('Production_model')->getServerHour();

        $data['viewdate'] = $displayDate;
            
        $this->view('templates/header_a', $data);
        $this->view('production/productionviewV3', $data);
        $this->view('templates/footer_a');
    }

    public function saveactualdata(){
        if( $this->model('Production_model')->saveactualdata($_POST) > 0 ) {
			$return = array(
                "msgtype" => "1",
                "message" => "Actual Quantity Inserted"
            );
            echo json_encode($return);
			exit;			
		}else{
			$return = array(
                "msgtype" => "2",
                "message" => "Insert Actual Quantity Failed"
            );
            echo json_encode($return);
			exit;	
	    }
    }

    public function searchMaterial(){
        $url    = parse_url($_SERVER['REQUEST_URI']);
        $search = $url['query'];
        $search = str_replace("searchName=","",$search);

        $result['data'] = $this->model('Production_model')->searchMaterial($search);
        echo json_encode($result);
    }
}