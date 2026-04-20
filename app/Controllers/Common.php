<?php

namespace App\Controllers;

use App\Models\RegistrationModel;
use App\Models\NCETApplicationModel;
use App\Models\CommonModel;
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
    
            $email_array = [];
            
            
            foreach ($records as $value) {
                var_dump($value);
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

                $email->setTo($value->email);
                $mail = $email->send();
    
                if( $mail == true ) {
                    $ncetApplicationModel->set('notification_status', 'SENT')->where("ncet_application_no", $value->ncet_application_no)->update();
                    echo json_encode(['message' => 'Mail Sent Successfully.', 'success' => true]);
                }else {
                    print_r($email->printDebugger(['headers']));//exit;
                    echo json_encode(['message' => 'Something went wrong', 'success' => false]);
                }
            }
            // exit;
        }catch(Exception $e){
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $e->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }
    
    public function sendSpotCounsellingMail(){
        try{
            $commonModel = new CommonModel();
            $registrationModel = new RegistrationModel();
            $records = $commonModel->getSpotCounsellingList();
    
            // var_dump($records);exit;
            foreach ($records as $value) {
                // var_dump($value);
                $email = \Config\Services::email();
                $from = "no-reply@riea.com";
                $fromName = "RIE Ajmer";
    
                $msg="Dear Candidate,<br/>";
                $msg .= "Please find the attached information about Spot Counselling for admission in ITEP Courses on <b>25.08.2025.</b>";
                
                $msg.="<br/><br/>Academic Section<br>RIE, NCERT, Ajmer<br/>";
    
                $subject = "Information about Spot Counselling for admission in ITEP Courses.";
    
                $email->setFrom($from,$fromName);
    
                $email->setSubject($subject);
                $email->setMessage($msg);
                $email->attach('https://riea.in/public/Spot_Counselling_Notice_2025_26.pdf');
                $email->setTo($value->email);
                // $email->setBCC('abhishek.sharma@ibirdsservices.com');
                $mail = $email->send();

                if( $mail == true ) {
                    $registrationModel->set('spot_counselling_mail', 'SENT')->where("id", $value->id)->update();
                    echo json_encode(['message' => 'Mail Sent Successfully.', 'success' => true]);
                }else {
                    print_r($email->printDebugger(['headers']));//exit;
                    echo json_encode(['message' => 'Something went wrong', 'success' => false]);
                }
            }
            // exit;
        }catch(Exception $e){
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $e->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }
    
    public function sendSingleMail(){
        try{
            
                $email = \Config\Services::email();
                $from = "no-reply@riea.com";
                $fromName = "RIE Ajmer";
    
                $msg="Dear Candidate,<br/>";
                $msg .= "We are pleased to inform you that you have been upgraded to a higher preference in the counselling process based on your merit and the availability of seats.<br><br>
                        Updated Allotment Details:
                          <ul>
                            <li>Name: Aparna Priyadarshini Sahu</li>
                            <li>NCET Application No.: 255110024645</li>
                            <li>Previous Allotment: Physics</li>
                            <li>New Allotment: Zoology</li>
                          </ul>
                        Please note that this allotment has been done as per your submitted preferences and available seat matrix.";
                
                $msg.="<br/><br/><b>Academic Section<br>RIE, Ajmer</b><br/>";
    
                $subject = "Upward Movement in Counselling – New Course Allotment";
    
                $email->setFrom($from,$fromName);
    
                $email->setSubject($subject);
                $email->setMessage($msg);
                
                // $email->setTo('aparnapriyadarshinisahu@gmail.com');
                // $email->setBCC('abhishek.sharma@ibirdsservices.com');
                $mail = $email->send();
    
                if( $mail == true ) {
                    echo json_encode(['message' => 'Mail Sent Successfully.', 'success' => true]);
                }else {
                    print_r($email->printDebugger(['headers']));//exit;
                    echo json_encode(['message' => 'Something went wrong', 'success' => false]);
                }

            // exit;
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
