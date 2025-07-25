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

    public function insert()
    {
        try {
            $data = [];
            $itepMatrixModel = new ITEPSeatMatrixModel();
            $counsellingModel = new CounsellingModel();
            $studentCounsellingModel = new StudentCounsellingModel();

            $input = $this->request->getVar();
            // var_dump($input);

            if (isset($input) && !empty($input) && !empty($input['start_date']) && !empty($input['end_date'])) {
                $data['start_date'] = $input['start_date'];
                $data['end_date'] = $input['end_date'];

                $result = $counsellingModel->insert($input);
                $counsellingId = $counsellingModel->getInsertID();
                if ($result) {
                    $matrixResult = $itepMatrixModel->fetchAll();

                    $acceptStudentList = $studentCounsellingModel->getAcceptedStudentCounsellingList();

                    $matrix = $matrixResult[0];
                    $total_bsc_physical = $matrixResult[1];
                    $total_ba_physical = $matrixResult[2];
                    $bsc_pwd_general = $matrixResult[3];
                    $ba_pwd_general = $matrixResult[4];

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

                    $selected_student_id = [];

                    $counselling = [];
                    $skipedStudent = ['255110017531'];

                    array_walk($acceptStudentList, function ($value) use (&$counselling) {
                        $counselling[] = $value;
                    });

                    $counsel = $counsellingModel->getCounsellingStudentList(" AND id NOT IN (SELECT student_counselling.registration_id FROM student_counselling) ORDER BY ncet_average_percentile DESC");

                    array_walk($counsel, function ($value) use (&$counselling) {
                        $counselling[] = $value;
                    });

                    usort($counselling, function ($a, $b) {
                        return (float)$b->ncet_average_percentile <=> (float)$a->ncet_average_percentile;
                    });

                    // var_dump('Counselling Count: ' . count($counselling));
                    foreach ($counselling as $key => $value) {
                        if (!in_array($value->ncet_application_no, $skipedStudent)) {
                            if ($value->course == 'ITEP - B.Sc. B.Ed.') {
                                if (isset($value->student_counselling_subject) && !empty($value->student_counselling_subject)) {
                                    $bsc_preferences = [
                                        $value->bsc_preference_1,
                                        $value->bsc_preference_2,
                                        $value->bsc_preference_3,
                                        $value->bsc_preference_4
                                    ];

                                    $currentIndex = $this->getPreferenceIndex($value->student_counselling_subject, $bsc_preferences);
                                    foreach ($bsc_preferences as $index => $preference) {
                                        $subjectKey = strtolower($preference);
                                        $categoryKey = strtolower($value->category);

                                        if (empty($preference) || $index >= $currentIndex) continue; // skip same or lower preference

                                        // General category allotment
                                        if ($selected_student[$subjectKey]['general'] < $matrix[$subjectKey]['general']) {
                                            $selected_student[$subjectKey]['general']++;
                                            $selected_student_id[] = [
                                                'counselling_id' => $counsellingId,
                                                'registration_id' => $value->id,
                                                'category' => 'general',
                                                'subject' => $preference,
                                                'physical_disable' => 'No'
                                            ];
                                            break; // Stop at first valid upward allotment
                                        }
                                        // Reserved category allotment
                                        if ($selected_student[$subjectKey][$categoryKey] < $matrix[$subjectKey][$categoryKey]) {
                                            $selected_student[$subjectKey][$categoryKey]++;
                                            $selected_student_id[] = [
                                                'counselling_id' => $counsellingId,
                                                'registration_id' => $value->id,
                                                'category' => $categoryKey,
                                                'subject' => $preference,
                                                'physical_disable' => 'No'
                                            ];
                                            break;
                                        }
                                    }
                                } else {
                                    if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']) {
                                        $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']) {
                                        $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']) {
                                        $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']) {
                                        $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                                    }
                                }
                            }

                            if ($value->course == 'ITEP - B.A. B.Ed.') {
                                if (isset($value->student_counselling_subject) && !empty($value->student_counselling_subject)) {
                                    $ba_preferences = [
                                        $value->ba_preference_1,
                                        $value->ba_preference_2,
                                        $value->ba_preference_3,
                                        $value->ba_preference_4
                                    ];

                                    $currentIndex = $this->getPreferenceIndex($value->student_counselling_subject, $ba_preferences);
                                    foreach ($ba_preferences as $index => $preference) {
                                        $subjectKey = strtolower($preference);
                                        $categoryKey = strtolower($value->category);

                                        if (empty($preference) || $index >= $currentIndex) continue; // skip same or lower preference

                                        // General category allotment
                                        if ($selected_student[$subjectKey]['general'] < $matrix[$subjectKey]['general']) {
                                            $selected_student[$subjectKey]['general']++;
                                            $selected_student_id[] = [
                                                'counselling_id' => $counsellingId,
                                                'registration_id' => $value->id,
                                                'category' => 'general',
                                                'subject' => $preference,
                                                'physical_disable' => 'No'
                                            ];
                                            break; // Stop at first valid upward allotment
                                        }
                                        // Reserved category allotment
                                        if ($selected_student[$subjectKey][$categoryKey] < $matrix[$subjectKey][$categoryKey]) {
                                            $selected_student[$subjectKey][$categoryKey]++;
                                            $selected_student_id[] = [
                                                'counselling_id' => $counsellingId,
                                                'registration_id' => $value->id,
                                                'category' => $categoryKey,
                                                'subject' => $preference,
                                                'physical_disable' => 'No'
                                            ];
                                            break;
                                        }
                                    }
                                } else {
                                    if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                                        $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']) {
                                        $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']) {
                                        $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']) {
                                        $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                                    }
                                }
                            }

                            if ($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') {
                                if (isset($value->student_counselling_subject) && !empty($value->student_counselling_subject)) {
                                    // Example for B.Sc. B.Ed.
                                    $bsc_preferences = [
                                        $value->bsc_preference_1,
                                        $value->bsc_preference_2,
                                        $value->bsc_preference_3,
                                        $value->bsc_preference_4
                                    ];

                                    $ba_preferences = [
                                        $value->ba_preference_1,
                                        $value->ba_preference_2,
                                        $value->ba_preference_3,
                                        $value->ba_preference_4
                                    ];

                                    $currentIndex = $this->getPreferenceIndex($value->student_counselling_subject, $bsc_preferences);
                                    foreach ($bsc_preferences as $index => $preference) {
                                        $subjectKey = strtolower($preference);
                                        $categoryKey = strtolower($value->category);

                                        if (empty($preference) || $index >= $currentIndex) continue; // skip same or lower preference

                                        // General category allotment
                                        if ($selected_student[$subjectKey]['general'] < $matrix[$subjectKey]['general']) {
                                            $selected_student[$subjectKey]['general']++;
                                            $selected_student_id[] = [
                                                'counselling_id' => $counsellingId,
                                                'registration_id' => $value->id,
                                                'category' => 'general',
                                                'subject' => $preference,
                                                'physical_disable' => 'No'
                                            ];
                                            break; // Stop at first valid upward allotment
                                        }

                                        // Reserved category allotment
                                        if ($selected_student[$subjectKey][$categoryKey] < $matrix[$subjectKey][$categoryKey]) {
                                            $selected_student[$subjectKey][$categoryKey]++;
                                            $selected_student_id[] = [
                                                'counselling_id' => $counsellingId,
                                                'registration_id' => $value->id,
                                                'category' => $categoryKey,
                                                'subject' => $preference,
                                                'physical_disable' => 'No'
                                            ];
                                            break;
                                        }
                                    }

                                    $currentIndex = $this->getPreferenceIndex($value->student_counselling_subject, $ba_preferences);
                                    foreach ($ba_preferences as $index => $preference) {
                                        $subjectKey = strtolower($preference);
                                        $categoryKey = strtolower($value->category);

                                        if (empty($preference) || $index >= $currentIndex) continue; // skip same or lower preference

                                        // General category allotment
                                        if ($selected_student[$subjectKey]['general'] < $matrix[$subjectKey]['general']) {
                                            $selected_student[$subjectKey]['general']++;
                                            $selected_student_id[] = [
                                                'counselling_id' => $counsellingId,
                                                'registration_id' => $value->id,
                                                'category' => 'general',
                                                'subject' => $preference,
                                                'physical_disable' => 'No'
                                            ];
                                            break; // Stop at first valid upward allotment
                                        }

                                        // Reserved category allotment
                                        if ($selected_student[$subjectKey][$categoryKey] < $matrix[$subjectKey][$categoryKey]) {
                                            $selected_student[$subjectKey][$categoryKey]++;
                                            $selected_student_id[] = [
                                                'counselling_id' => $counsellingId,
                                                'registration_id' => $value->id,
                                                'category' => $categoryKey,
                                                'subject' => $preference,
                                                'physical_disable' => 'No'
                                            ];
                                            break;
                                        }
                                    }
                                } else {
                                    if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']) {
                                        $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']) {
                                        $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']) {
                                        $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']) {
                                        $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                                    } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                                    }

                                    // BA STUDENT
                                    if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                                        $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']) {
                                        $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']) {
                                        $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']) {
                                        $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                                    } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                                    }
                                }
                            }
                        }
                    }

                    ini_set("xdebug.var_display_max_children", '-1');
                    ini_set("xdebug.var_display_max_data", '-1');
                    ini_set("xdebug.var_display_max_depth", '-1');

                    // var_dump($selected_student);
                    // var_dump($selected_student_id);
                    // exit;
                    $bsc_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND id NOT IN (SELECT student_counselling.registration_id FROM student_counselling) AND course IN ('ITEP - B.Sc. B.Ed.', 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC");
                    // var_dump('Counselling Count: ' . count($bsc_pwd_counselling));

                    $selected_bsc_physical = 0;
                    $selected_ba_physical = 0;
                    $selected_bsc_pwd_general = 0;
                    $selected_ba_pwd_general = 0;
                    foreach ($bsc_pwd_counselling as $key => $value) {
                        if ($value->course == 'ITEP - B.Sc. B.Ed.') {
                            if ($selected_bsc_physical < $total_bsc_physical) {
                                // var_dump($key .' ---- '. $selected_bsc_physical .' --- '.$value->id);
                                // Extract registration_ids from existing array
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    // var_dump('Enter');
                                    if (!empty($value->bsc_preference_1)) {
                                        if ($selected_bsc_pwd_general < $bsc_pwd_general) {
                                            if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                            } else {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;

                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }
                                                if (!empty($idx)) {
                                                    unset($selected_student_id[$idx]);
                                                }

                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                            }
                                            $selected_bsc_pwd_general++;
                                            $selected_bsc_physical++;
                                        } else {
                                            if (strtolower($value->category) != 'general') {
                                                // var_dump('Category Entr');
                                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                                } else {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }
                                                    if (!empty($idx)) {
                                                        unset($selected_student_id[$idx]);
                                                    }

                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                                }
                                                $selected_bsc_physical++;
                                            }
                                        }
                                    }
                                }
                            } else {
                                break;
                            }
                        }

                        if ($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') {
                            if ($selected_bsc_physical < $total_bsc_physical) {
                                // Extract registration_ids from existing array
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    if (!empty($value->bsc_preference_1)) {
                                        if ($selected_bsc_pwd_general < $bsc_pwd_general) {
                                            if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                            } else {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;

                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }
                                                if (!empty($idx)) {
                                                    unset($selected_student_id[$idx]);
                                                }

                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                            }
                                            $selected_bsc_pwd_general++;
                                            $selected_bsc_physical++;
                                        } else {
                                            if (strtolower($value->category) != 'general') {
                                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                                } else {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;

                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }
                                                    if (!empty($idx)) {
                                                        unset($selected_student_id[$idx]);
                                                    }
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                                }
                                                $selected_bsc_physical++;
                                            }
                                        }
                                    }
                                }
                            }

                            if ($selected_ba_physical < $total_ba_physical) {
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    if (!empty($value->ba_preference_1)) {
                                        if ($selected_ba_pwd_general < $ba_pwd_general) {
                                            if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                            } else {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;

                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }

                                                if (!empty($idx)) {
                                                    unset($selected_student_id[$idx]);
                                                }

                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                            }
                                            $selected_ba_pwd_general++;
                                            $selected_ba_physical++;
                                        } else {
                                            if (strtolower($value->category) != 'general') {
                                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                                } else {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;

                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }

                                                    if (!empty($idx)) {
                                                        unset($selected_student_id[$idx]);
                                                    }

                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                                }
                                                $selected_ba_physical++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $ba_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND id NOT IN (SELECT student_counselling.registration_id FROM student_counselling) AND course IN ('ITEP - B.A. B.Ed.', 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC");
                    // var_dump('Counselling Count: ' . count($ba_pwd_counselling));

                    foreach ($ba_pwd_counselling as $key => $value) {
                        if ($value->course == 'ITEP - B.A. B.Ed.') {
                            if ($selected_ba_physical < $total_ba_physical) {
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    if (!empty($value->ba_preference_1)) {
                                        if ($selected_ba_pwd_general < $ba_pwd_general) {
                                            if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                            } else {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;

                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }

                                                if (!empty($idx)) {
                                                    unset($selected_student_id[$idx]);
                                                }

                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                            }
                                            $selected_ba_pwd_general++;
                                            $selected_ba_physical++;
                                        } else {
                                            if (strtolower($value->category) != 'general') {
                                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                                } else {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;

                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }

                                                    if (!empty($idx)) {
                                                        unset($selected_student_id[$idx]);
                                                    }

                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                                }
                                                $selected_ba_physical++;
                                            }
                                        }
                                    }
                                }
                            } else {
                                break;
                            }
                        }

                        if ($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') {
                            if ($selected_bsc_physical < $total_bsc_physical) {
                                // Extract registration_ids from existing array
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    if (!empty($value->bsc_preference_1)) {
                                        if ($selected_bsc_pwd_general < $bsc_pwd_general) {
                                            if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                            } else {
                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;

                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }
                                                if (!empty($idx)) {
                                                    unset($selected_student_id[$idx]);
                                                }

                                                $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                            }
                                            $selected_bsc_pwd_general++;
                                            $selected_bsc_physical++;
                                        } else {
                                            if (strtolower($value->category) != 'general') {
                                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                                } else {
                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;

                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }
                                                    if (!empty($idx)) {
                                                        unset($selected_student_id[$idx]);
                                                    }

                                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                                }
                                                $selected_bsc_physical++;
                                            }
                                        }
                                    }
                                }
                            }

                            if ($selected_ba_physical < $total_ba_physical) {
                                $existingIds = array_column($selected_student_id, 'registration_id');
                                // Check if registration_id already exists
                                if (!in_array($value->id, $existingIds)) {
                                    if (!empty($value->ba_preference_1)) {
                                        if ($selected_ba_pwd_general < $ba_pwd_general) {
                                            if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                            } else {
                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;

                                                $idx = '';
                                                foreach ($selected_student_id as $key => $item) {
                                                    if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                        $idx = $key;
                                                    }
                                                }

                                                if (!empty($idx)) {
                                                    unset($selected_student_id[$idx]);
                                                }

                                                $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                            }
                                            $selected_ba_pwd_general++;
                                            $selected_ba_physical++;
                                        } else {
                                            if (strtolower($value->category) != 'general') {
                                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                                } else {
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
                                                    $idx = '';
                                                    foreach ($selected_student_id as $key => $item) {
                                                        if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                            $idx = $key;
                                                        }
                                                    }

                                                    if (!empty($idx)) {
                                                        unset($selected_student_id[$idx]);
                                                    }
                                                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                                }
                                                $selected_ba_physical++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }


                    $studentCounsellingModel->insertBatch($selected_student_id);

                    $subject_array = ['Physics', 'Chemistry', 'Mathematics', 'Botany', 'Zoology', 'History', 'Geography', 'English Language and Literature', 'Hindi Language and Literature', 'Urdu'];

                    foreach ($subject_array as $value) {
                        $info = $this->subjectPdf($counsellingId, $value);
                        // if (count($info) > 0) {
                            // foreach ($info as $key => $value) {
                            //     $email = \Config\Services::email();
                            //     $from = "no-reply@riea.com";
                            //     $fromName = "RIE Ajmer";

                            //     $msg = "Dear Candidate,<br/><br/>";

                            //     $msg .= '<html lang=en><meta charset=UTF-8><title>ITEP Admission 2025</title><style>body{text-align:center;padding:40px}.content{text-align:left;display:inline-block;max-width:800px;text-align:justify;border:2px solid #000;padding:10px 20px}h2{text-align:center;text-decoration:underline}ul{margin-top:0}</style><div class=content><ol><li>On the basis of your application, you have been provisionally selected for admission to the above programme in this Institute for the academic session 2025-26 (' . $value["subject"] . '). Please log in at <a href=www.riea.in>www.riea.in</a> with your login ID & Password to access all details including fee deposition.<li>In order to confirm your provisional admission you have to <strong>deposit Institute fees on or before 13.07.2025 by 11:59 pm</strong> as given below:-<ul style=list-style-type:disc><li>GENERAL/ OBC/ EWS students: Rs. 7,450/- (without Hostel)<li>SC/ST/PH students: Rs. 4,950/- (without Hostel)<li>GENERAL/ OBC/ EWS students: Rs. 29,650/- (with Hostel)<li>SC/ST/PH students: Rs. 27,150/- (with Hostel)</ul><li>You will only be considered for upward movement as per preferences for <strong>"Major"</strong> subjects in further rounds of counselling on deposition of Institute fees.<li>Your provisional admission will be treated as cancelled if-<ol type=a><li>Any of your documents is found to be forged or false.<li>Any misleading statement or suppression of facts is detected in your application at any time during the session.<li>In case there is incomplete/wrong entry in the marks obtained in the qualifying examination filled online by the applicant in the NCET 2025 application form.<li>If the requisite fees is not deposited within the prescribed time.<li>If your conduct in and outside the Institute during the session is found to be unsatisfactory.</ol><li>Following documents are to be produced in original at the time of physical reporting in the Institute on 28.07.2025 at 10:00 am in Room No.126 at RIE, Ajmer.<ol type=i><li>NCET-2025 Online Application Form and NTA - Score Card<li>Secondary Examination Mark Sheet/Secondary Examination Certificate (for Date of Birth).<li>Mark sheet of qualifying examination and other mark sheets, if any.<li>As per rules issued valid category certificate (SC/ST/OBC/EWS if required), OBC certificate must necessarily show that the applicant does not belong to the Creamy Layer<li>Disability Certificate (if required).<li>Transfer Certificate and Character Certificate of last School/College attended.<li>Certificate issued by the authorized Medical Officer as per the format available in your login document section.<li>The candidate has to submit a declaration/commitment signed by himself and the parents/guardian in the format available in your login document section.<li>The candidate will have to submit an undertaking signed by himself and the parent/guardian that if semester wise prescribed attendance is not completed in the Institute, the admission of the candidate in the hostel or the Institute or both can be cancelled.<li>Student and Parents/Guardian have to submit respective undertakings for the declaration of Anti-Ragging as per the format available in your login document section.<li>It will be mandatory to submit the police verification certificate of the student issued by the Police Department to the effect that no case is pending against the student concerned.<li>It is mandatory to submit the Income Certificate of the financial year 2024-25 of the total family (mother and father) which has been issued on or after 01.04.2025. In case the mother is a housewife, an affidavit on a ten rupee stamp has to be submitted by the father stating that my wife is a housewife and is completely dependent on the husband&apos;s income. This affidavit has to be submitted signed by a notary.<li>Five photographs of the student (when attending the Institute).</ol><li>Please pay special attention to the following points-<ol type=i><li>As per the undertaking/commitment prescribed for the students and parents, they are expected to go through the UGC Regulations on Controlling the menace of Ragging 2009 thoroughly available on the website of Regional Institute of Education, Ajmer.<li>After admission in the Institute, it is mandatory for the student to get himself/herself registered on the MoE Anti Ragging Portal and its URID number will be made available to the institute.</ol></ol><p><strong>NOTE - In case of any query the candidates may contact on helpline No. 0145-2643760 and e-mail ID <a href=mailto:helpitepadmission@rieajmer.ac.in>helpitepadmission@rieajmer.ac.in</a>.</strong><p style=text-align:start><a href=https://demo.riea.in/public/Documents-proforma-online-counselling.pdf>Click Here</a> to Download Counselling Form.<p style=text-align:right;margin-top:15px><strong>Academic Section<br>R.I.E., Ajmer</strong></div>';

                            //     $subject = "Admission in 4-Year " . $value['course'] . " (" . $value["subject"] . ") for the session 2025-26 regarding";

                            //     $email->setFrom($from, $fromName);
                            // $email->setTo($value['email']);
                            // $email->setBCC('abhishek.sharma@ibirdsservices.com');
                            //     // $email->setTo("abhishek.sharma@ibirdsservices.com");

                            //     $email->setSubject($subject);
                            //     $email->setMessage($msg);
                            //     $email->attach('https://demo.riea.in/public/subject_wise_lists/' . str_replace(" ", "_", $value['subject']) . '_' . $counsellingId .'.pdf');

                            //     // $mail = $email->send();

                            //     // if ($mail == true) {
                            //     //     echo json_encode(['message' => 'Counselling Mail Sent Successfully.', 'success' => true]);
                            //     // } else {
                            //     //     // print_r($email->printDebugger(['headers']));exit;
                            //     //     echo json_encode(['message' => 'Something went wrong', 'success' => false]);
                            //     // }
                            //     $email->clear(true);
                            // }
                        // }
                    }
                    $data['success_message'] = "Counselling session created successfully";
                } else {
                    $data['error_message'] = "Error creating couselling session";
                }
            }

            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function counsellingWiseStudentList($id)
    {
        try {
            $counsellingModel = new CounsellingModel();

            $data['pageTitle'] = "Counselling";
            $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));

            $data['fees'] = 'with fees';
            $input = $this->request->getVar();
            if (isset($input['fees']) && !empty($input['fees'])) {
                if ($input['fees'] == 'with fees') {
                    $data['fees'] = 'with fees';
                } else if ($input['fees'] == 'without fees') {
                    $data['fees'] = 'without fees';
                } else {
                    $data['fees'] = 'all';
                }
            }

            $data['id'] = $id;
            $data['records'] = $counsellingModel->getCounsellingWiseStudentList($id, $data['fees']);

            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/counselling/counselling_wise_student_list", $data) . view('admin/template/footer');
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function counsellingWiseStudentDetail($id)
    {
        try {
            $counsellingModel = new CounsellingModel();
            $ncetScoreModel = new NcetScoreModel();

            $data = [];
            $data['details'] = $counsellingModel->getCounsellingStudentDetail($id);
            unset($data['details']->password);
            $data['ncet'] = $ncetScoreModel->getNcetScoreByRegistrationId($data['details']->id);

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

    public function rejectCounselling($id)
    {
        try {
            $studentCounsellingModel = new StudentCounsellingModel();
            $result = $studentCounsellingModel->set('status', 'Reject')->where('id', $id)->update();

            if ($result) {
                echo json_encode(['status' => 200, 'message' => 'Counselling Rejected Successfully']);
            } else {
                echo json_encode(['status' => 400, 'message' => 'Failed to Reject Counselling']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function acceptCounselling($id)
    {
        try {
            $studentCounsellingModel = new StudentCounsellingModel();
            $itepMatrixModel = new ITEPSeatMatrixModel();
            $itepResult = false;

            $data = $this->request->getVar();
            // var_dump($data);

            if ($data->physical_disable == 'Yes') {
                if ($data->course == 'ITEP - B.Sc. B.Ed.') {
                    $itepMatrixModel->set('pwd_used', 'pwd_used + 1', false)->set('pwd_available', 'pwd_available - 1', false)->where('course', 'B.Sc. B.Ed.')->update();
                } else {
                    $itepMatrixModel->set('pwd_used', 'pwd_used + 1', false)->set('pwd_available', 'pwd_available - 1', false)->where('course', 'B.A. B.Ed.')->update();
                }
            }

            if ($data->category == 'general') {
                $itepResult = $itepMatrixModel->set('general_used', 'general_used + 1', false)->set('general_available', 'general_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            } else if ($data->category == strtolower('OBC-(NCL)')) {
                $itepResult = $itepMatrixModel->set('obc_ncl_used', 'obc_ncl_used + 1', false)->set('obc_ncl_available', 'obc_ncl_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            } else if ($data->category == strtolower('SC')) {
                $itepResult = $itepMatrixModel->set('sc_used', 'sc_used + 1', false)->set('sc_available', 'sc_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            } else if ($data->category == strtolower('ST')) {
                $itepResult = $itepMatrixModel->set('st_used', 'st_used + 1', false)->set('st_available', 'st_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            } else  if ($data->category == strtolower('EWS')) {
                $itepResult = $itepMatrixModel->set('ews_used', 'ews_used + 1', false)->set('ews_available', 'ews_available - 1', false)->where('disciplinary_major', $data->subject)->update();
            }
            // var_dump($result);
            // $itepMatrixModel->set()
            $result = false;
            if ($itepResult) {
                $result = $studentCounsellingModel->set('status', 'Accept')->where('id', $id)->update();
            }

            if ($result) {
                echo json_encode(['status' => 200, 'message' => 'Counselling Accepted Successfully']);
            } else {
                echo json_encode(['status' => 400, 'message' => 'Failed to Accept Counselling']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 500, 'message' => $e->getTrace()]);
        }
    }

    public function sendEmailToCounsellingStudents()
    {
        try {
            $studentCounsellingModel = new StudentCounsellingModel();

            $result = $studentCounsellingModel->getStudentCounsellingListWithoutFeesPay();
            foreach ($result as $key => $value) {
                // var_dump($value['email']);
                $email = \Config\Services::email();
                $from = "no-reply@riea.com";
                $fromName = "RIE Ajmer";

                $msg = "Dear candidate,<br/><br/>";

                $msg .= "Please pay the Institute fees by logging on ITEP Admission Portal of RIE, Ajmer using your login Id and password. Link to pay fees is <a href='https://riea.in/'>Click Here</a>";

                $msg .= "<br/><br/><b>Academic Section</b> <br/>";
                $msg .= "<b>RIE, Ajmer</b>";

                $subject = "Information to pay Institute fees for admission in ITEP";

                $email->setFrom($from, $fromName);
                $email->setTo($value['email']);
                $email->setBCC('abhishek.sharma@ibirdsservices.com');

                $email->setSubject($subject);
                $email->setMessage($msg);

                $mail = $email->send();

                if ($mail == true) {
                    echo json_encode(['message' => 'Counselling Mail Sent Successfully.', 'success' => true]);
                } else {
                    // print_r($email->printDebugger(['headers']));exit;
                    echo json_encode(['message' => 'Something went wrong', 'success' => false]);
                }
                $email->clear(true);
            }
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }
    public function subjectWiseStudentList()
    {
        try {
            $studentCounsellingModel = new StudentCounsellingModel();
            $counsellingModel = new CounsellingModel();

            $data['pageTitle'] = "Subject Wise Student List";
            $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));

            $data['counselling_list'] = $counsellingModel->fetchAll();
            $input = $this->request->getVar();
            $data['subject'] = '';
            $data['counsellingId'] = '';
            $subject = '';
            $counsellingId = '';

            if (count($input)) {
                $subject = $input['subject'];
                $counsellingId = $input['counsellingId'];
            }

            if (isset($_SESSION['subject']) && $_SESSION['subject'] != '') {
                $subject = session()->get('subject');
            }

            if ($subject != '' && $counsellingId != '') {
                // $subject = $input['subject'];
                $data['subject'] = $subject;
                $data['counsellingId'] = $counsellingId;
                $result = $studentCounsellingModel->getSubjectWiseStudentList($counsellingId, $subject);

                foreach ($result as $value) {
                    if ($value['student_counselling_category'] ==  'obc-(ncl)') {
                        $data['obc_ncl'][] = $value;
                    } else {
                        $data[strtolower($value['student_counselling_category'])][] = $value;
                    }
                    $data['info'][] = ['subject' => $value['student_counselling_subject'], 'email' => $value['email'], 'course' => $value['course']];
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

    public function subjectPdf($counsellingId = 7, $subject = 'Physics')
    {
        try {
            $studentCounsellingModel = new StudentCounsellingModel();
            $itepMatrixModel = new ITEPSeatMatrixModel();
            $commonModel = new CommonModel();

            $result = $studentCounsellingModel->getSubjectWiseStudentList($counsellingId, $subject);
            $matrixResult = $itepMatrixModel->fetchBySubject($subject);

            $info = [];

            // var_dump($result);exit;
            $data = [];
            if (count($result) > 0) {
                foreach ($result as $value) {
                    if ($value['student_counselling_category'] ==  'obc-(ncl)') {
                        $data['obc_ncl'][] = $value;
                    } else {
                        $data[$value['student_counselling_category']][] = $value;
                    }
                    $info[] = ['subject' => $value['student_counselling_subject'], 'email' => $value['email'], 'course' => $value['course']];
                }
                // var_dump($data);exit;

                // 1. Create your HTML content as a string
                $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Selected Candidate List - (' . $subject . ')</title>
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
            <div class="subtitle">Class : 4-Year ITEP ' . $matrixResult['course'] . ' Session : 2025-26</div>
                <div class="list-title">Provisional Selected Candidate List in 3<sup>rd</sup> round of Counselling - (' . $subject . ')</div>
            <div class="categories">
                    <span>(General - <strong>' . $matrixResult['general_available'] . '</strong></span> |
                    <span>OBC-NCL - <strong>' . $matrixResult['obc_ncl_available'] . '</strong></span> |
                    <span>SC - <strong>' . $matrixResult['sc_available'] . '</strong></span> |
                    <span>ST - <strong>' . $matrixResult['st_available'] . '</strong></span> |
                    <span>EWS - <strong>' . $matrixResult['ews_available'] . '</strong>)</span>
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
                if (isset($data['general']) && count($data['general']) > 0) {
                    foreach ($data['general'] as $key => $value) {
                        $html .= '<tr>
                    <td>' . $value["ncet_application_no"] . '</td>
                        <td>' . $value["name"] . (($subject == 'Urdu' && $key > 1) ? ' <span style="color: red;font-size: 14px;">*</span>' : '') . '</td>
                    <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                    <td>' . $value["student_counselling_subject"] . '</td>
                    <td>' . $value["ncet_average_percentile"] . '</td>';

                        if ($matrixResult['course'] == 'B.Sc. B.Ed.') {
                            $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                        } else if ($matrixResult['course'] == 'B.A. B.Ed.') {
                            $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                        }

                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
                }
                $html .= '</tbody></table>';

                if ($subject == 'Urdu' && $key > 1) {
                    $html .= '<div style="text-align:left;"><p style="font-weight: bold;">
                <span style="color: red;font-size: 14px;">*</span> Admitted against vacant SC / OBC-(NCL) quota seat.</p>
                </div>';
                }
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
                if (isset($data['obc_ncl']) && count($data['obc_ncl']) > 0) {
                    foreach ($data['obc_ncl'] as $key => $value) {
                        $html .= '<tr>
                <td>' . $value["ncet_application_no"] . '</td>
                <td>' . $value["name"] . '</td>
                <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                <td>' . $value["student_counselling_subject"] . '</td>
                <td>' . $value["ncet_average_percentile"] . '</td>';

                        if ($matrixResult['course'] == 'B.Sc. B.Ed.') {
                            $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                        } else if ($matrixResult['course'] == 'B.A. B.Ed.') {
                            $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                        }

                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
                }
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
                if (isset($data['sc']) && count($data['sc']) > 0) {
                    foreach ($data['sc'] as $key => $value) {
                        $html .= '<tr>
                <td>' . $value["ncet_application_no"] . '</td>
                <td>' . $value["name"] . '</td>
                <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                <td>' . $value["student_counselling_subject"] . '</td>
                <td>' . $value["ncet_average_percentile"] . '</td>';

                        if ($matrixResult['course'] == 'B.Sc. B.Ed.') {
                            $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                        } else if ($matrixResult['course'] == 'B.A. B.Ed.') {
                            $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                        }

                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
                }
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
                if (isset($data['st']) && count($data['st']) > 0) {
                    foreach ($data['st'] as $key => $value) {
                        $html .= '<tr>
                <td>' . $value["ncet_application_no"] . '</td>
                <td>' . $value["name"] . '</td>
                <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                <td>' . $value["student_counselling_subject"] . '</td>
                <td>' . $value["ncet_average_percentile"] . '</td>';

                        if ($matrixResult['course'] == 'B.Sc. B.Ed.') {
                            $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                        } else if ($matrixResult['course'] == 'B.A. B.Ed.') {
                            $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                        }

                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
                }
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
                if (isset($data['ews']) && count($data['ews']) > 0) {
                    foreach ($data['ews'] as $key => $value) {
                        $html .= '<tr>
                <td>' . $value["ncet_application_no"] . '</td>
                        <td>' . $value["name"] . (($subject == 'Urdu' && $key > 0) ? ' <span style="color: red;font-size: 14px;">*</span>' : '') . '</td>
                <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                <td>' . $value["student_counselling_subject"] . '</td>
                <td>' . $value["ncet_average_percentile"] . '</td>';

                        if ($matrixResult['course'] == 'B.Sc. B.Ed.') {
                            $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                        } else if ($matrixResult['course'] == 'B.A. B.Ed.') {
                            $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                        }

                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
                }
                $html .= '</tbody></table>';

                if ($subject == 'Urdu' && $key > 0) {
                    $html .= '<div style="text-align:left;"><p style="font-weight: bold;">
                <span style="color: red;font-size: 14px;">*</span> Admitted against vacant SC / OBC-(NCL) quota seat.</p>
                </div>';
                }

                $html .= '</body></html>';

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
                file_put_contents(FCPATH . 'public/subject_wise_lists/' . str_replace(" ", "_", $subject) . '_' . $counsellingId . '.pdf', $output);
                // var_dump($info);
            }
            return $info;
        } catch (Exception $e) {
            var_dump($e->getTrace());
        }
    }

    public function courseWisePdfGenerate($id)
    {
        $studentCounsellingModel = new StudentCounsellingModel();
        $itepMatrixModel = new ITEPSeatMatrixModel();

        $courses = ['ITEP - B.Sc. B.Ed.', 'ITEP - B.A. B.Ed.'];

        foreach ($courses as $course) {
            if ($course == 'ITEP - B.Sc. B.Ed.') {
                $c_name = 'bsc_bed';
            } else if ($course == 'ITEP - B.A. B.Ed.') {
                $c_name = 'ba_bed';
            }
            $result = $studentCounsellingModel->getCourseWiseStudentList($id, $course);
            // $matrixResult = $itepMatrixModel->fetchBySubject($course);

            // $info = [];

            // var_dump($course,$c_name,$result);//exit;
            $data = [];
            foreach ($result as $value) {
                if ($value['student_counselling_category'] ==  'obc-(ncl)') {
                    $data['obc_ncl'][] = $value;
                } else {
                    $data[$value['student_counselling_category']][] = $value;
                }
                // $info[] = ['subject' => $value['student_counselling_subject'], 'email' => $value['email'], 'course'=> $value['course']];
            }
            // var_dump($data);exit;

            // 1. Create your HTML content as a string
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <title>Selected Candidate List - (' . $course . ')</title>
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
                <div class="subtitle">Class : 4-Year ' . $course . ' Session : 2025-26</div>
                <div class="list-title">Provisional Selected Candidate List in 2<sup>nd</sup> round of Counselling - (' . $course . ')</div>
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
            if (isset($data['general']) && count($data['general']) > 0) {
                foreach ($data['general'] as $key => $value) {
                    $html .= '<tr>
                        <td>' . $value["ncet_application_no"] . '</td>
                        <td>' . $value["name"] . '</td>
                        <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                        <td>' . $value["student_counselling_subject"] . '</td>
                        <td>' . $value["ncet_average_percentile"] . '</td>';

                    if ($course == 'ITEP - B.Sc. B.Ed.') {
                        $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                    } else if ($course == 'ITEP - B.A. B.Ed.') {
                        $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                    }

                    $html .= '</tr>';
                }
            } else {
                $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
            }
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
            if (isset($data['obc_ncl']) && count($data['obc_ncl']) > 0) {
                foreach ($data['obc_ncl'] as $key => $value) {
                    $html .= '<tr>
                    <td>' . $value["ncet_application_no"] . '</td>
                    <td>' . $value["name"] . '</td>
                    <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                    <td>' . $value["student_counselling_subject"] . '</td>
                    <td>' . $value["ncet_average_percentile"] . '</td>';

                    if ($course == 'ITEP - B.Sc. B.Ed.') {
                        $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                    } else if ($course == 'ITEP - B.A. B.Ed.') {
                        $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                    }

                    $html .= '</tr>';
                }
            } else {
                $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
            }
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
            if (isset($data['sc']) && count($data['st']) > 0) {
                foreach ($data['sc'] as $key => $value) {
                    $html .= '<tr>
                    <td>' . $value["ncet_application_no"] . '</td>
                    <td>' . $value["name"] . '</td>
                    <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                    <td>' . $value["student_counselling_subject"] . '</td>
                    <td>' . $value["ncet_average_percentile"] . '</td>';

                    if ($course == 'ITEP - B.Sc. B.Ed.') {
                        $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                    } else if ($course == 'ITEP - B.A. B.Ed.') {
                        $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                    }

                    $html .= '</tr>';
                }
            } else {
                $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
            }
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
            if (isset($data['st']) && count($data['st']) > 0) {
                foreach ($data['st'] as $key => $value) {
                    $html .= '<tr>
                    <td>' . $value["ncet_application_no"] . '</td>
                    <td>' . $value["name"] . '</td>
                    <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                    <td>' . $value["student_counselling_subject"] . '</td>
                    <td>' . $value["ncet_average_percentile"] . '</td>';

                    if ($course == 'ITEP - B.Sc. B.Ed.') {
                        $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                    } else if ($course == 'ITEP - B.A. B.Ed.') {
                        $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                    }

                    $html .= '</tr>';
                }
            } else {
                $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
            }
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
            if (isset($data['ews']) && count($data['ews']) > 0) {
                foreach ($data['ews'] as $key => $value) {
                    $html .= '<tr>
                    <td>' . $value["ncet_application_no"] . '</td>
                    <td>' . $value["name"] . '</td>
                    <td>' . $value["category"] . ($value["physical_disable"] == 'Yes' ? ' - PwD' : '') . '</td>
                    <td>' . $value["student_counselling_subject"] . '</td>
                    <td>' . $value["ncet_average_percentile"] . '</td>';

                    if ($course == 'ITEP - B.Sc. B.Ed.') {
                        $html .= '<td>' . $value["bsc_preference_1"] . '</td><td>' . $value["bsc_preference_2"] . '</td><td>' . $value["bsc_preference_3"] . '</td><td>' . $value["bsc_preference_4"] . '</td>';
                    } else if ($course == 'ITEP - B.A. B.Ed.') {
                        $html .= '<td>' . $value["ba_preference_1"] . '</td><td>' . $value["ba_preference_2"] . '</td><td>' . $value["ba_preference_3"] . '</td><td>' . $value["ba_preference_4"] . '</td>';
                    }

                    $html .= '</tr>';
                }
            } else {
                $html .= '<tr><td colspan="9" style="text-align: center;">No Seat Vacant in this Category</td></tr>';
            }
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
            file_put_contents(FCPATH . 'course_wise_lists/' . str_replace(". ", "_", $c_name) . '.pdf', $output);
            // var_dump($info);
            // return $info;
        }

        return redirect()->to('admin/counselling/student-list/' . $id);
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
            if ($value->course == 'ITEP - B.Sc. B.Ed.') {
                if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']) {
                    $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                    // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']) {
                    // $selected_matrix[strtolower($value->bsc_preference_2)]['general'][] = [$value->id, $value->bsc_preference_2, $value->category];
                    $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]) {
                    // $selected_matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_2, $value->category];
                    $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']) {
                    // $selected_matrix[strtolower($value->bsc_preference_3)]['general'][] = [$value->id, $value->bsc_preference_3, $value->category];
                    $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]) {
                    // $selected_matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_3, $value->category];
                    $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']) {
                    // $selected_matrix[strtolower($value->bsc_preference_4)]['general'][] = [$value->id, $value->bsc_preference_4, $value->category];
                    $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]) {
                    // $selected_matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_4, $value->category];
                    $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                }
            }

            if ($value->course == 'ITEP - B.A. B.Ed.') {
                if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_1)]['general'][] = [$value->id, $value->ba_preference_1, $value->category];
                    $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_2)]['general'][] = [$value->id, $value->ba_preference_2, $value->category];
                    $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_2)][strtolower($value->category)][] = [$value->id, $value->ba_preference_2, $value->category];
                    $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_3)]['general'][] = [$value->id, $value->ba_preference_3, $value->category];
                    $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_3)][strtolower($value->category)][] = [$value->id, $value->ba_preference_3, $value->category];
                    $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_4)]['general'][] = [$value->id, $value->ba_preference_4, $value->category];
                    $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_4)][strtolower($value->category)][] = [$value->id, $value->ba_preference_4, $value->category];
                    $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                }
            }

            if ($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') {
                // var_dump($value->course.' - '. $value->id);
                if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_1)]['general'][] = [$value->id, $value->bsc_preference_1, $value->category];
                    $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_1, $value->category];
                    $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_2)]['general'][] = [$value->id, $value->bsc_preference_2, $value->category];
                    $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_2, $value->category];
                    $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_3)]['general'][] = [$value->id, $value->bsc_preference_3, $value->category];
                    $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_3, $value->category];
                    $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_4)]['general'][] = [$value->id, $value->bsc_preference_4, $value->category];
                    $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->bsc_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)][] = [$value->id, $value->bsc_preference_4, $value->category];
                    $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                }

                // BA STUDENT
                if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_1)]['general'][] = [$value->id, $value->ba_preference_1, $value->category];
                    $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_1, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_1)][strtolower($value->category)][] = [$value->id, $value->ba_preference_1, $value->category];
                    $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_2)]['general'][] = [$value->id, $value->ba_preference_2, $value->category];
                    $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_2, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_2)][strtolower($value->category)][] = [$value->id, $value->ba_preference_2, $value->category];
                    $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_3)]['general'][] = [$value->id, $value->ba_preference_3, $value->category];
                    $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_3, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_3)][strtolower($value->category)][] = [$value->id, $value->ba_preference_3, $value->category];
                    $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']) {
                    // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_4)]['general'][] = [$value->id, $value->ba_preference_4, $value->category];
                    $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]) {
                    // $selected_student[] = [$value->id, $value->ba_preference_4, $value->category];
                    // $selected_matrix[strtolower($value->ba_preference_4)][strtolower($value->category)][] = [$value->id, $value->ba_preference_4, $value->category];
                    $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
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
            if ($value->course == 'ITEP - B.Sc. B.Ed.') {
                if ($selected_bsc_physical < $total_bsc_physical) {
                    // var_dump($key .' ---- '. $selected_bsc_physical .' --- '.$value->id);
                    // Extract registration_ids from existing array
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        // var_dump('Enter');
                        if (!empty($value->bsc_preference_1)) {
                            if ($selected_bsc_pwd_general < $bsc_pwd_general) {
                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                } else {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;

                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }
                                    if (!empty($idx)) {
                                        unset($selected_student_id[$idx]);
                                    }

                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                }
                                $selected_bsc_pwd_general++;
                                $selected_bsc_physical++;
                            } else {
                                if (strtolower($value->category) != 'general') {
                                    // var_dump('Category Entr');
                                    if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;

                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }

                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_bsc_physical++;
                                }
                            }
                        }
                    }
                } else {
                    break;
                }
            }

            if ($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') {
                if ($selected_bsc_physical < $total_bsc_physical) {
                    // Extract registration_ids from existing array
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->bsc_preference_1)) {
                            if ($selected_bsc_pwd_general < $bsc_pwd_general) {
                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                } else {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;

                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }
                                    if (!empty($idx)) {
                                        unset($selected_student_id[$idx]);
                                    }

                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                }
                                $selected_bsc_pwd_general++;
                                $selected_bsc_physical++;
                            } else {
                                if (strtolower($value->category) != 'general') {
                                    if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;

                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }

                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_bsc_physical++;
                                }
                            }
                        }
                    }
                }

                if ($selected_ba_physical < $total_ba_physical) {
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->ba_preference_1)) {
                            if ($selected_ba_pwd_general < $ba_pwd_general) {
                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                } else {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;

                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }

                                    if (!empty($idx)) {
                                        unset($selected_student_id[$idx]);
                                    }

                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                }
                                $selected_ba_pwd_general++;
                                $selected_ba_physical++;
                            } else {
                                if (strtolower($value->category) != 'general') {
                                    if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;

                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }

                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }

                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
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
            if ($value->course == 'ITEP - B.A. B.Ed.') {
                if ($selected_ba_physical < $total_ba_physical) {
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->ba_preference_1)) {
                            if ($selected_ba_pwd_general < $ba_pwd_general) {
                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                } else {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;

                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }

                                    if (!empty($idx)) {
                                        unset($selected_student_id[$idx]);
                                    }

                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                }
                                $selected_ba_pwd_general++;
                                $selected_ba_physical++;
                            } else {
                                if (strtolower($value->category) != 'general') {
                                    if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;

                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }

                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }

                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_ba_physical++;
                                }
                            }
                        }
                    }
                } else {
                    break;
                }
            }

            if ($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') {
                if ($selected_bsc_physical < $total_bsc_physical) {
                    // Extract registration_ids from existing array
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->bsc_preference_1)) {
                            if ($selected_bsc_pwd_general < $bsc_pwd_general) {
                                if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                } else {
                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;

                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }
                                    if (!empty($idx)) {
                                        unset($selected_student_id[$idx]);
                                    }

                                    $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                }
                                $selected_bsc_pwd_general++;
                                $selected_bsc_physical++;
                            } else {
                                if (strtolower($value->category) != 'general') {
                                    if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;

                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }

                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_bsc_physical++;
                                }
                            }
                        }
                    }
                }

                if ($selected_ba_physical < $total_ba_physical) {
                    $existingIds = array_column($selected_student_id, 'registration_id');
                    // Check if registration_id already exists
                    if (!in_array($value->id, $existingIds)) {
                        if (!empty($value->ba_preference_1)) {
                            if ($selected_ba_pwd_general < $ba_pwd_general) {
                                if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                } else {
                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;

                                    $idx = '';
                                    foreach ($selected_student_id as $key => $item) {
                                        if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                            $idx = $key;
                                        }
                                    }

                                    if (!empty($idx)) {
                                        unset($selected_student_id[$idx]);
                                    }

                                    $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                    $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                }
                                $selected_ba_pwd_general++;
                                $selected_ba_physical++;
                            } else {
                                if (strtolower($value->category) != 'general') {
                                    if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;

                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }

                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }

                                        $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_ba_physical++;
                                }
                            }
                        }
                    }
                }
            }
        }

        var_dump($selected_student);
        var_dump(($selected_student_id));
        // var_dump($bsc_pwd_counselling, $ba_pwd_counselling);
        var_dump($selected_bsc_physical,   $selected_ba_physical,   $selected_bsc_pwd_general,   $selected_ba_pwd_general);
    }

    public function calculateCounselling2()
    {
        try{
            $itepMatrixModel = new ITEPSeatMatrixModel();
            $counsellingModel = new CounsellingModel();
            $studentCounsellingModel = new StudentCounsellingModel();
    
            $counsellingId = 2;
    
            $matrixResult = $itepMatrixModel->fetchAll();
    
            $acceptStudentList = $studentCounsellingModel->getAcceptedStudentCounsellingList();
    
            $matrix = $matrixResult[0];
            $total_bsc_physical = $matrixResult[1];
            $total_ba_physical = $matrixResult[2];
            $bsc_pwd_general = $matrixResult[3];
            $ba_pwd_general = $matrixResult[4];
    
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
    
            $selected_student_id = [];
            $skipedStudent = ['255110017531'];
    
            $counselling = [];
    
            array_walk($acceptStudentList, function ($value) use (&$counselling) {
                $counselling[] = $value;
            });
    
            $counsel = $counsellingModel->getCounsellingStudentList(" AND id NOT IN (SELECT student_counselling.registration_id FROM student_counselling) ORDER BY ncet_average_percentile DESC");
    
            array_walk($counsel, function ($value) use (&$counselling) {
                $counselling[] = $value;
            });
    
            usort($counselling, function ($a, $b) {
                return (float)$b->ncet_average_percentile <=> (float)$a->ncet_average_percentile;
            });
    
            // var_dump($counselling);exit;
            foreach ($counselling as $key => $value) {
                if (!in_array($value->ncet_application_no, $skipedStudent)) {
                    if ($value->course == 'ITEP - B.Sc. B.Ed.') {
                        if (isset($value->student_counselling_subject) && !empty($value->student_counselling_subject)) {
                            $bsc_preferences = [
                                $value->bsc_preference_1,
                                $value->bsc_preference_2,
                                $value->bsc_preference_3,
                                $value->bsc_preference_4
                            ];
    
                            $currentIndex = $this->getPreferenceIndex($value->student_counselling_subject, $bsc_preferences);
                            foreach ($bsc_preferences as $index => $preference) {
                                $subjectKey = strtolower($preference);
                                $categoryKey = strtolower($value->category);
    
                                if (empty($preference) || $index >= $currentIndex) continue; // skip same or lower preference
    
                                // General category allotment
                                if ($selected_student[$subjectKey]['general'] < $matrix[$subjectKey]['general']) {
                                    $selected_student[$subjectKey]['general']++;
                                    $selected_student_id[] = [
                                        'counselling_id' => $counsellingId,
                                        'registration_id' => $value->id,
                                        'category' => 'general',
                                        'subject' => $preference,
                                        'physical_disable' => 'No'
                                    ];
                                    break; // Stop at first valid upward allotment
                                }
                                // Reserved category allotment
                                if ($selected_student[$subjectKey][$categoryKey] < $matrix[$subjectKey][$categoryKey]) {
                                    $selected_student[$subjectKey][$categoryKey]++;
                                    $selected_student_id[] = [
                                        'counselling_id' => $counsellingId,
                                        'registration_id' => $value->id,
                                        'category' => $categoryKey,
                                        'subject' => $preference,
                                        'physical_disable' => 'No'
                                    ];
                                    break;
                                }
                            }
                        } else {
                            if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']) {
                                $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']) {
                                $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']) {
                                $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']) {
                                $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                            }
                        }
                    }
    
                    if ($value->course == 'ITEP - B.A. B.Ed.') {
                        if (isset($value->student_counselling_subject) && !empty($value->student_counselling_subject)) {
                            $ba_preferences = [
                                $value->ba_preference_1,
                                $value->ba_preference_2,
                                $value->ba_preference_3,
                                $value->ba_preference_4
                            ];
    
                            $currentIndex = $this->getPreferenceIndex($value->student_counselling_subject, $ba_preferences);
                            foreach ($ba_preferences as $index => $preference) {
                                $subjectKey = strtolower($preference);
                                $categoryKey = strtolower($value->category);
    
                                if (empty($preference) || $index >= $currentIndex) continue; // skip same or lower preference
    
                                // General category allotment
                                if ($selected_student[$subjectKey]['general'] < $matrix[$subjectKey]['general']) {
                                    $selected_student[$subjectKey]['general']++;
                                    $selected_student_id[] = [
                                        'counselling_id' => $counsellingId,
                                        'registration_id' => $value->id,
                                        'category' => 'general',
                                        'subject' => $preference,
                                        'physical_disable' => 'No'
                                    ];
                                    break; // Stop at first valid upward allotment
                                }
                                // Reserved category allotment
                                if ($selected_student[$subjectKey][$categoryKey] < $matrix[$subjectKey][$categoryKey]) {
                                    $selected_student[$subjectKey][$categoryKey]++;
                                    $selected_student_id[] = [
                                        'counselling_id' => $counsellingId,
                                        'registration_id' => $value->id,
                                        'category' => $categoryKey,
                                        'subject' => $preference,
                                        'physical_disable' => 'No'
                                    ];
                                    break;
                                }
                            }
                        } else {
                            if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                                $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']) {
                                $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']) {
                                $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']) {
                                $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                            }
                        }
                    }
    
                    if ($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') {
                        if (isset($value->student_counselling_subject) && !empty($value->student_counselling_subject)) {
                            // Example for B.Sc. B.Ed.
                            $bsc_preferences = [
                                $value->bsc_preference_1,
                                $value->bsc_preference_2,
                                $value->bsc_preference_3,
                                $value->bsc_preference_4
                            ];
    
                            $ba_preferences = [
                                $value->ba_preference_1,
                                $value->ba_preference_2,
                                $value->ba_preference_3,
                                $value->ba_preference_4
                            ];
    
                            $currentIndex = $this->getPreferenceIndex($value->student_counselling_subject, $bsc_preferences);
                            foreach ($bsc_preferences as $index => $preference) {
                                $subjectKey = strtolower($preference);
                                $categoryKey = strtolower($value->category);
    
                                if (empty($preference) || $index >= $currentIndex) continue; // skip same or lower preference
    
                                // General category allotment
                                if ($selected_student[$subjectKey]['general'] < $matrix[$subjectKey]['general']) {
                                    $selected_student[$subjectKey]['general']++;
                                    $selected_student_id[] = [
                                        'counselling_id' => $counsellingId,
                                        'registration_id' => $value->id,
                                        'category' => 'general',
                                        'subject' => $preference,
                                        'physical_disable' => 'No'
                                    ];
                                    break; // Stop at first valid upward allotment
                                }
    
                                // Reserved category allotment
                                if ($selected_student[$subjectKey][$categoryKey] < $matrix[$subjectKey][$categoryKey]) {
                                    $selected_student[$subjectKey][$categoryKey]++;
                                    $selected_student_id[] = [
                                        'counselling_id' => $counsellingId,
                                        'registration_id' => $value->id,
                                        'category' => $categoryKey,
                                        'subject' => $preference,
                                        'physical_disable' => 'No'
                                    ];
                                    break;
                                }
                            }
    
                            $currentIndex = $this->getPreferenceIndex($value->student_counselling_subject, $ba_preferences);
                            foreach ($ba_preferences as $index => $preference) {
                                $subjectKey = strtolower($preference);
                                $categoryKey = strtolower($value->category);
    
                                if (empty($preference) || $index >= $currentIndex) continue; // skip same or lower preference
    
                                // General category allotment
                                if ($selected_student[$subjectKey]['general'] < $matrix[$subjectKey]['general']) {
                                    $selected_student[$subjectKey]['general']++;
                                    $selected_student_id[] = [
                                        'counselling_id' => $counsellingId,
                                        'registration_id' => $value->id,
                                        'category' => 'general',
                                        'subject' => $preference,
                                        'physical_disable' => 'No'
                                    ];
                                    break; // Stop at first valid upward allotment
                                }
    
                                // Reserved category allotment
                                if ($selected_student[$subjectKey][$categoryKey] < $matrix[$subjectKey][$categoryKey]) {
                                    $selected_student[$subjectKey][$categoryKey]++;
                                    $selected_student_id[] = [
                                        'counselling_id' => $counsellingId,
                                        'registration_id' => $value->id,
                                        'category' => $categoryKey,
                                        'subject' => $preference,
                                        'physical_disable' => 'No'
                                    ];
                                    break;
                                }
                            }
                        } else {
                            if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)]['general']) < $matrix[strtolower($value->bsc_preference_1)]['general']) {
                                $selected_student[strtolower($value->bsc_preference_1)]['general'] = $selected_student[strtolower($value->bsc_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_1) && ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)]['general']) < $matrix[strtolower($value->bsc_preference_2)]['general']) {
                                $selected_student[strtolower($value->bsc_preference_2)]['general'] = $selected_student[strtolower($value->bsc_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_2) && ($selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_2)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_2, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)]['general']) < $matrix[strtolower($value->bsc_preference_3)]['general']) {
                                $selected_student[strtolower($value->bsc_preference_3)]['general'] = $selected_student[strtolower($value->bsc_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_3) && ($selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_3)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_3, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)]['general']) < $matrix[strtolower($value->bsc_preference_4)]['general']) {
                                $selected_student[strtolower($value->bsc_preference_4)]['general'] = $selected_student[strtolower($value->bsc_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                            } else if (!empty($value->bsc_preference_4) && ($selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->bsc_preference_4)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_4, 'physical_disable' => 'No'];
                            }
    
                            // BA STUDENT
                            if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)]['general']) < $matrix[strtolower($value->ba_preference_1)]['general']) {
                                $selected_student[strtolower($value->ba_preference_1)]['general'] = $selected_student[strtolower($value->ba_preference_1)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_1) && ($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)]['general']) < $matrix[strtolower($value->ba_preference_2)]['general']) {
                                $selected_student[strtolower($value->ba_preference_2)]['general'] = $selected_student[strtolower($value->ba_preference_2)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_2) && ($selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_2)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_2)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_2, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)]['general']) < $matrix[strtolower($value->ba_preference_3)]['general']) {
                                $selected_student[strtolower($value->ba_preference_3)]['general'] = $selected_student[strtolower($value->ba_preference_3)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_3) && ($selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_3)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_3)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_3, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)]['general']) < $matrix[strtolower($value->ba_preference_4)]['general']) {
                                $selected_student[strtolower($value->ba_preference_4)]['general'] = $selected_student[strtolower($value->ba_preference_4)]['general'] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => 'general', 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                            } else if (!empty($value->ba_preference_4) && ($selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_4)][strtolower($value->category)]) {
                                $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_4)][strtolower($value->category)] + 1;
                                $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_4, 'physical_disable' => 'No'];
                            }
                        }
                    }
                }
            }
    
            ini_set("xdebug.var_display_max_children", '-1');
            ini_set("xdebug.var_display_max_data", '-1');
            ini_set("xdebug.var_display_max_depth", '-1');
            // var_dump($selected_student);
            // var_dump($selected_student_id);
            // exit;
            $bsc_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND id NOT IN (SELECT student_counselling.registration_id FROM student_counselling) AND course IN ('ITEP - B.Sc. B.Ed.', 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC");
            // var_dump('Counselling Count: ' . count($bsc_pwd_counselling));
    
            $selected_bsc_physical = 0;
            $selected_ba_physical = 0;
            $selected_bsc_pwd_general = 0;
            $selected_ba_pwd_general = 0;
    
            foreach ($bsc_pwd_counselling as $key => $value) {
                if ($value->course == 'ITEP - B.Sc. B.Ed.') {
                    if ($selected_bsc_physical < $total_bsc_physical) {
                        var_dump($key . ' ---- ' . $selected_bsc_physical . ' --- ' . $value->id);
                        // Extract registration_ids from existing array
                        $existingIds = array_column($selected_student_id, 'registration_id');
                        // Check if registration_id already exists
                        if (!in_array($value->id, $existingIds)) {
                            var_dump('Enter');
                            if (!empty($value->bsc_preference_1)) {
                                if ($selected_bsc_pwd_general < $bsc_pwd_general) {
                                    if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;
    
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }
    
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_bsc_pwd_general++;
                                    $selected_bsc_physical++;
                                } else {
                                    if (strtolower($value->category) != 'general') {
                                        var_dump('Category Entr');
                                        if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                            $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                        } else {
                                            $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
    
                                            $idx = '';
                                            foreach ($selected_student_id as $key => $item) {
                                                if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                    $idx = $key;
                                                }
                                            }
                                            if (!empty($idx)) {
                                                unset($selected_student_id[$idx]);
                                            }
    
                                            $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                        }
                                        $selected_bsc_physical++;
                                    }
                                }
                            }
                        }
                    } else {
                        break;
                    }
                }
    
                if ($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') {
                    if ($selected_bsc_physical < $total_bsc_physical) {
                        // Extract registration_ids from existing array
                        $existingIds = array_column($selected_student_id, 'registration_id');
                        // Check if registration_id already exists
                        if (!in_array($value->id, $existingIds)) {
                            if (!empty($value->bsc_preference_1)) {
                                if ($selected_bsc_pwd_general < $bsc_pwd_general) {
                                    if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;
    
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }
    
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_bsc_pwd_general++;
                                    $selected_bsc_physical++;
                                } else {
                                    if (strtolower($value->category) != 'general') {
                                        if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                            $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                        } else {
                                            $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
    
                                            $idx = '';
                                            foreach ($selected_student_id as $key => $item) {
                                                if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                    $idx = $key;
                                                }
                                            }
                                            if (!empty($idx)) {
                                                unset($selected_student_id[$idx]);
                                            }
    
                                            $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                        }
                                        $selected_bsc_physical++;
                                    }
                                }
                            }
                        }
                    }
    
                    if ($selected_ba_physical < $total_ba_physical) {
                        $existingIds = array_column($selected_student_id, 'registration_id');
                        // Check if registration_id already exists
                        if (!in_array($value->id, $existingIds)) {
                            if (!empty($value->ba_preference_1)) {
                                if ($selected_ba_pwd_general < $ba_pwd_general) {
                                    if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;
    
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
    
                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }
    
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_ba_pwd_general++;
                                    $selected_ba_physical++;
                                } else {
                                    if (strtolower($value->category) != 'general') {
                                        if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                            $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                        } else {
                                            $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
    
                                            $idx = '';
                                            foreach ($selected_student_id as $key => $item) {
                                                if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                    $idx = $key;
                                                }
                                            }
    
                                            if (!empty($idx)) {
                                                unset($selected_student_id[$idx]);
                                            }
    
                                            $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                        }
                                        $selected_ba_physical++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
    
            $ba_pwd_counselling = $counsellingModel->getCounsellingStudentList(" AND id NOT IN (SELECT student_counselling.registration_id FROM student_counselling) AND course IN ('ITEP - B.A. B.Ed.', 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') AND physical_disable='Yes' ORDER BY ncet_average_percentile DESC");
            // var_dump('Counselling Count: ' . count($ba_pwd_counselling));
            foreach ($ba_pwd_counselling as $key => $value) {
                if ($value->course == 'ITEP - B.A. B.Ed.') {
                    if ($selected_ba_physical < $total_ba_physical) {
                        $existingIds = array_column($selected_student_id, 'registration_id');
                        // Check if registration_id already exists
                        if (!in_array($value->id, $existingIds)) {
                            if (!empty($value->ba_preference_1)) {
                                if ($selected_ba_pwd_general < $ba_pwd_general) {
                                    if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;
    
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
    
                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }
    
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_ba_pwd_general++;
                                    $selected_ba_physical++;
                                } else {
                                    if (strtolower($value->category) != 'general') {
                                        if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                            $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                        } else {
                                            $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
    
                                            $idx = '';
                                            foreach ($selected_student_id as $key => $item) {
                                                if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                    $idx = $key;
                                                }
                                            }
    
                                            if (!empty($idx)) {
                                                unset($selected_student_id[$idx]);
                                            }
    
                                            $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                        }
                                        $selected_ba_physical++;
                                    }
                                }
                            }
                        }
                    } else {
                        break;
                    }
                }
    
                if ($value->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') {
                    if ($selected_bsc_physical < $total_bsc_physical) {
                        // Extract registration_ids from existing array
                        $existingIds = array_column($selected_student_id, 'registration_id');
                        // Check if registration_id already exists
                        if (!in_array($value->id, $existingIds)) {
                            if (!empty($value->bsc_preference_1)) {
                                if ($selected_bsc_pwd_general < $bsc_pwd_general) {
                                    if ($selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] < $matrix[strtolower($value->bsc_preference_1)][strtolower('general')]) {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] - 1;
    
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }
    
                                        $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] = $selected_student[strtolower($value->bsc_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_bsc_pwd_general++;
                                    $selected_bsc_physical++;
                                } else {
                                    if (strtolower($value->category) != 'general') {
                                        if ($selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] < $matrix[strtolower($value->bsc_preference_1)][strtolower($value->category)]) {
                                            $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                        } else {
                                            $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] - 1;
    
                                            $idx = '';
                                            foreach ($selected_student_id as $key => $item) {
                                                if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->bsc_preference_1) && $item['physical_disable'] == 'No') {
                                                    $idx = $key;
                                                }
                                            }
                                            if (!empty($idx)) {
                                                unset($selected_student_id[$idx]);
                                            }
    
                                            $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->bsc_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->bsc_preference_1, 'physical_disable' => 'Yes'];
                                        }
                                        $selected_bsc_physical++;
                                    }
                                }
                            }
                        }
                    }
    
                    if ($selected_ba_physical < $total_ba_physical) {
                        $existingIds = array_column($selected_student_id, 'registration_id');
                        // Check if registration_id already exists
                        if (!in_array($value->id, $existingIds)) {
                            if (!empty($value->ba_preference_1)) {
                                if ($selected_ba_pwd_general < $ba_pwd_general) {
                                    if (($selected_student[strtolower($value->ba_preference_1)][strtolower('general')]) < $matrix[strtolower($value->ba_preference_1)][strtolower('general')]) {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    } else {
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] - 1;
    
                                        $idx = '';
                                        foreach ($selected_student_id as $key => $item) {
                                            if (strtolower($item['category']) == strtolower('general') && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                $idx = $key;
                                            }
                                        }
    
                                        if (!empty($idx)) {
                                            unset($selected_student_id[$idx]);
                                        }
    
                                        $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] = $selected_student[strtolower($value->ba_preference_1)][strtolower('general')] + 1;
                                        $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower('general'), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                    }
                                    $selected_ba_pwd_general++;
                                    $selected_ba_physical++;
                                } else {
                                    if (strtolower($value->category) != 'general') {
                                        if (($selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)]) < $matrix[strtolower($value->ba_preference_1)][strtolower($value->category)]) {
                                            $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
                                        } else {
                                            $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] - 1;
    
                                            $idx = '';
                                            foreach ($selected_student_id as $key => $item) {
                                                if (strtolower($item['category']) == strtolower($value->category) && strtolower($item['subject']) == strtolower($value->ba_preference_1) && $item['physical_disable'] == 'No') {
                                                    $idx = $key;
                                                }
                                            }
    
                                            if (!empty($idx)) {
                                                unset($selected_student_id[$idx]);
                                            }
    
                                            $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] = $selected_student[strtolower($value->ba_preference_1)][strtolower($value->category)] + 1;
                                            $selected_student_id[] = ['counselling_id' => $counsellingId, 'registration_id' => $value->id, 'category' => strtolower($value->category), 'subject' => $value->ba_preference_1, 'physical_disable' => 'Yes'];
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
            var_dump(($selected_student_id));
            // var_dump($bsc_pwd_counselling, $ba_pwd_counselling);
            // var_dump($selected_bsc_physical,   $selected_ba_physical,   $selected_bsc_pwd_general,   $selected_ba_pwd_general);
        }catch(Exception $e){
            echo "<pre>";print_r($e->getTrace());
        }
    }

    function getPreferenceIndex($subject, $preferences)
    {
        foreach ($preferences as $index => $pref) {
            if (strtolower($pref) == strtolower($subject)) {
                return $index;
            }
        }
        return -1; // subject not found
    }

    public function sendEmailToCounsellingStudentsBySubject()
    {
        try {
            $input = $this->request->getVar();

            $counsellingId = $input['counsellingId'];
            $subject = $input['subject'];

            $studentCounsellingModel = new StudentCounsellingModel();
            $itepMatrixModel = new ITEPSeatMatrixModel();

            $result = $studentCounsellingModel->getSubjectWiseStudentList($counsellingId, $subject);

            // echo json_encode(count($result));

            foreach ($result as $key => $value) {
                $email = \Config\Services::email();
                $from = "no-reply@riea.com";
                $fromName = "RIE Ajmer";

                $msg = "Dear Candidate,<br/><br/>";

                $msg .= '<html lang=en>
                            <meta charset=UTF-8>
                            <title>ITEP Admission 2025</title>
                            <style>
                                body {
                                    text-align: center;
                                    padding: 40px
                                }

                                .content {
                                    text-align: left;
                                    display: inline-block;
                                    max-width: 800px;
                                    text-align: justify;
                                    border: 2px solid #000;
                                    padding: 10px 20px
                                }

                                h2 {
                                    text-align: center;
                                    text-decoration: underline
                                }

                                ul {
                                    margin-top: 0
                                }
                            </style>

                            <body>
                                <div class="content">
                                    <ol>
                                        <li>On the basis of your application, you have been provisionally selected for admission to the above
                                            programme in this Institute for the academic session 2025-26 (' . $value["student_counselling_subject"]
                                            . '). Please log in at <a href=www.riea.in>www.riea.in</a> with your login ID & Password to access all
                                            details including fee deposition.</li>
                                        <li>In order to confirm your provisional admission you have to <strong>deposit Institute fees on or before
                                                17.07.2025 by 11:59 pm</strong> as given below:- </li>
                                        <ul style=list-style-type:disc>
                                            <li>GENERAL/ OBC/ EWS students: Rs. 7,450/- (without Hostel)</li>
                                            <li>SC/ST/PH students: Rs. 4,950/- (without Hostel)</li>
                                            <li>GENERAL/ OBC/ EWS students: Rs. 29,650/- (with Hostel)</li>
                                            <li>SC/ST/PH students: Rs. 27,150/- (with Hostel)</li>
                                            <li>Students who has got upward movement in the <strong>"Major"</strong> subject preference, need not to
                                                re-deposite their admission fees.</li>
                                        </ul>
                                        <li>You will only be considered for upward movement as per preferences for <strong>"Major"</strong> subjects
                                            in further rounds of counselling on deposition of Institute fees.</li>
                                        <li>Your provisional admission will be treated as cancelled if- </li>
                                        <ol type=a>
                                            <li>Any of your documents is found to be forged or false.</li>
                                            <li>Any misleading statement or suppression of facts is detected in your application at any time during
                                                the session.</li>
                                            <li>In case there is incomplete/wrong entry in the marks obtained in the qualifying examination filled
                                                online by the applicant in the NCET 2025 application form.</li>
                                            <li>If the requisite fees is not deposited within the prescribed time.</li>
                                            <li>If your conduct in and outside the Institute during the session is found to be unsatisfactory.</li>
                                        </ol>
                                        <li>Following documents are to be produced in original at the time of physical reporting in the Institute on
                                            28.07.2025 at 10:00 am in Room No.126 at RIE, Ajmer. <ol type=i>
                                                <li>NCET-2025 Online Application Form and NTA - Score Card</li>
                                                <li>Secondary Examination Mark Sheet/Secondary Examination Certificate (for Date of Birth).</li>
                                                <li>Mark sheet of qualifying examination and other mark sheets, if any.</li>
                                                <li>As per rules issued valid category certificate (SC/ST/OBC/EWS if required), OBC certificate must
                                                    necessarily show that the applicant does not belong to the Creamy Layer</li>
                                                <li>Disability Certificate (if required).</li>
                                                <li>Transfer Certificate and Character Certificate of last School/College attended.</li>
                                                <li>Certificate issued by the authorized Medical Officer as per the format available in your login
                                                    document section.</li>
                                                <li>The candidate has to submit a declaration/commitment signed by himself and the parents/guardian
                                                    in the format available in your login document section.</li>
                                                <li>The candidate will have to submit an undertaking signed by himself and the parent/guardian that
                                                    if semester wise prescribed attendance is not completed in the Institute, the admission of the
                                                    candidate in the hostel or the Institute or both can be cancelled.</li>
                                                <li>Student and Parents/Guardian have to submit respective undertakings for the declaration of
                                                    Anti-Ragging as per the format available in your login document section.</li>
                                                <li>It will be mandatory to submit the police verification certificate of the student issued by the
                                                    Police Department to the effect that no case is pending against the student concerned.</li>
                                                <li>It is mandatory to submit the Income Certificate of the financial year 2024-25 of the total
                                                    family (mother and father) which has been issued on or after 01.04.2025. In case the mother is a
                                                    housewife, an affidavit on a ten rupee stamp has to be submitted by the father stating that my
                                                    wife is a housewife and is completely dependent on the husband&apos;s income. This affidavit has
                                                    to be submitted signed by a notary.</li>
                                                <li>Five photographs of the student (when attending the Institute).</li>
                                            </ol>
                                        <li>Please pay special attention to the following points- </li>
                                        <ol type=i>
                                            <li>As per the undertaking/commitment prescribed for the students and parents, they are expected to
                                                go through the UGC Regulations on Controlling the menace of Ragging 2009 thoroughly available on
                                                the website of Regional Institute of Education, Ajmer.
                                            <li>After admission in the Institute, it is mandatory for the student to get himself/herself
                                                registered on the MoE Anti Ragging Portal and its URID number will be made available to the
                                                institute.
                                        </ol>
                                    </ol>
                                    <p>
                                        <strong>NOTE - In case of any query the candidates may contact on helpline No. 0145-2643760 and e-mail ID <a
                                                href=mailto:helpitepadmission@rieajmer.ac.in>helpitepadmission@rieajmer.ac.in</a>. </strong>
                                    </p>
                                    <p style=text-align:start>
                                        <a href=https://demo.riea.in/public/Documents-proforma-online-counselling.pdf>Click Here</a> to Download
                                        Counselling Form.
                                    </p>
                                    <p style=text-align:right;margin-top:15px>
                                        <strong>Academic Section <br>R.I.E., Ajmer </strong>
                                    </p>
                                </div>
                            </body>

                            </html>';

                $subject = "Admission in 4-Year " . $value['course'] . " (" . $value["student_counselling_subject"] . ") for the session 2025-26 regarding";

                $email->setFrom($from, $fromName);
                // $email->setTo($value['email']);
                // $email->setBCC('abhishek.sharma@ibirdsservices.com');
                $email->setTo("abhishek.sharma@ibirdsservices.com");

                $email->setSubject($subject);
                $email->setMessage($msg);
                $email->attach('https://demo.riea.in/public/subject_wise_lists/' . str_replace(" ", "_", $value['student_counselling_subject']) . '_' . $counsellingId . '.pdf');

                $mail = $email->send();

                if ($mail == true) {
                    echo json_encode(['message' => 'Counselling Mail Sent Successfully.', 'success' => true]);
                } else {
                    // print_r($email->printDebugger(['headers']));exit;
                    echo json_encode(['message' => 'Something went wrong', 'success' => false]);
                }
                $email->clear(true);
            }
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }
}
