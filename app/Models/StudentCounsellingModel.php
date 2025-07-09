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
        'course',
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

    public function getStudentCounsellingListWithoutFeesPay(){
        $query = "SELECT registrations.*, student_counselling.id AS student_counselling_id, student_counselling.counselling_id, student_counselling.academic_receipt_no, student_counselling.payment_date, student_counselling.academic_payment_receipt, student_counselling.category AS student_counselling_category, student_counselling.subject AS student_counselling_subject, student_counselling.physical_disable AS student_counselling_physical_disable FROM `student_counselling` JOIN registrations ON student_counselling.registration_id = registrations.id WHERE `academic_receipt_no` IS NULL AND `academic_payment_receipt` IS NULL ORDER BY registrations.ncet_average_percentile DESC;";

        $result = $this->db->query($query);

        if($result->getNumRows() > 0)
            return $result->getResultArray();

        return [];
    }

    public function getCourseWiseStudentList($id, $course){
        $query = "SELECT registrations.*, student_counselling.id AS student_counselling_id, student_counselling.counselling_id, student_counselling.academic_receipt_no, student_counselling.payment_date, student_counselling.academic_payment_receipt, student_counselling.category AS student_counselling_category, student_counselling.subject AS student_counselling_subject, student_counselling.physical_disable AS student_counselling_physical_disable FROM `student_counselling` JOIN registrations ON student_counselling.registration_id = registrations.id WHERE (registrations.course='$course' OR registrations.course='ITEP - B.Sc. B.Ed. & B.A. B.Ed.') AND student_counselling.counselling_id=$id ORDER BY registrations.ncet_average_percentile DESC";

        $result = $this->db->query($query);

        if($result->getNumRows() > 0)
            return $result->getResultArray();

        return [];
    }

    public function getAcceptedStudentCounsellingList(){
        $data = [];
        
        $query1 = "SELECT registrations.*, student_counselling.subject AS student_counselling_subject, student_counselling.course AS student_counselling_course FROM student_counselling inner join registrations on student_counselling.registration_id = registrations.id WHERE academic_receipt_no != '' and registrations.bsc_preference_1 != student_counselling.subject and registrations.course = 'ITEP - B.Sc. B.Ed.' AND student_counselling.status='Accept'";

        $result1 = $this->db->query($query1);
        if($result1->getNumRows() > 0){
            // return $result->getResultArray();
            foreach($result1->getResult() as $value){
                $data[] = $value;
            }
        }
        
        $query2 = "SELECT registrations.*, student_counselling.subject AS student_counselling_subject, student_counselling.course AS student_counselling_course FROM student_counselling inner join registrations on student_counselling.registration_id = registrations.id WHERE academic_receipt_no != '' and registrations.ba_preference_1 != student_counselling.subject and registrations.course = 'ITEP - B.A. B.Ed.' AND student_counselling.status='Accept'";

        $result2 = $this->db->query($query2);
        if($result2->getNumRows() > 0){
            // return $result->getResultArray();
            foreach($result2->getResult() as $value){
                $data[] = $value;
            }
        }
        
        $query3 = "SELECT registrations.*, student_counselling.subject AS student_counselling_subject, student_counselling.course AS student_counselling_course FROM student_counselling inner join registrations on student_counselling.registration_id = registrations.id WHERE academic_receipt_no != '' and registrations.course = 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.' AND student_counselling.status='Accept'";

        $result3 = $this->db->query($query3);
        if($result3->getNumRows() > 0){
            // return $result->getResultArray();
            foreach($result3->getResult() as $value){
                if($value->student_counselling_subject != $value->ba_preference_1 && $value->student_counselling_subject != $value->bsc_preference_1)
                    $data[] = $value;
            }
        }
        
        if(count($data) > 0){
            return $data;
        }

        return [];
    }
}