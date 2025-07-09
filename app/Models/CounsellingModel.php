<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class CounsellingModel extends Model {
    protected $table = 'counselling';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    public $errorMsg;


    function fetchAll(){
        $query = $this->db->query("SELECT * FROM counselling");

        if($query->getNumRows() > 0){
            return $query->getResult();
        }

        return [];
    }

    // Fetch Student List to Create Counselling Data.
    function getCounsellingStudentList($condition = null){

        $query = "SELECT * FROM registrations where status='Complete' ";

        if($condition != null){
            $query .= $condition;
        }

        $query = $this->db->query($query);

        if($query->getNumRows() > 0){
            return $query->getResult();
        }

        return [];
    }

    function getCounsellingWiseStudentList($counsellingId, $fees){
        $query = "SELECT registrations.*, student_counselling.counselling_id, student_counselling.academic_receipt_no, student_counselling.payment_date, student_counselling.category AS student_counselling_category, student_counselling.subject AS student_counselling_subject, student_counselling.physical_disable AS student_counselling_physical_disable, student_counselling.status AS counselling_status FROM registrations JOIN student_counselling ON registrations.id = student_counselling.registration_id where student_counselling.counselling_id=$counsellingId ";

        if($fees == 'with fees'){
            $query .= "AND student_counselling.academic_receipt_no IS NOT NULL AND student_counselling.academic_payment_receipt IS NOT NULL ";
        }else if($fees == 'without fees'){
            $query .= "AND student_counselling.academic_receipt_no IS NULL AND student_counselling.academic_payment_receipt IS NULL ";
        }

        $query .= 'ORDER BY student_counselling.id';
        // echo ($query);
        $query = $this->db->query($query);

        if($query->getNumRows() > 0){
            return $query->getResult();
        }

        return [];
    }

    function getCounsellingStudentDetail($registrationId){
        $query = "SELECT registrations.*, student_counselling.id AS student_counselling_id, student_counselling.counselling_id, student_counselling.academic_receipt_no, student_counselling.payment_date, student_counselling.academic_payment_receipt, student_counselling.category AS student_counselling_category, student_counselling.subject AS student_counselling_subject, student_counselling.physical_disable AS student_counselling_physical_disable FROM registrations JOIN student_counselling ON registrations.id = student_counselling.registration_id WHERE student_counselling.registration_id=$registrationId";

        $query = $this->db->query($query);

        if($query->getNumRows() > 0){
            return $query->getResult()[0];
        }

        return [];
    }

    public function getUser($where) {
        return $this->db
                        ->table($this->table)
                        ->where($where)
                        ->get()
                        ->getRow();
    }
}