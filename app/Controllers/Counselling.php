<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

use App\Models\CounsellingModel;
use App\Models\RegistrationModel;
use App\Models\CommonModel;
use App\Models\ITEPSeatMatrixModel;
use App\Models\NcetScoreModel;
use App\Models\StudentCounsellingModel;

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
            $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));

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
            $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));

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
            $itepMatrixModel = new ITEPSeatMatrixModel();
            $counsellingModel = new CounsellingModel();
            $studentCounsellingModel = new StudentCounsellingModel();

            $input = $this->request->getVar();
            // var_dump($input);

            if(isset($input) && !empty($input) && !empty($input['start_date']) && !empty($input['end_date'])) {
                $data['start_date'] = $input['start_date'];
                $data['end_date'] = $input['end_date'];

                $result = $counsellingModel->insert($input);
                $counsellingId = $counsellingModel->getInsertID();
                if($result) {
                    $matrixResult = $itepMatrixModel->fetchAll();

                    // var_dump($matrixResult);exit;

                    $counselling = $counsellingModel->getCounsellingStudentList(" AND physical_disable='No' ORDER BY ncet_average_percentile DESC");

                    $matrix = $matrixResult[0];
                    $total_bsc_physical = $matrixResult[1];
                    $total_ba_physical = $matrixResult[2];

                    // $matrix = [
                    //     'physics' => ['general' => 9, 'obc-(ncl)' => 5, "sc" => 3, "st" => 1, "ews" => 2],
                    //     'chemistry' => ['general' => 8, 'obc-(ncl)' => 5, "sc" => 3, "st" => 2, "ews" => 2],
                    //     'mathematics' => ['general' => 8, 'obc-(ncl)' => 5, "sc" => 3, "st" => 2, "ews" => 2],
                    //     'botany' => ['general' => 8, 'obc-(ncl)' => 6, "sc" => 3, "st" => 1, "ews" => 2],
                    //     'zoology' => ['general' => 8, 'obc-(ncl)' => 6, "sc" => 3, "st" => 1, "ews" => 2],

                    //     'history' => ['general' => 5, 'obc-(ncl)' => 3, "sc" => 2, "st" => 1, "ews" => 1],
                    //     'geography' => ['general' => 6, 'obc-(ncl)' => 3, "sc" => 2, "st" => 1, "ews" => 1],
                    //     'english language and literature' => ['general' => 4, 'obc-(ncl)' => 3, "sc" => 1, "st" => 1, "ews" => 1],
                    //     'hindi language and literature' => ['general' => 4, 'obc-(ncl)' => 3, "sc" => 1, "st" => 1, "ews" => 1],
                    //     'urdu' => ['general' => 2, 'obc-(ncl)' => 1, "sc" => 1, "st" => 0, "ews" => 1],
                    // ];

                    $selected_student = [
                        'physics' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
                        'chemistry' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
                        'mathematics' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
                        'botany' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
                        'zoology' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],

                        'history' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
                        'geography' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
                        'english language and literature' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
                        'hindi language and literature' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
                        'urdu' => ['general' => 0, 'obc-(ncl)' => 0, "sc" => 0, "st" => 0, "ews" => 0],
                    ];

                    $selected_matrix = [
                        'physics' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],
                        'chemistry' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],
                        'mathematics' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],
                        'botany' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],
                        'zoology' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],

                        'history' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],
                        'geography' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],
                        'english language and literature' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],
                        'hindi language and literature' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],
                        'urdu' => ['general' => [], 'obc-(ncl)' => [], "sc" => [], "st" => [], "ews" => []],
                    ];

                    $selected_student_id = [];

                    // var_dump($matrix['physics']['general']);

                    $bsc_subject_array = ['Physics', 'Chemistry', 'Mathematics', 'Botany', 'Zoology'];

                    foreach ($counselling as $key => $value) {
                        // var_dump($count++);
                        // if(in_array($value->bsc_preference_1, $bsc_subject_array)){
                        if(!in_array($value->id, array_column($selected_student_id, 'registration_id')))
                            if(!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']){
                                // $selected_student[] = [$value->id, $value->bsc_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_1)]['general'][] = [$value->id, $value->bsc_preference_1, $value->category];
                                $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->bsc_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']){
                                // $selected_student[] = [$value->id, $value->bsc_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_2)]['general'][] = [$value->id, $value->bsc_preference_2, $value->category];
                                $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->bsc_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_2, $value->category];
                                $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']){
                                // $selected_student[] = [$value->id, $value->bsc_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_3)]['general'][] = [$value->id, $value->bsc_preference_3, $value->category];
                                $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->bsc_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_3, $value->category];
                                $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']){
                                // $selected_student[] = [$value->id, $value->bsc_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_4)]['general'][] = [$value->id, $value->bsc_preference_4, $value->category];
                                $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->bsc_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_4, $value->category];
                                $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                            }

                            // BA STUDENT
                            else if(!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']){
                                // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_1)]['general'][] = [$value->id, $value->ba_preference_1, $value->category];
                                $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']){
                                // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_2)]['general'][] = [$value->id, $value->ba_preference_2, $value->category];
                                $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_2)][strtolower($value->category)][] = [$value->id, $value->ba_preference_2, $value->category];
                                $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']){
                                // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_3)]['general'][] = [$value->id, $value->ba_preference_3, $value->category];
                                $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_3)][strtolower($value->category)][] = [$value->id, $value->ba_preference_3, $value->category];
                                $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']){
                                // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_4)]['general'][] = [$value->id, $value->ba_preference_4, $value->category];
                                $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                            
                            }else if(!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_4)][strtolower($value->category)][] = [$value->id, $value->ba_preference_4, $value->category];
                                $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                            }
                        // }

                        // var_dump($value->bsc_preference_1." -- ". $value->bsc_preference_2." -- ". $value->bsc_preference_3." -- ". $value->bsc_preference_4. ' =---> '. $value->category);
                    }
                    
                    // ini_set("xdebug.var_display_max_children", '-1');
                    // ini_set("xdebug.var_display_max_data", '-1');
                    // ini_set("xdebug.var_display_max_depth", '-1');

                    // var_dump($selected_matrix);
                    // var_dump($selected_student);
                    // var_dump($selected_student_id);
                    $bsc_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND course='ITEP - B.Sc. B.Ed.' AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC LIMIT $total_bsc_physical");
                    // var_dump($counselling);

                    foreach ($bsc_pwd_counselling as $key => $value) {
                        if(!empty($value->bsc_preference_1)){
                            if(($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]){
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                            }else{
                                // array_pop($selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]);
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
                                // if (($key = array_search($value->id, $selected_student_id['registration_id'])) !== false) {
                                //     unset($selected_student_id[$key]);
                                // }
                                foreach ($selected_student_id as $key => $item) {
                                    if ($item['registration_id'] == $value->id) {
                                        unset($selected_student_id[$key]);
                                    }
                                }
                
                                // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                            }
                        }
                    }

                    $ba_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND course='ITEP - B.A. B.Ed.' AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC LIMIT $total_ba_physical");
                    // var_dump($counselling);

                    foreach ($ba_pwd_counselling as $key => $value) {
                        if(!empty($value->ba_preference_1)){
                            if(($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]){
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                            }else{
                                // array_pop($selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]);
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
                                foreach ($selected_student_id as $key => $item) {
                                    if ($item['registration_id'] == $value->id) {
                                        unset($selected_student_id[$key]);
                                    }
                                }

                                // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => $value->category, 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                            }
                        }
                    }

                    // var_dump($selected_matrix);
                    // var_dump($selected_student);
                    // var_dump($selected_student_id);

                    $studentCounsellingModel->insertBatch($selected_student_id);

                    $data['success_message'] = "Counselling session created successfully";
                }else{
                    $data['error_message'] = "Error creating couselling session";
                }
            }

            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function counsellingWiseStudentList($id){
        try {
            $counsellingModel = new CounsellingModel();

            $data['pageTitle'] = "Counselling";
            $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));

            $data['records'] = $counsellingModel->getCounsellingWiseStudentList($id);

            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/counselling/counselling_wise_student_list", $data) . view('admin/template/footer');
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function counsellingWiseStudentDetail($id){
        try {
            $counsellingModel = new CounsellingModel();
            $ncetScoreModel = new NcetScoreModel();

            $data = [];
            $data['details'] = $counsellingModel->getCounsellingStudentDetail($id);
            unset($data['details']->password);
            $data['ncet'] = $ncetScoreModel->getNcetScoreByRegistrationId(22);

            $bscPreferences = [$data['details']->bsc_preference_1, $data['details']->bsc_preference_2, $data['details']->bsc_preference_3, $data['details']->bsc_preference_4];
            $baPreferences = [$data['details']->ba_preference_1, $data['details']->ba_preference_2, $data['details']->ba_preference_3, $data['details']->ba_preference_4];

            $data['preferences'] = [
                'B.Sc. B.Ed.' => array_filter($bscPreferences, fn($value) => !is_null($value) && $value !== ''), 
                'B.A. B.Ed.' => array_filter($baPreferences, fn($value) => !is_null($value) && $value !== '')
            ];

            $data['pageTitle'] = "Student - Details";
            $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));
            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/counselling/counselling_wise_student_detail", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            // return redirect()->to('/500');
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function rejectCounselling($id){
        try {
            $studentCounsellingModel = new StudentCounsellingModel();
            $result = $studentCounsellingModel->set('status', 'Reject')->where('id', $id)->update();

            if($result){
                echo json_encode(['status' => 200, 'message' => 'Counselling Rejected Successfully']);
            }else{
                echo json_encode(['status' => 400, 'message' => 'Failed to Reject Counselling']);
            }
        }catch(Exception $e){
            echo json_encode(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function acceptCounselling($id){
        try {
            $studentCounsellingModel = new StudentCounsellingModel();
            $itepMatrixModel = new ITEPSeatMatrixModel();

            $data = $this->request->getVar();
            // var_dump($data);

            if($data->physical_disable == 'Yes'){
                if($data->course == 'ITEP - B.Sc. B.Ed.'){
                    $itepMatrixModel->set('pwd_used', 'pwd_used + 1', false)->set('pwd_available', 'pwd_available - 1', false)->where('course', 'B.Sc. B.Ed.')->update();
                }else{
                    $itepMatrixModel->set('pwd_used', 'pwd_used + 1', false)->set('pwd_available', 'pwd_available - 1', false)->where('course', 'B.A. B.Ed.')->update();
                }
            }
            
            if($data->category == 'general'){
                $itepResult = $itepMatrixModel->set('general_used', 'general_used + 1', false)->set('general_available', 'general_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            }else if($data->category == 'OBC-(NCL)'){
                $itepResult = $itepMatrixModel->set('obc-ncl_used', 'obc-ncl_used + 1', false)->set('obc-ncl_available', 'obc-ncl_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            }else if($data->category == 'SC'){
                $itepResult = $itepMatrixModel->set('sc_used', 'sc_used + 1', false)->set('sc_available', 'sc_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            }else if($data->category == 'ST'){
                $itepResult = $itepMatrixModel->set('st_used', 'st_used + 1', false)->set('st_available', 'st_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            }else  if($data->category == 'EWS'){
                $itepResult = $itepMatrixModel->set('ews_used', 'ews_used + 1', false)->set('ews_available', 'ews_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            }
            // var_dump($result);
            // $itepMatrixModel->set()
            $result = false;
            if($itepResult){
                $result = $studentCounsellingModel->set('status', 'Accept')->where('id', $id)->update();
            }

            if($result){
                echo json_encode(['status' => 200, 'message' => 'Counselling Accepted Successfully']);
            }else{
                echo json_encode(['status' => 400, 'message' => 'Failed to Accept Counselling']);
            }

        }catch(Exception $e){
            echo json_encode(['status' => 500, 'message' => $e->getMessage()]);
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
            $from = "no-reply@riea.com";
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
}
