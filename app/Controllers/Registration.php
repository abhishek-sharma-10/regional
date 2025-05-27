<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

use App\Models\RegistrationModel;
use App\Models\NcetScoreModel;
use App\Models\CommonModel;

class Registration extends BaseController
{
    public function __construct()
    {
        session();
    }

    public function index()
    {
        try {
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['registrations'] = $registrationModel->getRegistrations();

            $data['pageTitle'] = "Registrations";
            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/registrations/registration_list", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            return redirect()->to('/500');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }

    public function getRegistrationDetail($id)
    {
        try {
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Registration - Details";
            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/registrations/registration_detail", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            return redirect()->to('/500');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }

    public function studentRegistration()
    {
        $registrationModel = new RegistrationModel();
        $request = $this->request->getVar();
        
        $process = isset($request['registrations-process']) ? $request['registrations-process'] : '';
        $data['pageTitle'] = "Registration";

        $data['email_container'] = true;
        $data['otp_container'] = false;
        $data['register_container'] = false;
        $data['msg'] = '';

        if($process == "send-email"){
            $email = $request['email'];

            $email_data = $registrationModel->getRegistrationByEmail($email);
            if(count($email_data) > 0){
                $data['email'] = $email;
                $data['msg'] = ['box'=> 'warning', 'msg' => 'This email is already registered.<br>Application No : <b>'.$email_data[0]->ncet_application_no.'</b>'];
                return view('student/template/header', $data) . view("student/registrations/registrations", $data) . view('student/template/footer');
            }

            $data['email'] = $email;
            // Generate 6-digit OTP
            $otp = random_int(100000, 999999); // secure random OTP
            //var_dump($otp);

            // $data['email_container'] = false;
            // $data['otp_container'] = true;
            
            $emailService = \Config\Services::email();
            
            $emailService->setTo($email);
            $emailService->setFrom('no-reply@riea.com');
            $emailService->setSubject('Verification Code (OTP) for registration portal of RIE, Ajmer');

            $message = "
                Dear Candidate,<br><br>
                ".$otp." is your verification code (OTP) for registration portal of RIE, Ajmer. Don't share your code with anyone.<br><br>
                Academic Section<br>
                RIE, NCERT, Ajmer";

            $emailService->setMessage($message);
        
            if ($emailService->send()) {
                $data['msg'] = ['box'=> 'success', 'msg' => 'OTP sent successfully to ' . $email];
                $data['email_container'] = false;
                $data['otp_container'] = true;
            } else {
                // Show email sending errors
                $data['email_container'] = true;
                $data['otp_container'] = false;
                $data['msg'] = ['box'=> 'danger', 'msg' => "Please enter valid email address."];
            }
        
            session()->set('otp', $otp);

            return view('student/template/header', $data) . view("student/registrations/registrations", $data) . view('student/template/footer');
        }else if($process == "verify-otp"){
            $userOtp = $this->request->getVar('otp');
            $email = $this->request->getVar('email');
            $sessionOtp = session()->get('otp');
            $data['email'] = $email;
            if ($userOtp == $sessionOtp) {
                $data['msg'] = ['box'=> 'success', 'msg' => 'OTP Verified Successfully!'];
                $data['otp_container'] = false;
                $data['email_container'] = false;
                $data['register_container'] = true;
            } else {
                $data['otp_container'] = true;
                $data['email_container'] = false;
                $data['msg'] = ['box'=> 'danger', 'msg' => 'Invalid OTP. Please enter correct OTP.'];
            }
            return view('student/template/header', $data) . view("student/registrations/registrations", $data) . view('student/template/footer');
        }else if($process == "registration") {
            unset($request["submit"]);
            unset($request["confirm_password"]);
            $request["status"] = "Request";

            $password = $request['password'];
            $request['password'] = password_hash($password, PASSWORD_DEFAULT);

            $output = $registrationModel->save($request);
            var_dump($output);

            if ($output) {
                // Send email after successful registration
                $emailService = \Config\Services::email();
                
                $toEmail = $request['email'];
                $username = $request['email'];
                $plainPassword = $password;
                
                $emailService->setTo($toEmail);
                $emailService->setFrom('no-reply@riea.com', 'Academic Section RIE Ajmer');
                $emailService->setSubject('Successfully registered to apply for admission in ITEP course at RIE, Ajmer');
                
                $message = "
                Dear Candidate,<br><br>
                You have successfully registered to apply for admission in ITEP Course at Regional Institute of Education, Ajmer. Please login to apply for the ITEP course using the following information.<br><br>
                <strong>Username:</strong> $username<br>
                <strong>Password:</strong> $plainPassword<br><br>
                Academic Section<br>
                RIE, NCERT, Ajmer";
                
                $emailService->setMessage($message);
                $emailService->setMailType('html'); // enable HTML
                //var_dump($emailService);exit;
        
                if ($emailService->send()) {
                    session()->setFlashdata('success', 'Registration successful! Email sent.');
                } else {
                    log_message('error', $emailService->printDebugger(['headers']));
                    session()->setFlashdata('error', 'Registration successful but email failed.');
                }
            }
            return redirect()->to('/');
        }else if(isset($request) && empty($request)){
            return view('student/template/header', $data) . view("student/registrations/registrations", $data) . view('student/template/footer');
        }
        return redirect()->to('/');
    }

    public function academicProfile()
    {
        try {
            $id = '';
            if(isset($_SESSION['role']) && $_SESSION['role'] == 'STUDENT' && isset($_SESSION['student'][0]->id) && !empty($_SESSION['student'][0]->id)){
                $id = $_SESSION['student'][0]->id;
            }else{
                return redirect()->to('/logout');
            }
            
            $registrationModel = new RegistrationModel();
            $ncetScoreModel = new NcetScoreModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);
            $data['ncet'] = $ncetScoreModel->getNcetScoreByRegistrationId($id);

            $years_for_10th = [];
            $years_for_12th = [];
            $currentYear = (int)date('Y');

            for ($i = 14; $i >= 0; $i--) {
                $years_for_10th[] = $currentYear - $i;
            }

            for ($i = 2; $i >= 0; $i--) {
                $years_for_12th[] = $currentYear - $i;
            }

            $data['years_for_10th'] = $years_for_10th;
            $data['years_for_12th'] = $years_for_12th;
            $data['sectionArray'] = ["Section 1" => 2, "Section 2"=> "3", "Section 3"=> "1", "Section 4"=> 1];

            $data['active'] = "academic";

            if ($data['details']->status == 'Complete') {
                return redirect()->to('print-academic-details');
            }
            $data['pageTitle'] = "Student - Academic";
            return view('student/template/header', $data) . view('student/registrations/academic', $data) . view('student/template/footer');
        } catch (Exception $exception) {
            return redirect()->to('/500');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }

    public function updateAcademicProfile()
    {
        try {
            $session = session();
            $input = $this->request->getVar();

            // var_dump($input);
            // var_dump($this->request->getFiles());

            $uploadPath = "public/uploads/" . $input['id'] . "/";

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath);
            }

            $validationRule = [];

            $photo = $this->request->getFile('photo');
            if (!empty($photo->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'photo' => [
                        'label' => 'Photo',
                        'rules' => [
                            'uploaded[photo]',
                            'mime_in[photo,image/pdfimage/jpg,image/jpeg,image/png,application/pdf]',
                            'max_size[photo, 200]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    // $session->set('store_form_values', $request_data); // keeping filled form field values 
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic');
                }
            }

            $signature = $this->request->getFile('signature');
            if (!empty($signature->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'signature' => [
                        'label' => 'Signature',
                        'rules' => [
                            'uploaded[signature]',
                            'mime_in[signature,image/pdfimage/jpg,image/jpeg,image/png,application/pdf]',
                            'max_size[signature, 200]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic');
                }
            }

            $certificate_10 = $this->request->getFile('certificate_10');
            if (!empty($certificate_10->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'certificate_10' => [
                        'label' => 'certificate_10',
                        'rules' => [
                            'uploaded[certificate_10]',
                            'mime_in[certificate_10,image/pdfimage/jpg,image/jpeg,image/png,application/pdf]',
                            'max_size[certificate_10, 1024]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic');
                }
            }

            $certificate_12 = $this->request->getFile('certificate_12');
            if (!empty($certificate_12->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'certificate_12' => [
                        'label' => 'certificate_12',
                        'rules' => [
                            'uploaded[certificate_12]',
                            'mime_in[certificate_12,image/pdfimage/jpg,image/jpeg,image/png,application/pdf]',
                            'max_size[certificate_12, 1024]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic');
                }
            }

            $ncet_score_card = $this->request->getFile('ncet_score_card');
            if (!empty($ncet_score_card->getName())) {
                $validationRule = [
                    'ncet_score_card' => [
                        'label' => 'ncet_score_card',
                        'rules' => [
                            'uploaded[ncet_score_card]',
                            'mime_in[ncet_score_card,image/pdfimage/jpg,image/jpeg,image/png,application/pdf]',
                            'max_size[ncet_score_card, 1024]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic');
                }
            }

            $caste_certificate = $this->request->getFile('caste_certificate');
            if (!empty($caste_certificate->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'caste_certificate' => [
                        'label' => 'caste_certificate',
                        'rules' => [
                            'uploaded[caste_certificate]',
                            'mime_in[caste_certificate,image/pdfimage/jpg,image/jpeg,image/png,application/pdf]',
                            'max_size[caste_certificate, 1024]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic');
                }
            }

            $pwbd = $this->request->getFile('pwbd');
            if (!empty($pwbd->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'pwbd' => [
                        'label' => 'pwbd',
                        'rules' => [
                            'uploaded[pwbd]',
                            'mime_in[pwbd,image/pdfimage/jpg,image/jpeg,image/png,application/pdf]',
                            'max_size[pwbd, 1024]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic');
                }
            }

            $ncet_application_form = $this->request->getFile('ncet_application_form');
            if (!empty($ncet_application_form->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'ncet_application_form' => [
                        'label' => 'ncet_application_form',
                        'rules' => [
                            'uploaded[ncet_application_form]',
                            'mime_in[ncet_application_form,image/pdfimage/jpg,image/jpeg,image/png,application/pdf]',
                            'max_size[ncet_application_form, 1024]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic');
                }
            }
            // var_dump($input);
            // ----------------------------------
            if (!empty($photo->getName())) {
                $type = $photo->getClientMimeType();
                $ext = "." . explode("/", $type)[1];

                $newName = "photo" . $ext;
                $this->unlinkFiles($uploadPath . 'photo');
                // if (file_exists($uploadPath . $newName)) {
                //     unlink($uploadPath . $newName);
                // }
                $photo->move($uploadPath, $newName);
                $input['photo'] = $uploadPath . $newName;
            } else {
                unset($input['photo']);
            }
            
            if (!empty($signature->getName())) {
                $type = $signature->getClientMimeType();
                $ext = "." . explode("/", $type)[1];

                $newName = "signature" . $ext;
                $this->unlinkFiles($uploadPath . 'signature');
                // if (file_exists($uploadPath . $newName)) {
                //     unlink($uploadPath . $newName);
                // }
                $signature->move($uploadPath, $newName);
                $input['signature'] = $uploadPath . $newName;
            } else {
                unset($input['signature']);
            }
            
            if (!empty($certificate_10->getName())) {
                $type = $certificate_10->getClientMimeType();
                $ext = "." . explode("/", $type)[1];

                $newName = "certificate_10" . $ext;
                $this->unlinkFiles($uploadPath . 'certificate_10');
                // if (file_exists($uploadPath . $newName)) {
                //     unlink($uploadPath . $newName);
                // }
                $certificate_10->move($uploadPath, $newName);
                $input['certificate_10'] = $uploadPath . $newName;
            } else {
                unset($input['certificate_10']);
            }
            
            if (!empty($certificate_12->getName())) {
                $type = $certificate_12->getClientMimeType();
                $ext = "." . explode("/", $type)[1];

                $newName = "certificate_12" . $ext;
                $this->unlinkFiles($uploadPath . 'certificate_12');
                // if (file_exists($uploadPath . $newName)) {
                //     unlink($uploadPath . $newName);
                // }
                $certificate_12->move($uploadPath, $newName);
                $input['certificate_12'] = $uploadPath . $newName;
            } else {
                unset($input['certificate_12']);
            }
            
            if (!empty($ncet_score_card->getName())) {
                $type = $ncet_score_card->getClientMimeType();
                $ext = "." . explode("/", $type)[1];

                $newName = "ncet_score_card" . $ext;
                $this->unlinkFiles($uploadPath . 'ncet_score_card');
                // if (file_exists($uploadPath . $newName)) {
                //     unlink($uploadPath . $newName);
                // }
                $ncet_score_card->move($uploadPath, $newName);
                $input['ncet_score_card'] = $uploadPath . $newName;
            } else {
                unset($input['ncet_score_card']);
            }
            
            if (!empty($caste_certificate->getName())) {
                $type = $caste_certificate->getClientMimeType();
                $ext = "." . explode("/", $type)[1];

                $newName = "caste_certificate" . $ext;
                $this->unlinkFiles($uploadPath . 'caste_certificate');
                // if (file_exists($uploadPath . $newName)) {
                //     unlink($uploadPath . $newName);
                // }
                $caste_certificate->move($uploadPath, $newName);
                $input['caste_certificate'] = $uploadPath . $newName;
            } else {
                unset($input['caste_certificate']);
            }
            
            if (!empty($pwbd->getName())) {
                $type = $pwbd->getClientMimeType();
                $ext = "." . explode("/", $type)[1];

                $newName = "pwbd" . $ext;
                $this->unlinkFiles($uploadPath . 'pwbd');
                // if (file_exists($uploadPath . $newName)) {
                //     unlink($uploadPath . $newName);
                // }
                $pwbd->move($uploadPath, $newName);
                $input['pwbd'] = $uploadPath . $newName;
            } else {
                unset($input['pwbd']);
            }

            if (!empty($ncet_application_form->getName())) {
                $type = $ncet_application_form->getClientMimeType();
                $ext = "." . explode("/", $type)[1];

                $newName = "ncet_application_form" . $ext;
                $this->unlinkFiles($uploadPath . 'ncet_application_form');
                // if (file_exists($uploadPath . $newName)) {
                //     unlink($uploadPath . $newName);
                // }
                $ncet_application_form->move($uploadPath, $newName);
                $input['ncet_application_form'] = $uploadPath . $newName;
            } else {
                unset($input['ncet_application_form']);
            }

            // var_dump($input);

            $ncet_score_data = [];

            for ($i = 0; $i < count($input['code']); $i++) {
                // var_dump($ncet_score_data);
                $ncet_score_data[$i] = array(
                    "registration_id" => $input['id'],
                    "codes"  => $input['code'][$i],
                    "subjects" => $input['subject'][$i],
                    "total_maximum_marks" => $input['max_marks'][$i],
                    "total_marks_obtain" => $input['obtain_marks'][$i],
                    "percentage" => $input['percentage'][$i]
                );

                if(isset($input['ids'][$i]) && !empty($input['ids'][$i])){
                    $ncet_score_data[$i]['id'] = $input['ids'][$i];
                }
                // var_dump($ncet_score_data);
            }

            // var_dump($input);

            if (isset($input['button_value']) && $input['button_value'] == 'Final Save') {
                unset($input['final_save']);
                unset($input['button_value']);
                $input['status'] = "Save - Payment Pending";
                $input['registration_date'] = date('Y-m-d h:i:s');
            }else if (isset($input['button_value']) && $input['button_value'] == 'Save as Draft'){
                unset($input['button_value']);
                $input['status'] = "Save as Draft";
            }

            unset($input['ids']);
            unset($input['code']);
            unset($input['subject']);
            unset($input['max_marks']);
            unset($input['obtain_marks']);
            unset($input['total_max_marks']);
            unset($input['total_obtain_marks']);
            unset($input['percentage']);

            // var_dump($input);
            // exit;
            $registrationModel = new RegistrationModel();
            $ncetScoreModel = new NcetScoreModel();
            // var_dump($input, $ncet_score_data);

            $registrationModel->upsert($input);

            if(count($ncet_score_data) > 0){
                $ncetScoreModel->upsertBatch($ncet_score_data);
            }

            if (isset($input['status']) && $input['status'] == 'Save - Payment Pending') {
                return redirect()->to('/pay-registration-fee');
            }else if (isset($input['status']) && $input['status'] == 'Save as Draft'){
                return redirect()->to('/academic');
            }
            

            // $data = [];


            // $data['details'] = $registrationModel->getRegistrationDetail($id);

            // $data['pageTitle'] = "Student - Academic";
            // return view('student/template/header',$data). view('student/registrations/academic', $data). view('student/template/footer');
        } catch (Exception $exception) {
            session()->setFlashdata('error', 'Something went wrong.\n'.$exception->getMessage());
            return redirect()->to('/academic');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }

    public function checkApplicationNo($id)
    {
        try {
            $registrationModel = new RegistrationModel();

            $result = $registrationModel->checkNCETApplication($id);
            return ($this->getResponse(['status' => 200, 'result' => $result]));
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => '500', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function fetchSubjects($code)
    {
        try {
            $commonModel = new CommonModel();
            $section = $this->request->getVar('section');
            $result = $commonModel->getSubjectByCode($code, $section);
            return ($this->getResponse(['status' => 200, 'result' => $result]));
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => '500', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function studentDashboard()
    {
        try {
            $id = '';
            if(isset($_SESSION['role']) && $_SESSION['role'] == 'STUDENT' && isset($_SESSION['student'][0]->id) && !empty($_SESSION['student'][0]->id)){
                $id = $_SESSION['student'][0]->id;
            }else{
                return redirect()->to('/logout');
            }
            
            $registrationModel = new RegistrationModel();

            $request = $this->request->getVar();
            $details = $registrationModel->getRegistrationDetail($id);
            $data = [];
            // $data['details'] = $registrationModel->getRegistrationDetail($id);
            
            // var_dump($request, $details);exit;
            if(empty($request) && ($details->acknowledged == 'false' || $details->acknowledged == null)){
                $data['details'] = $details;
                $data['pageTitle'] = "Student - Academic";
                $data['active'] = '';
                return view('student/template/header', $data) . view("student/registrations/dashboard", $data) . view('student/template/footer');
            }elseif(empty($request) && $details->acknowledged == 'true'){
                return redirect()->to('/academic');
            }elseif(!empty($request) && !empty($request['ackCheckbox'])){
                $request['acknowledged'] = $request['ackCheckbox'] == 'on' ? 'true' : 'false';
                unset($request['submit']);
                unset($request['ackCheckbox']);
                $registrationModel->upsert($request);
                
                return redirect()->to('/academic');
            }
        } catch (Exception $exception) {
            return redirect()->to('/logout');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }

    public function paymentInfo()
    {
        try {
            $id = '';
            if(isset($_SESSION['role']) && $_SESSION['role'] == 'STUDENT' && isset($_SESSION['student'][0]->id) && !empty($_SESSION['student'][0]->id)){
                $id = $_SESSION['student'][0]->id;
            }else{
                return redirect()->to('/logout');
            }
            
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Payment";
            $data['active'] = "pay-fees";
            return view('student/template/header', $data) . view('student/registrations/payment', $data) . view('student/template/footer');
        } catch (Exception $exception) {
            return redirect()->to('/500');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }
    public function printAcademicDetails()
    {
        try {
            $id = '';
            if(isset($_SESSION['role']) && $_SESSION['role'] == 'STUDENT' && isset($_SESSION['student'][0]->id) && !empty($_SESSION['student'][0]->id)){
                $id = $_SESSION['student'][0]->id;
            }else{
                return redirect()->to('/logout');
            }

            $registrationModel = new RegistrationModel();
            $ncetScoreModel = new NcetScoreModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);
            unset($data['details']->password);
            $data['ncet'] = $ncetScoreModel->getNcetScoreByRegistrationId($id);

            if(isset($data['details']) && ($data['details']->status === 'Save - Payment Pending' || $data['details']->status === 'Complete')){
                $bscPreferences = [$data['details']->bsc_preference_1, $data['details']->bsc_preference_2, $data['details']->bsc_preference_3, $data['details']->bsc_preference_4];
                $baPreferences = [$data['details']->ba_preference_1, $data['details']->ba_preference_2, $data['details']->ba_preference_3, $data['details']->ba_preference_4];
    
                $data['preferences'] = [
                    'B.Sc. B.Ed.' => array_filter($bscPreferences, fn($value) => !is_null($value) && $value !== ''), 
                    'B.A. B.Ed.' => array_filter($baPreferences, fn($value) => !is_null($value) && $value !== '')
                ];
    
                $data['pageTitle'] = "Print Academic Details";
                $data['active'] = "print-academic";
                $data['status'] = "filled";
                return view('student/template/header', $data) . view('student/registrations/print_academic_details', $data) . view('student/template/footer');
            }else{
                $data['pageTitle'] = "Print Academic Details";
                $data['active'] = "print-academic";
                $data['status'] = "not-filled";
                return view('student/template/header', $data) . view('student/registrations/print_academic_details', $data) . view('student/template/footer');
            }
        } catch (Exception $exception) {
            return redirect()->to('/500');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }

    public function payRegistrationFee()
    {
        try {
            $id = '';
            if(isset($_SESSION['role']) && $_SESSION['role'] == 'STUDENT' && isset($_SESSION['student'][0]->id) && !empty($_SESSION['student'][0]->id)){
                $id = $_SESSION['student'][0]->id;
            }else{
                return redirect()->to('/logout');
            }
            
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            if ($data['details']->status === 'Complete') {
                return redirect()->to('/payment');
            }

            $data['pageTitle'] = "Pay - Registration - Fee";
            $data['active'] = "pay-fees";
            return  view('student/template/header', $data) . view('student/registrations/pay_registration_fee', $data) . view('student/template/footer');
        } catch (Exception $exception) {
            return redirect()->to('/500');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }

    public function paymentRegistrationFee()
    {
        try {
            $session = session();
            $input = $this->request->getVar();

            // var_dump($this->request);
            // var_dump($this->request->getFiles());

            $uploadPath = "public/uploads/" . $input['id'] . "/";

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath);
            }

            $validationRule = [];

            $payment_receipt = $this->request->getFile('payment_receipt');
            if (!empty($payment_receipt->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'payment_receipt' => [
                        'label' => 'Payment Receipt',
                        'rules' => [
                            'uploaded[payment_receipt]',
                            'mime_in[payment_receipt,image/pdfimage/jpg,image/jpeg,image/png,application/pdf]',
                            'max_size[payment_receipt, 1024]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    // $session->set('store_form_values', $request_data); // keeping filled form field values 
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/pay-registration-fee');
                }

                $type = $payment_receipt->getClientMimeType();
                $ext = "." . explode("/", $type)[1];

                $newName = "payment_receipt" . $ext;
                $this->unlinkFiles($uploadPath . 'payment_receipt');
                // if (file_exists($uploadPath . $newName)) {
                //     unlink($uploadPath . $newName);
                // }
                $payment_receipt->move($uploadPath, $newName);
                $input['payment_receipt'] = $uploadPath . $newName;
            } else {
                unset($input['payment_receipt']);
            }

            $input['payment_date'] = date('Y-m-d h:i:s');
            $input['status'] = "Complete";

            var_dump($input);

            $registrationModel = new RegistrationModel();

            $result = $registrationModel->upsert($input);

            if ($result) {

                $studentDetail = $registrationModel->getRegistrationDetail($input['id']);

                $emailService = \Config\Services::email();
                
                $toEmail = $studentDetail->email;
                
                $emailService->setTo($toEmail);
                $emailService->setFrom('no-reply@riea.com', 'Academic Section RIE Ajmer');
                $emailService->setSubject('Successfully submitted your application for admission in ITEP course at RIE, Ajmer');
                
                $message = "
                Dear Candidate,<br><br>
                You have successfully submitted your application for admission in ITEP Course at Regional Institute of Education, Ajmer.<br><br>
                Academic Section<br>
                RIE, NCERT, Ajmer";
                
                $emailService->setMessage($message);
        
                if ($emailService->send()) {
                    session()->setFlashdata('success', 'Application submitted successful! Email sent.');
                } else {
                    // log_message('error', $emailService->printDebugger(['headers']));
                    session()->setFlashdata('error', 'Application submitted successful but email failed.');
                }
            }

            return redirect()->to('/payment');
        } catch (Exception $exception) {
            session()->setFlashdata('error', 'Something went wrong.\n'.$exception->getMessage());
            return redirect()->to('/pay-registration-fee');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }

    function unlinkFiles($filename)
    {
        $matches = glob($filename . '.*'); // find any file with any extension

        foreach ($matches as $file) {
            if (is_file($file)) {
                unlink($file);
                // echo "Deleted: " . $file . "<br>";
            }
        }
    }

    public function getInstruction(){
        $data['pageTitle'] = "Registrations";
        $data['active'] = '';
        $data['details'] = (object)['id' => 7];
        return view('student/template/header', $data) . view('student/registrations/instruction', $data) . view('student/template/footer');
    }
}
