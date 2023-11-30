<?php
class CLS_Device{
 
    // database connection and table name
    private $conn;
    private $table_name = "t_devices_settings"; 
		
	public $sys_code_device;
	public $manufacturer_device;
	public $os_version_device;
	public $code_site_affected;
	public $code_user_connected;
	public $datesys;
	public $device_imei;
	public $device_name;
	public $short_code_device; 

 
    public function __construct($db){
        $this->conn = $db;
    }
 
    function read(){ 
        $query = "SELECT sys_code_device,manufacturer_device,short_code_device,device_name,os_version_device
                FROM
                    " . $this->table_name . "
                ORDER BY
                    manufacturer_device"; 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }
	
	function readAll($from_record_num, $records_per_page){ 
		$query = "SELECT sys_code_device,manufacturer_device,os_version_device,code_site_affected,code_user_connected,datesys,DATE_FORMAT(t_devices_settings.datesys,'%d/%m/%Y %H:%i:%s') as datesys_fr,device_imei,device_name,short_code_device  
				FROM " . $this->table_name . "
				ORDER BY
					manufacturer_device ASC
				LIMIT
					{$from_record_num}, {$records_per_page}";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();	 
		return $stmt;
	}

   function GetDetail(){
		 $query = "SELECT sys_code_device,manufacturer_device,os_version_device,code_site_affected,code_user_connected,datesys,DATE_FORMAT(t_devices_settings.datesys,'%d/%m/%Y %H:%i:%s') as datesys_fr,device_imei,device_name,short_code_device	FROM " . $this->table_name . "
			WHERE sys_code_device = ?
			LIMIT 0,1";
	 
		$stmt = $this->conn->prepare( $query );
		 $this->sys_code_device=htmlspecialchars(strip_tags($this->sys_code_device));
		$stmt->bindParam(1,$this->sys_code_device);
		$stmt->execute(); 
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
    }
	

// used for paging products
public function countAll(){
 
    $query = "SELECT sys_code_device FROM " . $this->table_name . "";
 
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
 
    $num = $stmt->rowCount();
 
    return $num;
}

// read products by search term
public function search($search_term, $from_record_num, $records_per_page){
 
    // select query
    $query = "SELECT  sys_code_device,manufacturer_device,os_version_device,code_site_affected,code_user_connected,t_devices_settings.datesys,DATE_FORMAT(t_devices_settings.datesys,'%d/%m/%Y %H:%i:%s') as datesys_fr,device_imei,device_name,short_code_device,t_site_perception.intitule_site FROM t_devices_settings
INNER JOIN t_site_perception ON t_devices_settings.code_site_affected = t_site_perception.code_site
 WHERE t_devices_settings.manufacturer_device Like :search_term OR  t_devices_settings.device_imei Like :search_term OR  t_devices_settings.code_site_affected Like :search_term OR  t_site_perception.intitule_site Like :search_term ORDER BY manufacturer_device ASC LIMIT :from, :offset";  
    $stmt = $this->conn->prepare( $query ); 
    $search_term = "%{$search_term}%";
    $stmt->bindParam(':search_term', $search_term); 
    $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
 
    // execute query
    $stmt->execute();
 
    // return values from database
    return $stmt;
}
public function countAll_BySearch($search_term){
 
    // select query
    $query = "SELECT
                COUNT(*) as total_rows
            FROM t_devices_settings
INNER JOIN t_site_perception ON t_devices_settings.code_site_affected = t_site_perception.code_site WHERE 
                t_devices_settings.manufacturer_device Like :search_term OR  t_devices_settings.device_imei Like :search_term OR  t_devices_settings.code_site_affected Like :search_term OR  t_site_perception.intitule_site Like :search_term";  
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind variable values
    $search_term = "%{$search_term}%";
    $stmt->bindParam(':search_term', $search_term); 
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC); 
    return $row['total_rows'];
}
}
?>