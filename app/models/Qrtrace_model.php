<?php

class Qrtrace_model{

    private $db;
    public function __construct()
    {
		  $this->db = new Database;
    }

    public function getQrDetails($params){
        $url      = parse_url($_SERVER['REQUEST_URI']);
        $data     = parse_str($url['query'], $params);
        $qrcode   = $params['qrcode'];

        $this->db->query("SELECT * FROM t_qr_registration WHERE qrcode='$qrcode'");
		return $this->db->single();
    }

    public function checkLatestProcess($params){
        $url      = parse_url($_SERVER['REQUEST_URI']);
        $data     = parse_str($url['query'], $params);
        $qrcode   = $params['qrcode'];

        $this->db->query("SELECT * FROM t_qr_registration WHERE qrcode='$qrcode'");
		return $this->db->single();
    }

    public function getQrBoardDetails($params){
        $url      = parse_url($_SERVER['REQUEST_URI']);
        $data     = parse_str($url['query'], $params);
        $qrcode   = $params['qrcode'];

        $this->db->query("SELECT * FROM t_qr_registration_boards WHERE qrcode='$qrcode'");
		return $this->db->resultSet();
    }

    public function findBoardData($params){
        $url      = parse_url($_SERVER['REQUEST_URI']);
        $data     = parse_str($url['query'], $params);
        $qrcode   = $params['boardcode'];

        $this->db->query("SELECT * FROM v_qrcode_details WHERE board_code='$qrcode'");
		
        return $this->db->single();
    }

    public function checkBoardProcess($data){
        $boardcode = $data['board_code'];
        $process   = $data['board_process'];

        $this->db->query("SELECT * FROM t_board_process WHERE board_code='$boardcode' AND board_process='$process'");
		
        return $this->db->single();
    }

    public function findBoardProcess($params){
        $url      = parse_url($_SERVER['REQUEST_URI']);
        $data     = parse_str($url['query'], $params);
        $qrcode   = $params['boardcode'];

        $this->db->query("SELECT * FROM t_board_process WHERE board_code='$qrcode' ORDER BY id DESC");
		
        return $this->db->single();
    }

    public function saveQrRegistration($data)
    {
        // echo json_encode($data);
        
        $currentDate = date('Y-m-d h:m:s');
        
        $query = "INSERT INTO t_qr_registration(qrcode, kepi_lot, model, assy_code, prod_line, operator, createdby, createdon) 
                      VALUES(:qrcode, :kepi_lot, :model, :assy_code, :prod_line, :operator, :createdby, :createdon)";

        $this->db->query($query);
        
        $this->db->bind('qrcode',    $data['qrcode']);
        $this->db->bind('kepi_lot',  $data['kepilot']);
        $this->db->bind('model',     $data['partmodel']);
        $this->db->bind('assy_code', $data['assycode']);
        $this->db->bind('prod_line', $data['smtline']);
        $this->db->bind('operator',  $_SESSION['usr']['user']);
        $this->db->bind('createdon', $currentDate);
        $this->db->bind('createdby', $_SESSION['usr']['user']);
        $this->db->execute();

        $boards = $data['boards'];
        for($i = 0; $i < count($boards); $i++){
            $query = "INSERT INTO t_qr_registration_boards(qrcode,board_code,createdby, createdon) 
                      VALUES(:qrcode,:board_code,:createdby,:createdon)";
            $this->db->query($query);

            $this->db->bind('qrcode',      $data['qrcode']);
            $this->db->bind('board_code',  $boards[$i]);
            $this->db->bind('createdon',   $currentDate);
            $this->db->bind('createdby',   $_SESSION['usr']['user']);
            $this->db->execute();
        }
        
        return $this->db->rowCount();
    }

    public function saveQRProcess($data)
    {
        $currentDate = date('Y-m-d h:m:s');

        $query = "UPDATE t_qr_registration set last_process=:last_process WHERE qrcode=:qrcode";
        $this->db->query($query);

        $this->db->bind('qrcode',       $data['qrcode']);
        $this->db->bind('last_process', $data['qrprocess']);
        $this->db->execute();
        
        $boards = $data['boards'];
        for($i = 0; $i < count($boards); $i++){
            $query = "INSERT INTO t_qr_process(qrcode,board_code,process,smt_vi_result,createdby, createdon) 
                      VALUES(:qrcode,:board_code,:process,:smt_vi_result,:createdby,:createdon)";
            $this->db->query($query);

            $this->db->bind('qrcode',        $data['qrcode']);
            $this->db->bind('board_code',    $boards[$i]);
            $this->db->bind('process',       $data['qrprocess']);
            if($data['qc_result'] === "NG"){
                if($data['boardCode'] === $boards[$i]){
                    $this->db->bind('smt_vi_result', "NG");    
                }else{
                    $this->db->bind('smt_vi_result', "GOOD");
                }
            }else{
                $this->db->bind('smt_vi_result', $data['qc_result']);
            }
            $this->db->bind('createdon',     $currentDate);
            $this->db->bind('createdby',     $_SESSION['usr']['user']);
            $this->db->execute();
        }

        return $this->db->rowCount();
    }

    public function saveBoardProcess($data){
        $currentDate = date('Y-m-d h:m:s');
        
        $query = "INSERT INTO t_board_process(qrcode,board_code,board_process,board_status,process_date,createdby, createdon) 
                      VALUES(:qrcode,:board_code,:board_process,:board_status,:process_date,:createdby,:createdon)";
        $this->db->query($query);

        $this->db->bind('qrcode',        $data['qrcode']);
        $this->db->bind('board_code',    $data['board_code']);
        $this->db->bind('board_process', $data['board_process']);
        $this->db->bind('board_status',  $data['scan_result']);
        $this->db->bind('process_date',  date('Y-m-d'));
        $this->db->bind('createdon',     $currentDate);
        $this->db->bind('createdby',     $_SESSION['usr']['user']);
        $this->db->execute();

        return $this->db->rowCount();
    }
}