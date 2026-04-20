<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class CommonModel extends Model {
	
	function getSubjectByCode($code, $section){
        $query = "SELECT * from subjects where code = '".$code."' AND section='".$section."'";
        $result = $this->db->query($query);
        
		if($result->getNumRows() > 0){
			return $result->getResult();
		}
        
        return [];
    }

    function getFeesStructureByCategory($category){
        $query = "SELECT * from fees_structure where category = '".strtolower($category)."'";
        $result = $this->db->query($query);
        
		if($result->getNumRows() > 0){
			return $result->getResult()[0];
		}
        
        return [];
    }

    function getOverallPercentage($ncet_application_no){
        $query = "SELECT * from percentile WHERE ncet_application_no= '".$ncet_application_no."'";
        $result = $this->db->query($query);
        if($result->getNumRows() > 0){
			return $result->getResult()[0];
		}
        
        return [];
    }
    
    function getSpotCounsellingList(){
        $query = "SELECT * FROM `registrations` WHERE id NOT IN (SELECT registration_id FROM student_counselling) AND receipt_no IS NOT NULL AND payment_receipt IS NOT NULL AND spot_counselling_mail IS NULL LIMIT 250";
        $result = $this->db->query($query);
        if($result->getNumRows() > 0){
			return $result->getResult();
		}
        
        return [];
    }
}