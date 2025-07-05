<?php

namespace App\Controllers;

use App\Models\RegistrationModel;
use App\Models\NCETApplicationModel;
use Exception;
use CodeIgniter\HTTP\ResponseInterface;

class Common extends BaseController
{
    public function __construct()
    {
        session();
    }

    public function getInstruction(){
        $data['pageTitle'] = "Registrations";
        $data['active'] = '';
        $registrationModel = new RegistrationModel();
        if(isset($_SESSION['student'])){
            $details = $registrationModel->getRegistrationDetail($_SESSION['student'][0]->id);
            $data['details'] = $details;
        }
        return view('student/template/header', $data) . view('student/registrations/instruction', $data) . view('student/template/footer');
    }

    public function contactUs(){
        $data['pageTitle'] = "Contact Us";
        $data['active'] = '';

        $registrationModel = new RegistrationModel();
        if(isset($_SESSION['student'])){
            $details = $registrationModel->getRegistrationDetail($_SESSION['student'][0]->id);
            $data['details'] = $details;
        }

        return view('student/template/header', $data) . view('student/contact_us', $data) . view('student/template/footer');
    }

    public function sendRegistrationOpenMail(){
        try{
            $ncetApplicationModel = new NCETApplicationModel();
            $records = $ncetApplicationModel->getApplicantEmails();
    
            $email = \Config\Services::email();
            $from = "no-reply@riea.com";
            $fromName = "RIE Ajmer";

            $msg="Dear Candidate,<br/>";
            $msg .= "Online admission process for ITEP <b>B.Sc. B.Ed.</b> and <b>B.A. B.Ed.</b> Courses at the <b>Regional Institute of Education, NCERT, Ajmer</b> has started. ";
            $msg .= "Please apply on the link https://riea.in/registrations (<a href='https://riea.in/registrations'>Click Here</a>).<br/>The link is open from <b>25-JUNE-2025</b> to <b>04-JULY-2025</b>.<br/><br/>For more details, please visit https://rieajmer.raj.nic.in (<a href='https://rieajmer.raj.nic.in/'>Click Here</a>)";

            $msg.="<br/><br/>Academic Section<br>RIE, NCERT, Ajmer<br/>";

            $subject = "Admission in ITEP Courses at RIE NCERT Ajmer";

            $email->setFrom($from,$fromName);
            $email->setSubject($subject);
            $email->setMessage($msg);

            foreach ($records as $value) {
                // var_dump($value);
                $email->setTo($value->email);
                $mail = $email->send();

                if( $mail == true ) {
                    $ncetApplicationModel->set('notification_status', 'NOT SENT')->where("ncet_application_no", $value->ncet_application_no)->update();
                    echo json_encode(['message' => 'Mail Sent Successfully.', 'success' => true]);
                }else {
                    // print_r($email->printDebugger(['headers']));exit;
                    echo json_encode(['message' => 'Something went wrong', 'success' => false]);
                }
            }

        }catch(Exception $e){
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $e->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }
    
    public function internalServer(){
        $data['pageTitle'] = "500 - Error";
        return view('500_error', $data);
    }
}
