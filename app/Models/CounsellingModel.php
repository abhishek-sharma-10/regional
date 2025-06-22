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
        'start_date',
        'end_date',
    ];

    public $errorMsg;


    function fetchAll(){
        $query = $this->db->query("SELECT * FROM counselling");

        if($query->getNumRows() > 0){
            return $query->getResult();
        }

        return [];
    }

    function getCounsellingStudentList(){
        $query = $this->db->query("SELECT * FROM registrations where status='Complete'");

        if($query->getNumRows() > 0){
            return $query->getResult();
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