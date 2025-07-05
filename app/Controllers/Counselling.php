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

use Dompdf\Dompdf;
use Dompdf\Options;

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

                    $counselling = $counsellingModel->getCounsellingStudentList(" ORDER BY ncet_average_percentile DESC");

                    $matrix = $matrixResult[0];
                    $total_bsc_physical = $matrixResult[1];
                    $total_ba_physical = $matrixResult[2];
                    $bsc_pwd_general = $matrixResult[3];
                    $ba_pwd_general = $matrixResult[4];

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
                        // if (!in_array($value->id, array_column($selected_student_id, 'registration_id')))
                        // var_dump($value->id . ' - '. $value->course);
                        if($value->course == 'ITEP - B.Sc. B.Ed.'){
                            if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']) {
                                $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            } else if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']) {
                                // $selected_matrix[strtolower($value->bsc_preference_2)]['general'][] = [$value->id, $value->bsc_preference_2, $value->category];
                                $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]) {
                                // $selected_matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_2, $value->category];
                                $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']) {
                                // $selected_matrix[strtolower($value->bsc_preference_3)]['general'][] = [$value->id, $value->bsc_preference_3, $value->category];
                                $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]) {
                                // $selected_matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_3, $value->category];
                                $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']) {
                                // $selected_matrix[strtolower($value->bsc_preference_4)]['general'][] = [$value->id, $value->bsc_preference_4, $value->category];
                                $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]) {
                                // $selected_matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_4, $value->category];
                                $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            }
                        }

                        if($value->course == 'ITEP - B.A. B.Ed.'){
                            if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                                // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_1)]['general'][] = [$value->id, $value->ba_preference_1, $value->category];
                                $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            } else if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']) {
                                // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_2)]['general'][] = [$value->id, $value->ba_preference_2, $value->category];
                                $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]) {
                                // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_2)][strtolower($value->category)][] = [$value->id, $value->ba_preference_2, $value->category];
                                $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']) {
                                // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_3)]['general'][] = [$value->id, $value->ba_preference_3, $value->category];
                                $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]) {
                                // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_3)][strtolower($value->category)][] = [$value->id, $value->ba_preference_3, $value->category];
                                $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']) {
                                // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_4)]['general'][] = [$value->id, $value->ba_preference_4, $value->category];
                                $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]) {
                                // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_4)][strtolower($value->category)][] = [$value->id, $value->ba_preference_4, $value->category];
                                $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            }
                        }

                        if($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.'){
                            // var_dump($value->course.' - '. $value->id);
                            if(!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']){
                                // $selected_student[] = [$value->id, $value->bsc_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_1)]['general'][] = [$value->id, $value->bsc_preference_1, $value->category];
                                $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            
                            }else if(!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->bsc_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            
                            }else if(!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']){
                                // $selected_student[] = [$value->id, $value->bsc_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_2)]['general'][] = [$value->id, $value->bsc_preference_2, $value->category];
                                $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            
                            }else if(!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->bsc_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_2, $value->category];
                                $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            
                            }else if(!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']){
                                // $selected_student[] = [$value->id, $value->bsc_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_3)]['general'][] = [$value->id, $value->bsc_preference_3, $value->category];
                                $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            
                            }else if(!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->bsc_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_3, $value->category];
                                $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            
                            }else if(!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']){
                                // $selected_student[] = [$value->id, $value->bsc_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_4)]['general'][] = [$value->id, $value->bsc_preference_4, $value->category];
                                $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            
                            }else if(!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->bsc_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_4, $value->category];
                                $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            }

                            // BA STUDENT
                            if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                                // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_1)]['general'][] = [$value->id, $value->ba_preference_1, $value->category];
                                $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            
                            }else if(!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            
                            }else if(!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']){
                                // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_2)]['general'][] = [$value->id, $value->ba_preference_2, $value->category];
                                $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            
                            }else if(!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_2)][strtolower($value->category)][] = [$value->id, $value->ba_preference_2, $value->category];
                                $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            
                            }else if(!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']){
                                // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_3)]['general'][] = [$value->id, $value->ba_preference_3, $value->category];
                                $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            
                            }else if(!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_3)][strtolower($value->category)][] = [$value->id, $value->ba_preference_3, $value->category];
                                $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            
                            }else if(!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']){
                                // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_4)]['general'][] = [$value->id, $value->ba_preference_4, $value->category];
                                $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            
                            }else if(!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]){
                                // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                                // $selected_matrix[strtolower($value->ba_preference_4)][strtolower($value->category)][] = [$value->id, $value->ba_preference_4, $value->category];
                                $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                            }
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
                    $bsc_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND course IN ('ITEP - B.Sc. B.Ed.', 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC");
                    // var_dump($counselling);

                    // foreach ($bsc_pwd_counselling as $key => $value) {
                    //     if (!empty($value->bsc_preference_1)) {
                    //         if (($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                    //             $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                    //             // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                    //             $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                    //         } else {
                    //             // array_pop($selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]);
                    //             $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
                    //             // if (($key = array_search($value->id, $selected_student_id['registration_id'])) !== false) {
                    //             //     unset($selected_student_id[$key]);
                    //             // }
                    //             // foreach ($selected_student_id as $key => $item) {
                    //             //     if ($item['registration_id'] == $value->id) {
                    //             //         unset($selected_student_id[$key]);
                    //             //     }
                    //             // }

                    //             $idx = '';
                    //             foreach ($selected_student_id as $key => $item) {
                    //                 // var_dump($item['category'], $item['subject']);
                    //                 if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1)) {
                    //                     // var_dump('Key --> '.$key, $item['category'], $item['subject']);
                    //                     $idx = $key;
                    //                 }
                    //             }

                    //             // var_dump('idx --> ', $idx);
                    //             if(!empty($idx)){
                    //                 unset($selected_student_id[$idx]);
                    //             }

                    //             // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                    //             $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                    //             $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                    //         }
                    //     }
                    // }

                    $selected_bsc_physical = 0;
                    $selected_ba_physical = 0;
                    $selected_bsc_pwd_general = 0;
                    $selected_ba_pwd_general = 0;
                    foreach ($bsc_pwd_counselling as $key => $value) {
                        if($value->course == 'ITEP - B.Sc. B.Ed.'){
                            if($selected_bsc_physical < $total_bsc_physical){
                                // var_dump($key .' ---- '. $selected_bsc_physical .' --- '.$value->id);
                                // Extract registration_ids from existing array
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    // var_dump('Enter');
                        if(!empty($value->bsc_preference_1)){
                                        if($selected_bsc_pwd_general < $bsc_pwd_general){
                                            if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                            } else {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;
                                                
                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }
                                                if(!empty($idx)){
                                                    unset($selected_student_id[$idx]);
                                                }
                            
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                            }
                                            $selected_bsc_pwd_general++;
                                            $selected_bsc_physical++;
                                        }else{
                                            if(strtolower($value->category) != 'general'){
                                                // var_dump('Category Entr');
                                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                            }else{
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
                                                    $idx = '';
                                foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }
                                                    if(!empty($idx)){
                                                        unset($selected_student_id[$idx]);
                                                    }
                                
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                                }
                                                $selected_bsc_physical++;
                                            }
                                        }
                                    }
                                }
                            }else{
                                break;
                                    }
                                }
                
                        if($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.'){
                            if($selected_bsc_physical < $total_bsc_physical){
                                // Extract registration_ids from existing array
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    if (!empty($value->bsc_preference_1)) {
                                        if($selected_bsc_pwd_general < $bsc_pwd_general){
                                            if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                            } else {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;
                                                
                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }
                                                if(!empty($idx)){
                                                    unset($selected_student_id[$idx]);
                                                }
                            
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                            }
                                            $selected_bsc_pwd_general++;
                                            $selected_bsc_physical++;
                                        }else{
                                            if(strtolower($value->category) != 'general'){
                                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                                } else {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
                                                    
                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }
                                                    if(!empty($idx)){
                                                        unset($selected_student_id[$idx]);
                                                    }
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                                }
                                                $selected_bsc_physical++;
                                            }
                                        }
                                    }
                                }
                            }

                            if($selected_ba_physical < $total_ba_physical){
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    if (!empty($value->ba_preference_1)) {
                                        if($selected_ba_pwd_general < $ba_pwd_general){
                                            if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                            } else {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;
                
                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }
                
                                                if(!empty($idx)){
                                                    unset($selected_student_id[$idx]);
                                                }
                
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                            }
                                            $selected_ba_pwd_general++;
                                            $selected_ba_physical++;
                                        }else{
                                            if(strtolower($value->category) != 'general'){
                                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                                } else {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
                    
                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }
                    
                                                    if(!empty($idx)){
                                                        unset($selected_student_id[$idx]);
                                                    }
                    
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                                }
                                                $selected_ba_physical++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $ba_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND course IN ('ITEP - B.A. B.Ed.', 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC");
                    // var_dump($counselling);

                    // foreach ($ba_pwd_counselling as $key => $value) {
                    //     if (!empty($value->ba_preference_1)) {
                    //         if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                    //             $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                    //             $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                    //             // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                    //         } else {
                    //             // array_pop($selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]);
                    //             $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
                    //             // foreach ($selected_student_id as $key => $item) {
                    //             //     if ($item['registration_id'] == $value->id) {
                    //             //         unset($selected_student_id[$key]);
                    //             //     }
                    //             // }

                    //             $idx = '';
                    //             foreach ($selected_student_id as $key => $item) {
                    //                 // var_dump($item['category'], $item['subject']);
                    //                 if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1)) {
                    //                     // var_dump('Key --> '.$key, $item['category'], $item['subject']);
                    //                     $idx = $key;
                    //                 }
                    //             }

                    //             // var_dump('idx --> ', $idx);
                    //             if(!empty($idx)){
                    //                 unset($selected_student_id[$idx]);
                    //             }

                    //             // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                    //             $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                    //             $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                    //         }
                    //     }
                    // }
                    foreach ($ba_pwd_counselling as $key => $value) {
                        if($value->course == 'ITEP - B.A. B.Ed.'){
                            if($selected_ba_physical < $total_ba_physical){
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                        if(!empty($value->ba_preference_1)){
                                        if($selected_ba_pwd_general < $ba_pwd_general){
                                            if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                            } else {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;
                
                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }
                
                                                if(!empty($idx)){
                                                    unset($selected_student_id[$idx]);
                                                }
                
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                            }
                                            $selected_ba_pwd_general++;
                                            $selected_ba_physical++;
                                        }else{
                                            if(strtolower($value->category) != 'general'){
                            if(($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]){
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                                } else {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
                    
                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }
                    
                                                    if(!empty($idx)){
                                                        unset($selected_student_id[$idx]);
                                                    }
                    
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                                }
                                                $selected_ba_physical++;
                                            }
                                        }
                                    }
                                }
                            }else{
                                break;
                            }
                        }
                        
                        if($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.'){
                            if($selected_bsc_physical < $total_bsc_physical){
                                // Extract registration_ids from existing array
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    if (!empty($value->bsc_preference_1)) {
                                        if($selected_bsc_pwd_general < $bsc_pwd_general){
                                            if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                            } else {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;
                                                
                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }
                                                if(!empty($idx)){
                                                    unset($selected_student_id[$idx]);
                                                }
                            
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                            }
                                            $selected_bsc_pwd_general++;
                                            $selected_bsc_physical++;
                                        }else{
                                            if(strtolower($value->category) != 'general'){
                                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                                } else {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
                                                    
                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }
                                                    if(!empty($idx)){
                                                        unset($selected_student_id[$idx]);
                                                    }
                                
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                                }
                                                $selected_bsc_physical++;
                                            }
                                        }
                                    }
                                }
                            }

                            if($selected_ba_physical < $total_ba_physical){
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    if (!empty($value->ba_preference_1)) {
                                        if($selected_ba_pwd_general < $ba_pwd_general){
                                            if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                            } else {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;
                
                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }
                
                                                if(!empty($idx)){
                                                    unset($selected_student_id[$idx]);
                                                }
                
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                            }
                                            $selected_ba_pwd_general++;
                                            $selected_ba_physical++;
                            }else{
                                            if(strtolower($value->category) != 'general'){
                                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                                } else {
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
                                                    $idx = '';
                                foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                    }
                                }

                                                    if(!empty($idx)){
                                                        unset($selected_student_id[$idx]);
                                                    }
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                                }
                                                $selected_ba_physical++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // var_dump($selected_matrix);
                    // var_dump($selected_student);
                    // var_dump($selected_student_id);

                    $studentCounsellingModel->insertBatch($selected_student_id);

                    $subject_array = ['Physics', 'Chemistry', 'Mathematics', 'Botany', 'Zoology', 'History', 'Geography', 'English Language and Literature', 'Hindi Language and Literature', 'Urdu'];

                    foreach ($subject_array as $value) {
                        $info = $this->subjectPdf($value);
                        // var_dump(count($info));
                        foreach ($info as $key => $value) {
                            $email = \Config\Services::email();
                            $from = "no-reply@riea.com";
                            $fromName = "RIE Ajmer";
        
                            $msg="Dear Candidate,<br/><br/>";
        
                            $msg.='<html lang="en"><head><meta charset="UTF-8"><title>ITEP Admission 2025</title><style> body { text-align: center; padding: 40px; } .content { text-align: left; display: inline-block; max-width: 800px; text-align: justify; border: 2px solid black; padding: 10px 20px; } h2 { text-align: center; text-decoration: underline; } ul { margin-top: 0; } </style></head><body><div class="content"><ol><li>On the basis of your application, you have been provisionally selected for admission to the above programme in this Institute for the academic session 2025-26 ('.$value["subject"].').</li><li>In order to confirm your provisional admission you have to deposit Institute fees on or before <strong>07.07.2025</strong> by <strong>11:59 pm</strong> as given below:- <ul style="list-style-type:disc;"><li>GENERAL/ OBC/ EWS students: Rs. 7,450/- (without Hostel)</li><li>SC/ST/PH students: Rs. 4,950/- (without Hostel)</li><li>GENERAL/ OBC/ EWS students: Rs. 29,650/- (with Hostel)</li><li>SC/ST/PH students: Rs. 27,150/- (with Hostel)</li></ul></li><li>Your provisional admission will be treated as cancelled if- <ol type="a"><li>Any of your documents is found to be forged or false.</li><li>Any misleading statement or suppression of facts is detected in your application at any time during the session.</li><li>In case there is incomplete/wrong entry in the marks obtained in the qualifying examination filled online by the applicant in the NCET 2025 application form.</li><li>If the requisite fees is not deposited within the prescribed time.</li><li>If your conduct in and outside the Institute during the session is found to be unsatisfactory.</li></ol></li><li>Following documents are to be produced in original at the time of physical reporting in the Institute on 28.07.2025 at 10:00 am in Room No.126 at RIE, Ajmer. <ol type="i"><li>NCET-2025 Online Application Form and NTA - Score Card</li><li>Secondary Examination Mark Sheet/Secondary Examination Certificate (for Date of Birth).</li><li>Mark sheet of qualifying examination and other mark sheets, if any.</li><li>As per rules issued valid category certificate (SC/ST/OBC/EWS if required), OBC certificate must necessarily show that the applicant does not belong to the Creamy Layer</li><li>Disability Certificate (if required).</li><li>Transfer Certificate and Character Certificate of last School/College attended.</li><li>Certificate issued by the authorized Medical Officer as per the format available in your login document section.</li><li>The candidate has to submit a declaration/commitment signed by himself and the parents/guardian in the format available in your login document section.</li><li>The candidate will have to submit an undertaking signed by himself and the parent/guardian that if semester wise prescribed attendance is not completed in the Institute, the admission of the candidate in the hostel or the Institute or both can be cancelled.</li><li>Student and Parents/Guardian have to submit respective undertakings for the declaration of Anti-Ragging as per the format available in your login document section.</li><li>It will be mandatory to submit the police verification certificate of the student issued by the Police Department to the effect that no case is pending against the student concerned.</li><li>It is mandatory to submit the Income Certificate of the financial year 2024-25 of the total family (mother and father) which has been issued on or after 01.04.2025. In case the mother is a housewife, an affidavit on a ten rupee stamp has to be submitted by the father stating that my wife is a housewife and is completely dependent on the husband&apos;s income. This affidavit has to be submitted signed by anotary.</li><li>Five photographs of the student (when attending the Institute).</li></ol></li><li>Please pay special attention to the following points- <ol type="i"><li>As per the undertaking/commitment prescribed for the students and parents, they are expected to go through the UGC Regulations on Controlling the menace of Ragging 2009 thoroughly available on the website of Regional Institute ofEducation, Ajmer.</li><li>After admission in the Institute, it is mandatory for the student to get himself/herself registered on the MoE Anti Ragging Portal and its URID number will be made available to the institute.</li></ol></li></ol><p><strong>NOTE - In case of any query the candidates may contact on helpline No.0145-2643760 and <a href="mailto:helpitepadmission@rieajmer.ac.in">helpitepadmission@rieajmer.ac.in</a>.</strong><p style="text-align:start;"><a href="https://demo.riea.in/public/Documents-proforma-online-counselling.pdf">Click Here</a> to Download Counselling Form.</p><p style="text-align: right; margin-top: 15px;"><strong>Academic Section<br>R.I.E., Ajmer</strong></p></div></body></html>';
        
                            $subject = "Admission in 4-Year ".$value['course']." (".$value["subject"].") for the session 2025-26 regarding";
        
                            $email->setFrom($from,$fromName);
                            // $email->setTo($value['email']);
                            $email->setTo("abhishek.sharma@ibirdsservices.com");
        
                            $email->setSubject($subject);
                            $email->setMessage($msg);
                            $email->attach('https://demo.riea.in/public/subject_wise_lists/'.$value['subject'].'.pdf');
        
                            $mail = $email->send();
        
                            if( $mail == true ) {
                                echo json_encode(['message' => 'Counselling Mail Sent Successfully.', 'success' => true]);
                            }else {
                                // print_r($email->printDebugger(['headers']));exit;
                                echo json_encode(['message' => 'Something went wrong', 'success' => false]);
                            }
                            $email->clear(true);
                        }
                    }
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
    public function subjectWiseStudentList(){
        try {
            $studentCounsellingModel = new StudentCounsellingModel();

            $data['pageTitle'] = "Subject Wise Student List";
            $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));

            $input = $this->request->getVar();
            $data['subject']= '';
            $subject = '';

            if(count($input)){
                $subject = $input['subject'];
            }

            if(isset($_SESSION['subject']) && $_SESSION['subject'] != ''){
                $subject = session()->get('subject');
            }

            if($subject != ''){
                // $subject = $input['subject'];
                $data['subject'] = $subject;
                $result = $studentCounsellingModel->getSubjectWiseStudentList($subject);
                
                foreach ($result as $value) {
                    if($value['student_counselling_category'] ==  'obc-(ncl)'){
                        $data['obc_ncl'][] = $value;
                    }else{
                        $data[$value['student_counselling_category']][] = $value;
                    }
                }
            }
            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/subject/subject_wise_student_list", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            // return redirect()->to('/500');
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function subjectPdf($subject='Physics'){
        $studentCounsellingModel = new StudentCounsellingModel();
        $itepMatrixModel = new ITEPSeatMatrixModel();

        $result = $studentCounsellingModel->getSubjectWiseStudentList($subject);
        $matrixResult = $itepMatrixModel->fetchBySubject($subject);

        $info = [];

        // var_dump($result);exit;
        $data = [];
        foreach ($result as $value) {
            if($value['student_counselling_category'] ==  'obc-(ncl)'){
                $data['obc_ncl'][] = $value;
            }else{
                $data[$value['student_counselling_category']][] = $value;
            }
            $info[] = ['subject' => $value['student_counselling_subject'], 'email' => $value['email'], 'course'=> $value['course']];
        }
        // var_dump($data);exit;

        // 1. Create your HTML content as a string
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Selected Candidate List - ('.$subject.')</title>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 12px; text-align: center; }
                .list-title,.subtitle,.title{font-weight:700}
                .title{font-size:22px}
                .subtitle{font-size:18px}
                .categories,.list-title{font-size:16px}
                .categories span{margin:0 5px}
                table { width: 100%; border-collapse: collapse; margin-top:2rem; }
                table, th, td { border: 1px solid black; padding: 5px; }
            </style>
        </head>
        <body>
            <div class="title">REGIONAL INSTITUTE OF EDUCATION, AJMER</div>
            <div class="subtitle">Class : 4-Year ITEP '.$matrixResult['course'].' Session : 2025-26</div>
            <div class="list-title">Provisional Selected Candidate List - ('.$subject.')</div>
            <div class="categories">
                <span>(General - <strong>'.$matrixResult['general'].'</strong></span> |
                <span>OBC-NCL - <strong>'.$matrixResult['obc-(ncl)'].'</strong></span> |
                <span>SC - <strong>'.$matrixResult['sc'].'</strong></span> |
                <span>ST - <strong>'.$matrixResult['st'].'</strong></span> |
                <span>EWS - <strong>'.$matrixResult['ews'].'</strong>)</span>
            </div>
            <table>
                <thead>
                    <tr><th colspan="9" style="text-align: center;">Category: GENERAL</th></tr>
                    <tr>
                        <th>NCET Application</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Major Allotment</th>
                        <th>Percentile</th>
                        <th>Preference 1</th>
                        <th>Preference 2</th>
                        <th>Preference 3</th>
                        <th>Preference 4</th>
                    </tr>
                </thead>
                <tbody>';
                if(isset($data['general']) && count($data['general']) > 0){ foreach ($data['general'] as $key => $value) {
                    $html .= '<tr>
                    <td>'.$value["ncet_application_no"].'</td>
                    <td>'.$value["name"].'</td>
                    <td>'.$value["category"] . ($value["physical_disable"]== 'Yes' ? ' - PwD' : '') .'</td>
                    <td>'.$value["student_counselling_subject"].'</td>
                    <td>'.$value["ncet_average_percentile"].'</td>';

                    if($matrixResult['course'] == 'B.Sc. B.Ed.'){
                        $html .= '<td>'.$value["bsc_preference_1"].'</td><td>'.$value["bsc_preference_2"].'</td><td>'.$value["bsc_preference_3"].'</td><td>'.$value["bsc_preference_4"].'</td>';
                    }else if($matrixResult['course'] == 'B.A. B.Ed.'){
                        $html .= '<td>'.$value["ba_preference_1"].'</td><td>'.$value["ba_preference_2"].'</td><td>'.$value["ba_preference_3"].'</td><td>'.$value["ba_preference_4"].'</td>';
                    }

                    $html .= '</tr>';
                }}else{ $html .= '<tr><td colspan="9" style="text-align: center;">No Student Selected</td></tr>'; }
            $html .= '</tbody></table>';

        $html .= '<table>
                <thead>
                    <th colspan="9" style="text-align: center;">Category: OBC-NCL</th></tr>
                    <tr>
                        <th>NCET Application</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Major Allotment</th>
                        <th>Percentile</th>
                        <th>Preference 1</th>
                        <th>Preference 2</th>
                        <th>Preference 3</th>
                        <th>Preference 4</th>
                    </tr>
                </thead>
                <tbody>';
            if(isset($data['obc_ncl']) && count($data['obc_ncl']) > 0){ foreach ($data['obc_ncl'] as $key => $value) {
                $html .= '<tr>
                <td>'.$value["ncet_application_no"].'</td>
                <td>'.$value["name"].'</td>
                <td>'.$value["category"] . ($value["physical_disable"]== 'Yes' ? ' - PwD' : '') .'</td>
                <td>'.$value["student_counselling_subject"].'</td>
                <td>'.$value["ncet_average_percentile"].'</td>';

                if($matrixResult['course'] == 'B.Sc. B.Ed.'){
                    $html .= '<td>'.$value["bsc_preference_1"].'</td><td>'.$value["bsc_preference_2"].'</td><td>'.$value["bsc_preference_3"].'</td><td>'.$value["bsc_preference_4"].'</td>';
                }else if($matrixResult['course'] == 'B.A. B.Ed.'){
                    $html .= '<td>'.$value["ba_preference_1"].'</td><td>'.$value["ba_preference_2"].'</td><td>'.$value["ba_preference_3"].'</td><td>'.$value["ba_preference_4"].'</td>';
                }

                $html .= '</tr>';
            }}else{ $html .= '<tr><td colspan="9" style="text-align: center;">No Student Selected</td></tr>'; }
            $html .= '</tbody></table>';

            $html .= '<table>
                    <thead>
                        <tr><th colspan="9" style="text-align: center;">Category: SC</th></tr>
                        <tr>
                            <th>NCET Application</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Major Allotment</th>
                            <th>Percentile</th>
                            <th>Preference 1</th>
                            <th>Preference 2</th>
                            <th>Preference 3</th>
                            <th>Preference 4</th>
                        </tr>
                    </thead>
                    <tbody>';
            if(isset($data['sc']) && count($data['st']) > 0){ foreach ($data['sc'] as $key => $value) {
                $html .= '<tr>
                <td>'.$value["ncet_application_no"].'</td>
                <td>'.$value["name"].'</td>
                <td>'.$value["category"] . ($value["physical_disable"]== 'Yes' ? ' - PwD' : '') .'</td>
                <td>'.$value["student_counselling_subject"].'</td>
                <td>'.$value["ncet_average_percentile"].'</td>';

                if($matrixResult['course'] == 'B.Sc. B.Ed.'){
                    $html .= '<td>'.$value["bsc_preference_1"].'</td><td>'.$value["bsc_preference_2"].'</td><td>'.$value["bsc_preference_3"].'</td><td>'.$value["bsc_preference_4"].'</td>';
                }else if($matrixResult['course'] == 'B.A. B.Ed.'){
                    $html .= '<td>'.$value["ba_preference_1"].'</td><td>'.$value["ba_preference_2"].'</td><td>'.$value["ba_preference_3"].'</td><td>'.$value["ba_preference_4"].'</td>';
                }

                $html .= '</tr>';
            }}else{ $html .= '<tr><td colspan="9" style="text-align: center;">No Student Selected</td></tr>'; }
            $html .= '</tbody></table>';

            $html .= '<table>
                    <thead>
                        <tr><th colspan="9" style="text-align: center;">Category: ST</th></tr>
                        <tr>
                            <th>NCET Application</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Major Allotment</th>
                            <th>Percentile</th>
                            <th>Preference 1</th>
                            <th>Preference 2</th>
                            <th>Preference 3</th>
                            <th>Preference 4</th>
                        </tr>
                    </thead>
                    <tbody>';
            if(isset($data['st']) && count($data['st']) > 0){ foreach ($data['st'] as $key => $value) {
                $html .= '<tr>
                <td>'.$value["ncet_application_no"].'</td>
                <td>'.$value["name"].'</td>
                <td>'.$value["category"] . ($value["physical_disable"]== 'Yes' ? ' - PwD' : '') .'</td>
                <td>'.$value["student_counselling_subject"].'</td>
                <td>'.$value["ncet_average_percentile"].'</td>';

                if($matrixResult['course'] == 'B.Sc. B.Ed.'){
                    $html .= '<td>'.$value["bsc_preference_1"].'</td><td>'.$value["bsc_preference_2"].'</td><td>'.$value["bsc_preference_3"].'</td><td>'.$value["bsc_preference_4"].'</td>';
                }else if($matrixResult['course'] == 'B.A. B.Ed.'){
                    $html .= '<td>'.$value["ba_preference_1"].'</td><td>'.$value["ba_preference_2"].'</td><td>'.$value["ba_preference_3"].'</td><td>'.$value["ba_preference_4"].'</td>';
                }

                $html .= '</tr>';
            }}else{ $html .= '<tr><td colspan="9" style="text-align: center;">No Student Selected</td></tr>'; }
            $html .= '</tbody></table>';

            $html .= '<table>
                    <thead>
                        <tr><th colspan="9" style="text-align: center;">Category: EWS</th></tr>
                        <tr>
                            <th>NCET Application</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Major Allotment</th>
                            <th>Percentile</th>
                            <th>Preference 1</th>
                            <th>Preference 2</th>
                            <th>Preference 3</th>
                            <th>Preference 4</th>
                        </tr>
                    </thead>
                    <tbody>';
            if(isset($data['ews']) && count($data['ews']) > 0){ foreach ($data['ews'] as $key => $value) {
                $html .= '<tr>
                <td>'.$value["ncet_application_no"].'</td>
                <td>'.$value["name"].'</td>
                <td>'.$value["category"] . ($value["physical_disable"]== 'Yes' ? ' - PwD' : '') .'</td>
                <td>'.$value["student_counselling_subject"].'</td>
                <td>'.$value["ncet_average_percentile"].'</td>';

                if($matrixResult['course'] == 'B.Sc. B.Ed.'){
                    $html .= '<td>'.$value["bsc_preference_1"].'</td><td>'.$value["bsc_preference_2"].'</td><td>'.$value["bsc_preference_3"].'</td><td>'.$value["bsc_preference_4"].'</td>';
                }else if($matrixResult['course'] == 'B.A. B.Ed.'){
                    $html .= '<td>'.$value["ba_preference_1"].'</td><td>'.$value["ba_preference_2"].'</td><td>'.$value["ba_preference_3"].'</td><td>'.$value["ba_preference_4"].'</td>';
                }

                $html .= '</tr>';
            }}else{ $html .= '<tr><td colspan="9" style="text-align: center;">No Student Selected</td></tr>'; }
            $html .= '</tbody></table></body></html>';

        // 2. Set Dompdf options
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Enable for using external images/styles
        $dompdf = new Dompdf($options);

        // 3. Load HTML and render PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // 4. Output to browser
        // $dompdf->stream($subject.'.pdf', ['Attachment' => false]);
        
        $output = $dompdf->output();
        file_put_contents(FCPATH.'public/subject_wise_lists/'.$subject.'.pdf', $output);
        // var_dump($info);
        return $info;
    }

    public function calculate()
    {
        $itepMatrixModel = new ITEPSeatMatrixModel();
        $counsellingModel = new CounsellingModel();
        $studentCounsellingModel = new StudentCounsellingModel();

        $counsellingId = 2;

        $matrixResult = $itepMatrixModel->fetchAll();

        // var_dump($matrixResult);//exit;

        $counselling = $counsellingModel->getCounsellingStudentList(" ORDER BY ncet_average_percentile DESC");

        $matrix = $matrixResult[0];
        $total_bsc_physical = $matrixResult[1];
        $total_ba_physical = $matrixResult[2];
        $bsc_pwd_general = $matrixResult[3];
        $ba_pwd_general = $matrixResult[4];

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

        foreach ($counselling as $key => $value) {
            // if (!in_array($value->id, array_column($selected_student_id, 'registration_id')))
            // var_dump($value->id . ' - '. $value->course);
            if($value->course == 'ITEP - B.Sc. B.Ed.'){
                if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']) {
                    $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                    // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']) {
                    // $selected_matrix[strtolower($value->bsc_preference_2)]['general'][] = [$value->id, $value->bsc_preference_2, $value->category];
                    $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]) {
                    // $selected_matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_2, $value->category];
                    $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']) {
                    // $selected_matrix[strtolower($value->bsc_preference_3)]['general'][] = [$value->id, $value->bsc_preference_3, $value->category];
                    $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]) {
                    // $selected_matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_3, $value->category];
                    $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']) {
                    // $selected_matrix[strtolower($value->bsc_preference_4)]['general'][] = [$value->id, $value->bsc_preference_4, $value->category];
                    $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]) {
                    // $selected_matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_4, $value->category];
                    $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                }
            }

            if($value->course == 'ITEP - B.A. B.Ed.'){
                if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_1)]['general'][] = [$value->id, $value->ba_preference_1, $value->category];
                    $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_2)]['general'][] = [$value->id, $value->ba_preference_2, $value->category];
                    $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_2)][strtolower($value->category)][] = [$value->id, $value->ba_preference_2, $value->category];
                    $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_3)]['general'][] = [$value->id, $value->ba_preference_3, $value->category];
                    $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_3)][strtolower($value->category)][] = [$value->id, $value->ba_preference_3, $value->category];
                    $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_4)]['general'][] = [$value->id, $value->ba_preference_4, $value->category];
                    $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_4)][strtolower($value->category)][] = [$value->id, $value->ba_preference_4, $value->category];
                    $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                }
            }

            if($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.'){
                // var_dump($value->course.' - '. $value->id);
                if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_1)]['general'][] = [$value->id, $value->bsc_preference_1, $value->category];
                    $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_2)]['general'][] = [$value->id, $value->bsc_preference_2, $value->category];
                    $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_2, $value->category];
                    $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_3)]['general'][] = [$value->id, $value->bsc_preference_3, $value->category];
                    $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_3, $value->category];
                    $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_4)]['general'][] = [$value->id, $value->bsc_preference_4, $value->category];
                    $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_4, $value->category];
                    $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.Sc. B.Ed.'];
                }
    
                // BA STUDENT
                if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_1)]['general'][] = [$value->id, $value->ba_preference_1, $value->category];
                    $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_2)]['general'][] = [$value->id, $value->ba_preference_2, $value->category];
                    $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_2)][strtolower($value->category)][] = [$value->id, $value->ba_preference_2, $value->category];
                    $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_3)]['general'][] = [$value->id, $value->ba_preference_3, $value->category];
                    $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_3)][strtolower($value->category)][] = [$value->id, $value->ba_preference_3, $value->category];
                    $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_4)]['general'][] = [$value->id, $value->ba_preference_4, $value->category];
                    $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_4)][strtolower($value->category)][] = [$value->id, $value->ba_preference_4, $value->category];
                    $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No', 'course' => 'ITEP - B.A. B.Ed.'];
                }
            }

            // }

            // var_dump($value->bsc_preference_1." -- ". $value->bsc_preference_2." -- ". $value->bsc_preference_3." -- ". $value->bsc_preference_4. ' =---> '. $value->category);
        }

        ini_set("xdebug.var_display_max_children", '-1');
        ini_set("xdebug.var_display_max_data", '-1');
        ini_set("xdebug.var_display_max_depth", '-1');

        // var_dump($selected_matrix);
        // var_dump($selected_student);
        // var_dump($selected_student_id);
        $bsc_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND course IN ('ITEP - B.Sc. B.Ed.', 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC");
        // var_dump($bsc_pwd_counselling);

        $selected_bsc_physical = 0;
        $selected_ba_physical = 0;
        $selected_bsc_pwd_general = 0;
        $selected_ba_pwd_general = 0;

        foreach ($bsc_pwd_counselling as $key => $value) {
            if($value->course == 'ITEP - B.Sc. B.Ed.'){
                if($selected_bsc_physical < $total_bsc_physical){
                    // var_dump($key .' ---- '. $selected_bsc_physical .' --- '.$value->id);
                    // Extract registration_ids from existing array
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        // var_dump('Enter');
                        if (!empty($value->bsc_preference_1)) {
                            if($selected_bsc_pwd_general < $bsc_pwd_general){
                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                } else {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;
                                    
                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }
                                    if(!empty($idx)){
                                        unset($selected_student_id[$idx]);
                                    }
                
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                }
                                $selected_bsc_pwd_general++;
                                $selected_bsc_physical++;
                            }else{
                                if(strtolower($value->category) != 'general'){
                                    // var_dump('Category Entr');
                                    if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                    } else {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
                                        
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
                                        if(!empty($idx)){
                                            unset($selected_student_id[$idx]);
                                        }
                    
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                    }
                                    $selected_bsc_physical++;
                                }
                            }
                        }
                    }
                }else{
                    break;
                }
            }

            if($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.'){
                if($selected_bsc_physical < $total_bsc_physical){
                    // Extract registration_ids from existing array
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->bsc_preference_1)) {
                            if($selected_bsc_pwd_general < $bsc_pwd_general){
                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                } else {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;
                                    
                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }
                                    if(!empty($idx)){
                                        unset($selected_student_id[$idx]);
                                    }
                
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                }
                                $selected_bsc_pwd_general++;
                                $selected_bsc_physical++;
                            }else{
                                if(strtolower($value->category) != 'general'){
                                    if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                    } else {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
                                        
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
                                        if(!empty($idx)){
                                            unset($selected_student_id[$idx]);
                                        }
                    
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                    }
                                    $selected_bsc_physical++;
                                }
                            }
                        }
                    }
                }

                if($selected_ba_physical < $total_ba_physical){
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->ba_preference_1)) {
                            if($selected_ba_pwd_general < $ba_pwd_general){
                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                } else {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;
    
                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }
    
                                    if(!empty($idx)){
                                        unset($selected_student_id[$idx]);
                                    }
    
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                }
                                $selected_ba_pwd_general++;
                                $selected_ba_physical++;
                            }else{
                                if(strtolower($value->category) != 'general'){
                                    if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                    } else {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
        
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
        
                                        if(!empty($idx)){
                                            unset($selected_student_id[$idx]);
                                        }
        
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                    }
                                    $selected_ba_physical++;
                                }
                            }
                        }
                    }
                }
            }
        }

        // var_dump($selected_student_id);
        // var_dump($selected_bsc_physical,   $selected_ba_physical,   $selected_bsc_pwd_general,   $selected_ba_pwd_general);

        $ba_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND course IN ('ITEP - B.A. B.Ed.', 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC");

        foreach ($ba_pwd_counselling as $key => $value) {
            if($value->course == 'ITEP - B.A. B.Ed.'){
                if($selected_ba_physical < $total_ba_physical){
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->ba_preference_1)) {
                            if($selected_ba_pwd_general < $ba_pwd_general){
                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                } else {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;
    
                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }
    
                                    if(!empty($idx)){
                                        unset($selected_student_id[$idx]);
                                    }
    
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                }
                                $selected_ba_pwd_general++;
                                $selected_ba_physical++;
                            }else{
                                if(strtolower($value->category) != 'general'){
                                    if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                    } else {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
        
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
        
                                        if(!empty($idx)){
                                            unset($selected_student_id[$idx]);
                                        }
        
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                    }
                                    $selected_ba_physical++;
                                }
                            }
                        }
                    }
                }else{
                    break;
                }
            }
            
            if($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.'){
                if($selected_bsc_physical < $total_bsc_physical){
                    // Extract registration_ids from existing array
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->bsc_preference_1)) {
                            if($selected_bsc_pwd_general < $bsc_pwd_general){
                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                } else {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;
                                    
                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }
                                    if(!empty($idx)){
                                        unset($selected_student_id[$idx]);
                                    }
                
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                }
                                $selected_bsc_pwd_general++;
                                $selected_bsc_physical++;
                            }else{
                                if(strtolower($value->category) != 'general'){
                                    if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                    } else {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
                                        
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
                                        if(!empty($idx)){
                                            unset($selected_student_id[$idx]);
                                        }
                    
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.Sc. B.Ed.'];
                                    }
                                    $selected_bsc_physical++;
                                }
                            }
                        }
                    }
                }

                if($selected_ba_physical < $total_ba_physical){
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->ba_preference_1)) {
                            if($selected_ba_pwd_general < $ba_pwd_general){
                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                } else {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;
    
                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }
    
                                    if(!empty($idx)){
                                        unset($selected_student_id[$idx]);
                                    }
    
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                }
                                $selected_ba_pwd_general++;
                                $selected_ba_physical++;
                            }else{
                                if(strtolower($value->category) != 'general'){
                                    if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                    } else {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
        
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
        
                                        if(!empty($idx)){
                                            unset($selected_student_id[$idx]);
                                        }
        
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes', 'course' => 'ITEP - B.A. B.Ed.'];
                                    }
                                    $selected_ba_physical++;
                                }
                            }
                        }
                    }
                }
            }
        }

        // var_dump($selected_student);
        // var_dump(($selected_student_id));
        // var_dump($bsc_pwd_counselling, $ba_pwd_counselling);
        // var_dump($selected_bsc_physical,   $selected_ba_physical,   $selected_bsc_pwd_general,   $selected_ba_pwd_general);
    }
}
