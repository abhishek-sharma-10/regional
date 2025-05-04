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
}