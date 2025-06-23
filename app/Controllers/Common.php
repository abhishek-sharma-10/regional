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
            // $records = $ncetApplicationModel->getApplicantEmails();
    
            $email_array = ['abhishek.sharma@ibirdsservices.com'];
            // foreach ($records as $value) {
            //     var_dump($value);
            //     // $email_array[] = $value->email;
            // }
            // exit;
            $email = \Config\Services::email();
            $from = "rieajmer@no-reply.com";
            $fromName = "RIE Ajmer";

            $msg="Dear Candidate,<br/>";
            $msg .= "Online admission process for ITEP <b>B.Sc. B.Ed.</b> and <b>B.A. B.Ed.</b> courses at <b>Regional Institute of Education, NCERT, Ajmer</b> has started. ";
            $msg .= "Please apply on <a href='https://riea.in/'>Click Here</a> Link is live now till <b>04.07.2025</b>.<br/><br/>For details please visit <a href='https://rieajmer.raj.nic.in/'>Click Here</a>";

            $msg.="<br/><br/>Academic Section<br>RIE, NCERT, Ajmer<br/>";

            $subject = "Admission in ITEP Courses at RIE NCERT Ajmer";

            $email->setFrom($from,$fromName);
            $email->setTo($email_array);

            $email->setSubject($subject);
            $email->setMessage($msg);

            $mail = $email->send();

            if( $mail == true ) {
                return json_encode(['message' => 'Mail Sent Successfully.', 'success' => true]);
            }else {
                // print_r($email->printDebugger(['headers']));exit;
                return json_encode(['message' => 'Something went wrong', 'success' => false]);
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
