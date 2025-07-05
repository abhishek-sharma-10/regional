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
}