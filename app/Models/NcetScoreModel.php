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
    ];

    public $errorMsg;

}