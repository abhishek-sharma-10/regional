<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class RegistrationModel extends Model {
    protected $table = 'registrations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'registration_no',
        'ncet_application_no',
        'name',
        'gender',
        'dob',
        'father_name',
        'mother_name',
        'address',
        'city',
        'district',
        'state',
        'pincode',
        'aadhar_no',
        'category',
        'physical_disable',
        'course',
        'board_10th',
        'board_10th_other',
        'year_of_passing_10th',
        'max_marks_10th',
        'obtain_marks_10th',
        'percentage_10th',
        'discipline',
        'board_12th',
        'board_12th_other',
        'year_of_passing_12th',
        'max_marks_12th',
        'obtain_marks_12th',
        'percentage_12th',
        'bsc_preference_1',
        'bsc_preference_2',
        'bsc_preference_3',
        'bsc_preference_4',
        'ba_preference_1',
        'ba_preference_2',
        'ba_preference_3',
        'email',
        'phone',
        'password',
        'ncet_roll_no',
        'itep_course',
        'ncet_average_percentile',
        'photo',
        'signature',
        'certificate_10',
        'certificate_12',
        'ncet_score_card',
        'cast_certificate',
        'pwbd',
        'receipt_no',
        'payment_receipt',
        'acknowledged',
        'status'
    ];

    public $errorMsg;

	function getRegistrations(){
        $query = $this->db->query("SELECT id, ncet_application_no, name, father_name, email, phone, status FROM registrations");
    
        if($query->getNumRows() > 0){
            return $query->getResult();
        }
        return [];
    }

    function getRegistrationDetail($id){
        $query = $this->db->query("SELECT * FROM registrations where id=$id");
    
        if($query->getNumRows() > 0){
            $result = $query->getResult();
            return $result[0];
        }
        return [];
    }

    // Check If User Already Registered
    function checkNCETApplication($id){
        $query = $this->db->query("SELECT id FROM registrations where ncet_application_no='$id'");
    
        if($query->getNumRows() > 0){
            return $query->getResult();
        }
        return [];
    }

    function getRegistrationByEmail($email){
        $query = $this->db->query("SELECT id,ncet_application_no, email FROM registrations where email='$email'");
    
        if($query->getNumRows() > 0){
            return $query->getResult();
        }
        return [];
    }
    
    function getRegistrationCounseleDetail($id){
        $query = $this->db->query("SELECT registrations.*, student_counselling.id AS student_counselling_id, student_counselling.counselling_id AS counselling_id, student_counselling.academic_receipt_no, student_counselling.academic_payment_receipt, student_counselling.payment_date AS academic_payment_date FROM registrations LEFT JOIN student_counselling ON registrations.id = student_counselling.registration_id where registrations.id=$id");
    
        if($query->getNumRows() > 0){
            $result = $query->getResult();
            return $result[0];
        }
        return [];
    }
}