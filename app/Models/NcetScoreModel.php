<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class NcetScoreModel extends Model {
    protected $table = 'ncet_scores';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'registration_id',
        'codes',
        'subjects',
        'total_maximum_marks',
        'total_marks_obtain',
        'percentage',
    ];

    public $errorMsg;


    function getNcetScoreByRegistrationId($reg_id){
        $query = $this->db->query("SELECT * FROM ncet_scores WHERE registration_id=$reg_id ORDER BY id");

        if($query->getNumRows() > 0){
            return $query->getResult();
        }

        return [];
    }
}