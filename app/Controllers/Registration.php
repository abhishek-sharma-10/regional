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
            $request["status"] = "Request";

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
            $ncetScoreModel = new NcetScoreModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);
            $data['ncet'] = $ncetScoreModel->getNcetScoreByRegistrationId($id);

            if ($data['details']->status == 'Complete' || $data['details']->status == 'Save - Payment Pending') {
                return redirect()->to('print-academic-details/' . $data['details']->id);
            }
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
                            'mime_in[photo,image/pdfimage/jpg,image/jpeg,image/png]',
                            'max_size[photo, 3072]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    // $session->set('store_form_values', $request_data); // keeping filled form field values 
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic/' . $input['id']);
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
                            'mime_in[signature,image/pdfimage/jpg,image/jpeg,image/png]',
                            'max_size[signature, 3072]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic/' . $input['id']);
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
                            'mime_in[certificate_10,image/pdfimage/jpg,image/jpeg,image/png]',
                            'max_size[certificate_10, 3072]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic/' . $input['id']);
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
                            'mime_in[certificate_12,image/pdfimage/jpg,image/jpeg,image/png]',
                            'max_size[certificate_12, 3072]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic/' . $input['id']);
                }
            }

            $ncet_score_card = $this->request->getFile('ncet_score_card');
            if (!empty($ncet_score_card->getName())) {
                $validationRule = [
                    'ncet_score_card' => [
                        'label' => 'ncet_score_card',
                        'rules' => [
                            'uploaded[ncet_score_card]',
                            'mime_in[ncet_score_card,image/pdfimage/jpg,image/jpeg,image/png]',
                            'max_size[ncet_score_card, 3072]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic/' . $input['id']);
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
                            'mime_in[caste_certificate,image/pdfimage/jpg,image/jpeg,image/png]',
                            'max_size[caste_certificate, 3072]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic/' . $input['id']);
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
                            'mime_in[pwbd,image/pdfimage/jpg,image/jpeg,image/png]',
                            'max_size[pwbd, 3072]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/academic/' . $input['id']);
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

            // var_dump($input);

            $ncet_score_data = [];

            for ($i = 0; $i < count($input['code']); $i++) {
                if(isset($input['ids'][$i]) && !empty($input['ids'][$i])){
                    $ncet_score_data[$i] = array(
                        "id" => $input['ids'][$i],
                        "registration_id" => $input['id'],
                        "codes"  => $input['code'][$i],
                        "subjects" => $input['subject'][$i],
                        "total_maximum_marks" => $input['max_marks'][$i],
                        "total_marks_obtain" => $input['obtain_marks'][$i]
                    );
                }
            }

            // var_dump($input);

            if (isset($input['save_as_draft'])) {
                unset($input['save_as_draft']);
                $input['status'] = "Save as Draft";
            } elseif (isset($input['final_save'])) {
                unset($input['final_save']);
                $input['status'] = "Save - Payment Pending";
            }

            unset($input['ids']);
            unset($input['code']);
            unset($input['subject']);
            unset($input['max_marks']);
            unset($input['obtain_marks']);
            unset($input['total_max_marks']);
            unset($input['total_obtain_marks']);

            // var_dump($input);
            // exit;
            $registrationModel = new RegistrationModel();
            $ncetScoreModel = new NcetScoreModel();
            // var_dump($input, $ncet_score_data);

            $registrationModel->upsert($input);

            if(count($ncet_score_data) > 0){
                $ncetScoreModel->upsertBatch($ncet_score_data);
            }

            return redirect()->to('/dashboard/' . $input['id']);

            // $data = [];


            // $data['details'] = $registrationModel->getRegistrationDetail($id);

            // $data['pageTitle'] = "Student - Academic";
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

    public function studentDashboard($id)
    {

        try {
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Student - Dashboard";
            return view('student/template/header', $data) . view("student/registrations/dashboard", $data) . view('student/template/footer');
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function paymentInfo($id)
    {
        try {
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            $data['pageTitle'] = "Payment";
            return view('student/template/header', $data) . view('student/registrations/payment', $data) . view('student/template/footer');
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }
    public function printAcademicDetails($id)
    {
        try {
            $registrationModel = new RegistrationModel();
            $ncetScoreModel = new NcetScoreModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);
            unset($data['details']->password);
            $data['ncet'] = $ncetScoreModel->getNcetScoreByRegistrationId($id);

            $data['pageTitle'] = "Print Academic Details";
            return view('student/template/header', $data) . view('student/registrations/print_academic_details', $data) . view('student/template/footer');
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function payRegistrationFee($id)
    {
        try {
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['details'] = $registrationModel->getRegistrationDetail($id);

            if ($data['details']->status === 'Complete') {
                return redirect()->to('/payment/' . $id);
            }

            $data['pageTitle'] = "Pay - Registration - Fee";
            return  view('student/template/header', $data) . view('student/registrations/pay_registration_fee', $data) . view('student/template/footer');
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
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
                            'mime_in[payment_receipt,image/pdfimage/jpg,image/jpeg,image/png]',
                            'max_size[payment_receipt, 3072]'
                        ],
                    ],
                ];

                if (!$this->validate($validationRule)) {
                    var_dump($this->validator->getErrors());
                    // $session->set('store_form_values', $request_data); // keeping filled form field values 
                    $session->setFlashdata('err_msg', 'Invalid file format/ Max. File Size upload attempted.');
                    return redirect()->to('/pay-registration-fee/' . $input['id']);
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

            var_dump($input);

            $input['status'] = "Complete";
            var_dump($input);

            $registrationModel = new RegistrationModel();

            $registrationModel->upsert($input);

            return redirect()->to('/dashboard/' . $input['id']);
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
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
}
