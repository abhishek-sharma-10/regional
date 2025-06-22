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
        'academic_receipt_no',
        'academic_payment_receipt',
        'payment_date',
        'status'
    ];

    public $errorMsg;

}