<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class HomeModel extends Model {
	function getScheduleExams(){
        
    	$data =  $_SESSION["student"];

    	$regNo = $data[0]->reg_no;
        $fieldName = array("id");

        // $this->db->select($fieldName);
        // $this->db->where("reg_no",$regNo);
        // $queryResult = $this->db->get("student_courses");
    	
        $query = $this->db->query("SELECT id FROM student_courses WHERE reg_no='".$regNo."'");

        $findInSet = "";
        $count = $query->getNumRows();  
        $i=1;   
        if($count > 0)
        {
            $findInSet = "AND (";   
            foreach ($query->getResult() as $key => $value) {

                #var_dump($value["id"]);
                $findInSet .= "FIND_IN_SET('$value->id',bi.student_ids) > 0 ";
                if($i < $count)
                {
                    $findInSet .= "OR ";
                }
                $i++;
            }
            $findInSet .= ")";   
        }

		$examQuery = 'SELECT es.exam_date, es.start_time, es.end_time, es.practical, et.name FROM exam_schedule AS es JOIN exam_type AS et ON es.exam_type_id = et.id JOIN batch_info AS bi ON bi.id = es.batches_id WHERE es.exam_date >= CURRENT_DATE() '.$findInSet.' ORDER BY exam_date LIMIT 3';

        //echo $examQuery;
        $queryResult = [];
        $result = array();
        try{
            // $queryResult = $this->dbHelper->executeQuery($examQuery);
            $queryResult = $this->db->query($examQuery);
        }catch(Exception $e){
            var_dump($e);
        }
    
        if($queryResult->getNumRows() > 0){
            foreach($queryResult->getResult() as $qr){
                $result[] = $qr;
            } 
        }
        return $result;
    }

    function getFeesDetail(){
        $regNo = $_SESSION['student'][0]->reg_no;
        
        $query = "SELECT fee_deposit.id, fee_deposit.amount, fee_deposit.date, courses.course_name FROM fee_deposit LEFT JOIN student_courses ON fee_deposit.student_courses_id =student_courses.id AND fee_deposit.status = 'Active' LEFT JOIN courses ON student_courses.course_id = courses.course_id WHERE student_courses.reg_no = $regNo ORDER BY fee_deposit.id DESC limit 3";

        try{
            $result =  $this->db->query($query);
            if($result->getNumRows() > 0)
            {
            	return $result->getResult();
            }
            else
            {
            	return array();
            }
        }catch(Exception $e){
            var_dump($e);
        }
    }

    function getTodayResponseOfFeedback(){
        $regNo = $_SESSION['student'][0]->reg_no;
        $query = "SELECT response,modified_date,description,created_date from feedback where student_reg_no = $regNo AND status = 'close' AND DATE(modified_date) = CURRENT_DATE()";
        try{
            $result =  $this->db->query($query);
            
            if($result->getNumRows())
            {
            	return $result->getResult();
            }
            else
            {
            	return array(); 
            }
        }catch(Exception $e){
            var_dump($e);
        }
    }

    function changePassword($newpswd, $userid = NULL, $blankSecurityToken = null){

    	$newpswd = password_hash($newpswd, PASSWORD_DEFAULT);

        $query = "UPDATE users SET password='$newpswd'";

    	if($blankSecurityToken){
            $query .= ", security_token=''";		    
		}

        $query .= " WHERE id=$userid";

        $result = $this->db->query($query); 
        if($result)
        {
        	return true;
        }
        else
        {
        	return false;
        }
	}

    function updateStudent($insertArray){
        $result = array();  
        
        try{
            $query = "UPDATE student set city='".$insertArray['city']."', state='".$insertArray['state']."', email='".$insertArray['email']."', contact_no='".$insertArray['contact_no']."', address='".$insertArray['address']."' WHERE reg_no=".$_SESSION['student'][0]->reg_no;
            $result =  $this->db->query($query);
            if($result)
            {
                return true;
            }
            else
            {
                return false;
            }
        }catch(Exception $e){
            var_dump($e);
        }
    }

    function updateStudentSession(){
        $regNo = $_SESSION['student'][0]->reg_no;
        
        $query = "SELECT * FROM student WHERE reg_no=$regNo";

        $result = $this->db->query($query);

        if($result->getNumRows() > 0)
        {
            return $result->getResult();
        }
        else
        {
            return [];
        }
    }
}