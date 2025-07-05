<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class StudentCounsellingModel extends Model {
    protected $table = 'student_counselling';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'counselling_id',
        'registration_id',
        'category',
        'subject',
        'physical_disable',
        'academic_receipt_no',
        'academic_payment_receipt',
        'payment_date',
        'status'
    ];

    public $errorMsg;

    public function getSubjectWiseStudentList($subject){
        $query = "SELECT registrations.*, student_counselling.id AS student_counselling_id, student_counselling.counselling_id, student_counselling.academic_receipt_no, student_counselling.payment_date, student_counselling.academic_payment_receipt, student_counselling.category AS student_counselling_category, student_counselling.subject AS student_counselling_subject, student_counselling.physical_disable AS student_counselling_physical_disable FROM `student_counselling` JOIN registrations ON student_counselling.registration_id = registrations.id WHERE student_counselling.subject='$subject' ORDER BY registrations.ncet_average_percentile DESC";

        $result = $this->db->query($query);

        if($result->getNumRows() > 0)
            return $result->getResultArray();

        return [];
    }

}