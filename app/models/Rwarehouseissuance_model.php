<?php

class Rwarehouseissuance_model{
    private $db;

    public function __construct()
    {
		$this->db = new Database;
    }

    public function getReportData($strdate, $enddate){
       //$this->db->query("SELECT * FROM t_warehouse_issuance WHERE issueance_date BETWEEN '$strdate' AND '$enddate'");
        
//$this->db->query("SELECT distinct a.assy_location, b.barcode_serial,b.part_number, b.part_lot, b.issueance_date, b.ageing_status,b.ft_status FROM t_part_location as a inner JOIN t_warehouse_issuance as b ON a.part_number = b.part_number BETWEEN '$strdate' AND '$enddate'");
$this->db->query("SELECT DISTINCT `barcode_serial`, `issuance_number`, t_warehouse_issuance.issueance_date, `part_lot`, t_warehouse_issuance.part_number, `ageing_status`, `ft_status`, `assy_location` from t_warehouse_issuance INNER JOIN t_part_location ON t_warehouse_issuance.part_number = t_part_location.part_number WHERE t_warehouse_issuance.issueance_date BETWEEN '$strdate' AND '$enddate'");
return $this->db->resultSet();
    }
}


