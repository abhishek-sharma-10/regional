<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class NCETApplicationModel extends Model {
    protected $table = 'ncet_applications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'ncet_application_no',
        'name',
        'email',
        'mobile_no',
        'father_name',
        'mother_name',
        'dob',
        'subject_code',
        'subject_name',
        'gender',
        'category_name',
        'physical_disablility',
        'state',
        'address',
        'district',
        'pincode',
        'passing_year_10',
        'board_10',
        'board_other_10',
        'total_marks_10',
        'obtain_marks_10',
        'percentage_10',
        'passing_year_12',
        'board_12',
        'board_other_12',
        'total_marks_12',
        'obtain_marks_12',
        'percentage_12',
        'subject_percentile',
        'notification_status'
    ];

    public $errorMsg;


    function fetchAll(){
        $query = $this->db->query("SELECT * FROM ncet_applications ");

        if($query->getNumRows() > 0){
            return $query->getResult();
        }

        return [];
    }

    function fetchSubjectDetailsByApplicationNo($ncet_application_no){
        $query = $this->db->query("SELECT ncet_applications.subject_code, ncet_applications.subject_percentile, subjects.subject as subject_name FROM `ncet_applications` JOIN subjects ON ncet_applications.subject_code = subjects.code WHERE ncet_application_no = $ncet_application_no");

        if($query->getNumRows() > 0){
            return $query->getResult();
        }

        return [];
    }

    function checkApplication($ncet_application_no, $score='no'){
        $query = $this->db->query("SELECT ncet_applications.*, percentile.percentile_total from ncet_applications JOIN percentile ON ncet_applications.ncet_application_no = percentile.ncet_application_no WHERE ncet_applications.ncet_application_no=$ncet_application_no");

        if($query->getNumRows() > 0){
            $data = [];
            foreach($query->getResult() as $row){
                $data['name'] = $row->name;
                $data['gender'] = $row->gender;
                $data['dob'] = $row->dob;
                $data['father_name'] = $row->father_name;
                $data['mother_name'] = $row->mother_name;
                $data['address'] = $row->address;
                $data['state'] = $row->state;
                $data['pincode'] = $row->pincode;
                $data['mobile_no'] = $row->mobile_no;
                $data['category_name'] = $row->category_name;
                $data['physical_disability'] = $row->physical_disability;
            }

            if($score == 'yes'){
                $data['passing_year_10'] = $row->passing_year_10;
                $data['board_10'] = $row->board_10;
                $data['board_other_10'] = $row->board_other_10;
                $data['total_marks_10'] = $row->total_marks_10;
                $data['obtain_marks_10'] = $row->obtain_marks_10;
                $data['percentage_10'] = $row->percentage_10;
                $data['passing_year_12'] = $row->passing_year_12;
                $data['board_12'] = $row->board_12;
                $data['board_other_12'] = $row->board_other_12;
                $data['total_marks_12'] = $row->total_marks_12;
                $data['obtain_marks_12'] = $row->obtain_marks_12;
                $data['percentage_12'] = $row->percentage_12;
                $data['percentile_total'] = $row->percentile_total;
            }
            return $data;
        }

        return [];
    }

    function getApplicantEmails(){
        $query = $this->db->query("SELECT DISTINCT ncet_application_no, name, email FROM `ncet_applications` WHERE notification_status='NOT SENT' LIMIT 100");

        if($query->getNumRows() > 0){
            return $query->getResult();
        }

        return [];
    }
}