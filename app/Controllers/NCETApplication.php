<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

use App\Models\NCETApplicationModel;
use App\Models\CommonModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NCETApplication extends BaseController
{
    public function __construct()
    {
        session();
    }

    public function index()
    {
        try {
            $data 	= [];

            $ncetApplicationModel = new NCETApplicationModel();

            $data ["result"] = $ncetApplicationModel->fetchAll();

            $data['pageTitle'] = "NCET Applications";
            $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));
            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/ncet_application/ncet_application", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            return redirect()->to('/500');
            // return $this->getResponse(
            //     ['status' => 'ERROR', 'message' => $exception->getMessage()],
            //     ResponseInterface::HTTP_BAD_REQUEST
            // );
        }
    }

    public function import() {
        try{
            $ncetApplicationModel = new NCETApplicationModel();
            
            $path = 'uploads/';
            $json = [];
            $file_name = $this->request->getFile('file');
            $file_name = $this->uploadFile($path, $file_name);
            $arr_file = explode('.', $file_name);
            $extension = end($arr_file);
            if('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file_name);
            $sheet_data = $spreadsheet->getActiveSheet()->toArray();
            
            $list = [];
            foreach($sheet_data as $key => $val) {
                // var_dump($key);
                if($key != 0) {
                    // $result = $ncetApplicationModel->checkApplication(["ncet_application_no" => $val[0]]);
                    // if(!$result) {
                        $list [] = [
                            'ncet_application_no' => $val[0],
                            'name' => $val[1],
                            'mobile_no' => $val[2],
                            'father_name' => $val[3],
                            'mother_name' => $val[4],
                            'dob' => $val[5],
                            'gender' => $val[6],
                            'category_name' => $val[7],
                            'physical_disability' => $val[8],
                            'address' => $val[9],
                            'state' => $val[10],
                            'pincode' => $val[11],
                            'passing_year_10' => $val[12],
                            'board_10' => $val[13],
                            'board_other_10' => $val[14],
                            'total_marks_10' => $val[15],
                            'obtain_marks_10' => $val[16],
                            'percentage_10' => $val[17],
                            'passing_year_12' => $val[18],
                            'board_12' => $val[19],
                            'board_other_12' => $val[20],
                            'total_marks_12' => $val[21],
                            'obtain_marks_12' => $val[22],
                            'percentage_12' => $val[23],
                            'subject_code' => $val[24],
                            'subject_percentile' => $val[25],
                            'created_at' => date("Y-m-d H:i:s"),
                            'status' => "1",
                        ];
                    // }
                }
            }
            
            if(file_exists($file_name))
                unlink($file_name);
            if(count($list) > 0) {
                $result = $ncetApplicationModel->insertBatch($list);
                if($result) {
                    $json = [
                        'success_message' => "All Entries are imported successfully.",
                    ];
                } else {
                    $json = [
                        'error_message' => "Something went wrong. Please try again."
                    ];
                }
            } else {
                $json = [
                    'error_message' => "No new record is found.",
                ];
            }
            
            echo json_encode($json);
        }catch(Exception $e){
            echo json_encode($e->getMessage());
        }
    }

	public function uploadFile($path, $file) {
    	if (!is_dir($path)) 
			mkdir($path, 0777, TRUE);
		if ($file->isValid() && ! $file->hasMoved()) {
			$newName = $file->getRandomName();
			$file->move($path, $newName);
			return $path.$file->getName();
		}
		return "";
	}
}
