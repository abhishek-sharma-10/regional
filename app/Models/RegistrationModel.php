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
        'aadhar_no',
        'category',
        'physical_disable',
        'course',
        'email',
        'phone',
        'password',
        'preference_1',
        'preference_2',
        'preference_3',
        'preference_4',
        'preference_5',
        'discipline',
        'year_of_passing',
        'sr_sec_max_marks',
        'sr_sec_obtain_marks',
        'sr_sec_percentage',
        'ncet_roll_no',
        'itep_course',
        'photo',
        'signature',
        'certificate_10',
        'certificate_12',
        'ncet_score_card',
        'cast_certificate',
        'pwbd',
        'receipt_no',
        'payment_receipt',
        'status'
    ];

    public $errorMsg;

	function getRegistrations(){
        $query = $this->db->query("SELECT id, ncet_application_no, name, father_name, email, phone FROM registrations");
    
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
    
}