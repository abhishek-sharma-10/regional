<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class UserModel extends Model {
	function getSchoolInfo(){
        $result = array();
        $query = "SELECT * FROM `school_info` WHERE id = '12345' ";    
        $queryResult = $this->db->query($query);
            
        if($queryResult->getNumRows() > 0){
            foreach($queryResult->getResult() as $qr){
                $result = $qr;
            }
            
        }         
        return $result;
    }
}