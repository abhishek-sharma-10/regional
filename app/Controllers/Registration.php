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
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
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
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function studentRegistration()
    {
        $registrationModel = new RegistrationModel();
        $request = $this->request->getVar();

        $data['pageTitle'] = "Registration";
        if (isset($request) && empty($request)) {
            return view('student/template/header', $data) . view("student/registrations/registrations", $data) . view('student/template/footer');
        } else {
            unset($request["submit"]);
            unset($request["confirm_password"]);

            $password = $request['password'];
            $request['password'] = password_hash($password, PASSWORD_DEFAULT);

            $output = $registrationModel->save($request);
            var_dump($output);
            return redirect()->to('/');
        }

        //return view('Student/registrations/registrations');
    }

    public function academicProfile($id)
    {
        try {
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Student - Academic";
            return view('student/template/header', $data) . view('student/registrations/academic', $data) . view('student/template/footer');
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function updateAcademicProfile()
    {
        try {
            $input = $this->request->getVar();

            // var_dump($input);
            var_dump($this->request->getFiles());
            
            $uploadPath = "uploads/".$input['id']."/";
            
            if(!is_dir($uploadPath)){
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
                            'mime_in[photo,image/pdfimage/jpg,image/jpeg,image/png]',
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    // $session->set('store_form_values', $request_data); // keeping filled form field values 
                    // $session->setFlashdata('err_msg', parent::$INVALID_FILE_UPLOAD_MESSAGE);
                    // return redirect()->to('/membership/add_package');
                }
            }
            if (!empty($photo->getName())) {
                $newName = $photo->getRandomName();
                echo $newName;
                $photo->move($uploadPath, $newName);
                $input['photo'] = $uploadPath . $newName;
            }

            $signature = $this->request->getFile('signature');
            if (!empty($signature->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'signature' => [
                        'label' => 'Signature',
                        'rules' => [
                            'uploaded[signature]',
                            'mime_in[signature,image/pdfimage/jpg,image/jpeg,image/png]',
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                }
            }


            if (!empty($signature->getName())) {
                $newName = $signature->getRandomName();
                $signature->move($uploadPath, $newName);
                $input['signature'] = $uploadPath . $newName;
            }

            $certificate_10 = $this->request->getFile('certificate_10');
            if (!empty($certificate_10->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'certificate_10' => [
                        'label' => 'certificate_10',
                        'rules' => [
                            'uploaded[certificate_10]',
                            'mime_in[certificate_10,image/pdfimage/jpg,image/jpeg,image/png]',
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                }
            }


            if (!empty($certificate_10->getName())) {
                $newName = $certificate_10->getRandomName();
                $certificate_10->move($uploadPath, $newName);
                $input['certificate_10'] = $uploadPath . $newName;
            }

            $certificate_12 = $this->request->getFile('certificate_12');
            if (!empty($certificate_12->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'certificate_12' => [
                        'label' => 'certificate_12',
                        'rules' => [
                            'uploaded[certificate_12]',
                            'mime_in[certificate_12,image/pdfimage/jpg,image/jpeg,image/png]',
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                }
            }

            if (!empty($certificate_12->getName())) {
                $newName = $certificate_12->getRandomName();
                $certificate_12->move($uploadPath, $newName);
                $input['certificate_12'] = $uploadPath . $newName;
            }

            $ncet_score_card = $this->request->getFile('ncet_score_card');
            if (!empty($ncet_score_card->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'ncet_score_card' => [
                        'label' => 'ncet_score_card',
                        'rules' => [
                            'uploaded[ncet_score_card]',
                            'mime_in[ncet_score_card,image/pdfimage/jpg,image/jpeg,image/png]',
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                }
            }

            if (!empty($ncet_score_card->getName())) {
                $newName = $ncet_score_card->getRandomName();
                $ncet_score_card->move($uploadPath, $newName);
                $input['ncet_score_card'] = $uploadPath . $newName;
            }

            $caste_certificate = $this->request->getFile('caste_certificate');
            if (!empty($caste_certificate->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'caste_certificate' => [
                        'label' => 'caste_certificate',
                        'rules' => [
                            'uploaded[caste_certificate]',
                            'mime_in[caste_certificate,image/pdfimage/jpg,image/jpeg,image/png]',
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                }
            }

            if (!empty($caste_certificate->getName())) {
                $newName = $caste_certificate->getRandomName();
                $caste_certificate->move($uploadPath, $newName);
                $input['caste_certificate'] = $uploadPath . $newName;
            }

            $pwbd = $this->request->getFile('pwbd');
            if (!empty($pwbd->getName())) {
                // $videoFileExist = true;
                $validationRule = [
                    'pwbd' => [
                        'label' => 'pwbd',
                        'rules' => [
                            'uploaded[pwbd]',
                            'mime_in[pwbd,image/pdfimage/jpg,image/jpeg,image/png]',
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                }
            }

            if (!empty($pwbd->getName())) {
                $newName = $pwbd->getRandomName();
                $pwbd->move($uploadPath, $newName);
                $input['pwbd'] = $uploadPath . $newName;
            }
            var_dump($input);

            $ncet_score_data = [];

            for($i=0;$i<count($input['code']); $i++){
                $ncet_score_data[$i] = array(
                    "registration_id" => $input['id'],
                    "codes"  => $input['code'][$i],
                    "subjects" => $input['subject'][$i],
                    "total_maximum_marks" => $input['max_marks'][$i],
                    "total_marks_obtain" => $input['obtain_marks'][$i]
                );
            }

            $registrationModel = new RegistrationModel();
            $ncetScoreModel = new NcetScoreModel();
            var_dump($ncet_score_data);
            $registrationModel->save($input);
            $ncetScoreModel->insertBatch($ncet_score_data);
            exit;
            $data = [];
            // $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Student - Academic";
            // return view('student/template/header',$data). view('student/registrations/academic', $data). view('student/template/footer');
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
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
                ['status' => '400', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function fetchSubjects($code)
    {
        try {
            $commonModel = new CommonModel();

            $result = $commonModel->getSubjectByCode($code);
            return ($this->getResponse(['status' => 200, 'result' => $result]));
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => '400', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function studentDashboard($id){

        try{
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Student - Dashboard";
            return view('student/template/header',$data). view("student/registrations/dashboard", $data) . view('student/template/footer');
        }catch(Exception $exception){
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                 ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function paymentInfo($id){
        try{
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Payment";
            return view('student/registrations/payment', $data);
        }catch(Exception $exception){
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                 ResponseInterface::HTTP_BAD_REQUEST
            );
        }
        // $data['pageTitle'] = "Payment";
        //return view('student/template/header',$data). view("student/registrations/academic", $data) . view('student/template/footer');
        //return view('Student/registrations/payment');
    }
    public function printAcademicDetails($id){
        try{
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Print Academic Details";
            return view('student/registrations/print_academic_details', $data);
        }catch(Exception $exception){
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                 ResponseInterface::HTTP_BAD_REQUEST
            );
        }
        // $data['pageTitle'] = "Print - Academic - Details";
        //return view('student/template/header',$data). view("student/registrations/academic", $data) . view('student/template/footer');
        //return view('Student/registrations/print_academic_details'); 
    }

    public function payRegistrationFee($id){
        try{
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Pay - Registration - Fee";
            return view('student/registrations/pay_registration_fee', $data);
        }catch(Exception $exception){
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                 ResponseInterface::HTTP_BAD_REQUEST
            );
        }
        // $data['pageTitle'] = "Pay - Registration - Fee";
        //return view('student/template/header',$data). view("student/registrations/academic", $data) . view('student/template/footer');
        //return view('Student/registrations/pay_registration_fee'); 
    }
}