<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class ITEPSeatMatrixModel extends Model {
    protected $table = 'itep_seats_matrix';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'course',
        'disciplinary_major',
        'general',
        'general_used',
        'general_available',
        'obc-ncl',
        'obc-ncl_used',
        'obc-ncl_available',
        'sc',
        'sc_used',
        'sc_available',
        'st',
        'st_used',
        'st_available',
        'ews',
        'ews_used',
        'ews_available',
        'pwd',
        'pwd_used',
        'pwd_available',
        'pwd_general',
        'session',
    ];

    public $errorMsg;


    function fetchAll(){
        $session = date('Y') . '-' . date('Y', strtotime('+1 year'));

        $query = $this->db->query("SELECT * FROM itep_seats_matrix WHERE session = '$session'");

        $matrix = [
            'physics' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
            'chemistry' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
            'mathematics' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
            'botany' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
            'zoology' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],

            'history' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
            'geography' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
            'english language and literature' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
            'hindi language and literature' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
            'urdu' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
        ];

        // $bsc_total_student = 0;
        // $ba_total_student = 0;

        $total_bsc_physical = 0;
        $total_ba_physical = 0;
        $bsc_pwd_general = 0;
        $ba_pwd_general = 0;

        if($query->getNumRows() > 0){
            foreach($query->getResultArray() as $value){
                $matrix[strtolower($value['disciplinary_major'])]['general'] = $value['general_available'];
                $matrix[strtolower($value['disciplinary_major'])]['obc-(ncl)'] = $value['obc_ncl_available'];
                $matrix[strtolower($value['disciplinary_major'])]['sc'] = $value['sc_available'];
                $matrix[strtolower($value['disciplinary_major'])]['st'] = $value['st_available'];
                $matrix[strtolower($value['disciplinary_major'])]['ews'] = $value['ews_available'];

                if($value['course'] == 'B.Sc. B.Ed.'){
                    // $bsc_total_student += $value['general'] + $value['obc-(ncl)'] + $value['sc'] + $value['st'] + $value['ews'];
                    $total_bsc_physical = $value['pwd_available'];
                    $bsc_pwd_general = $value['pwd_general'];
                }else{
                    // $ba_total_student += $value['general'] + $value['obc-(ncl)'] + $value['sc'] + $value['st'] + $value['ews'];
                    $total_ba_physical = $value['pwd_available'];
                    $ba_pwd_general = $value['pwd_general'];
                }
            }

            // $total_bsc_physical = ($bsc_total_student * 5)/100;
            // $total_ba_physical = ceil(($ba_total_student * 5)/100);

            return [$matrix, $total_bsc_physical, $total_ba_physical, $bsc_pwd_general, $ba_pwd_general];
        }

        return [];
    }

    function fetchBySubject($subject){
        $session = date('Y') . '-' . date('Y', strtotime('+1 year'));

        $query = $this->db->query("SELECT * FROM itep_seats_matrix WHERE disciplinary_major='$subject' AND session = '$session'");

        if($query->getNumRows() > 0){
            return $query->getResultArray()[0];
        }

        return [];
    }
}