<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class CounterModel extends Model {
    protected $table = 'year_counters';
    protected $allowedFields = [
        'year',
        'counter',
    ];

    public $errorMsg;


    function getCounter($year){
        $query = $this->db->query("SELECT * FROM year_counters WHERE year=$year");

        if($query->getNumRows() > 0){
            return $query->getResult()[0];
        }

        return [];
    }
}