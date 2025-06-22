<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

use App\Models\CounsellingModel;
use App\Models\RegistrationModel;
use App\Models\CommonModel;

class Counselling extends BaseController
{
    public function __construct()
    {
        session();
    }

    public function add()
    {
        try {
            $data['pageTitle'] = "Counselling";
            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/counselling/add_counselling", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            // return redirect()->to('/500');
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function show()
    {
        try {
            $data = [];

            $counsellingModel = new CounsellingModel();

            $data["counselling"] = $counsellingModel->fetchAll();

            $data['pageTitle'] = "Counselling";
            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/counselling/show_counselling", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            // return redirect()->to('/500');
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function insert(){
        try {
            $data = [];
            $counsellingModel = new CounsellingModel();

            $input = $this->request->getVar();
            // var_dump($input);

            if(isset($input) && !empty($input) && !empty($input['start_date']) && !empty($input['end_date'])) {
                $data['start_date'] = $input['start_date'];
                $data['end_date'] = $input['end_date'];

                $result = $counsellingModel->insert($input);
                if($result) {
                    $data['success_message'] = "Couselling session created successfully";
                }else{
                    $data['error_message'] = "Error creating couselling session";
                }
            }

            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function counsellingWiseStudentList(){
        try {
            
            $counsellingModel = new CounsellingModel();

            $records = $counsellingModel->getCounsellingStudentList();

            echo json_encode($records);
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function sendEmailToCounsellingStudents($id)
    {
        try {
            $email_array = [];
            $counsel_student = [];
            
            $input = $this->request->getVar();

            foreach ($input as $value) {
                $email_array[] = $value->email;
                $counsel_student[] = [$id, $value->id];
            }

            $email = \Config\Services::email();
            $from = "rieajmer@no-reply.com";
            $fromName = "RIE Ajmer";

            $msg="<i><b>Hi</b>,<br/><br/>";

            $msg.="This is a reminder for your couselling session scheduled on 18/06/2025.";

            $msg.="<br/><br/><b>Thanks,</b> <br/>";
            $msg.="<b>RIE Ajmer</b></i>";

            $subject = "RIE : Counselling Mail";

            $email->setFrom($from,$fromName);
            $email->setTo($email_array);

            $email->setSubject($subject);
            $email->setMessage($msg);

            $mail = $email->send();

            if( $mail == true ) {
                echo json_encode(['message' => 'Counselling Mail Sent Successfully.', 'success' => true]);
            }else {
                // print_r($email->printDebugger(['headers']));exit;
                echo json_encode(['message' => 'Something went wrong', 'success' => false]);
            }

        } catch(Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    // public function import() {
    //     try{
    //         $ncetApplicationModel = new NCETApplicationModel();
            
    //         $path = 'uploads/';
    //         $json = [];
    //         $file_name = $this->request->getFile('file');
    //         $file_name = $this->uploadFile($path, $file_name);
    //         $arr_file = explode('.', $file_name);
    //         $extension = end($arr_file);
    //         if('csv' == $extension) {
    //             $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    //         } else {
    //             $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //         }
    //         $spreadsheet = $reader->load($file_name);
    //         $sheet_data = $spreadsheet->getActiveSheet()->toArray();
            
    //         $list = [];
    //         foreach($sheet_data as $key => $val) {
    //             if($key != 0) {
    //                 $result = $ncetApplicationModel->getUser(["ncet_application_no" => $val[0]]);
    //                 if(!$result) {
    //                     $list [] = [
    //                         'ncet_application_no' => $val[0],
    //                         'name' => $val[1],
    //                         'mobile' => $val[2],
    //                         'email'	=> $val[3],
    //                         'city' => $val[4],
    //                         'created_at' => date("Y-m-d H:i:s"),
    //                         'status' => "1",
    //                     ];
    //                 }
    //             }
    //         }
            
    //         if(file_exists($file_name))
    //             unlink($file_name);
    //         if(count($list) > 0) {
    //             $result = $ncetApplicationModel->insertBatch($list);
    //             if($result) {
    //                 $json = [
    //                     'success_message' => "All Entries are imported successfully.",
    //                 ];
    //             } else {
    //                 $json = [
    //                     'error_message' => "Something went wrong. Please try again."
    //                 ];
    //             }
    //         } else {
    //             $json = [
    //                 'error_message' => "No new record is found.",
    //             ];
    //         }
            
    //         echo json_encode($json);
    //     }catch(Exception $e){
    //         echo json_encode($e->getMessage());
    //     }
    // }

	// public function uploadFile($path, $file) {
    // 	if (!is_dir($path)) 
	// 		mkdir($path, 0777, TRUE);
	// 	if ($file->isValid() && ! $file->hasMoved()) {
	// 		$newName = $file->getRandomName();
	// 		$file->move($path, $newName);
	// 		return $path.$file->getName();
	// 	}
	// 	return "";
	// }
}
