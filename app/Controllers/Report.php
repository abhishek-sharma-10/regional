<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

use App\Models\RegistrationModel;

class Report extends BaseController
{
    function registrationReport(){
        try {
            $registrationModel = new RegistrationModel();

            $data = [];

            $data['pageTitle'] = "Registrations";
            $filterType = $this->request->getVar('filterType'); // 'state' or 'subject'
            $filterValue = $this->request->getVar('filterValue'); // e.g., 'Rajasthan' or 'Mathematics'
            
            $data['filterValue'] = $filterValue;
            //var_dump($filterType, $filterValue);exit;

            if (!empty($filterType) && !empty($filterValue)) {
                // Validate
                if (!in_array($filterType, ['state'])) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid filter type.']);
                }
    
                $data['registrations'] = $registrationModel
                    ->where($filterType, $filterValue)
                    ->findAll();
            } else {
                // No filter, fetch all
                $data['registrations'] = $registrationModel->findAll();
            }

            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/report/registration_report", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    function subjectWiseReport(){
        try {
            $registrationModel = new RegistrationModel();

            $data = [];

            $filterType = $this->request->getVar('filterType'); // 'state' or 'subject'
            $filterValue = $this->request->getVar('filterValue'); // e.g., 'Rajasthan' or 'Mathematics'
            
            $data['filterValue'] = $filterValue;

            if (!empty($filterType) && !empty($filterValue)) {
                // Validate
                if (!in_array($filterType, ['course'])) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid filter type.']);
                }
    
                $data['registrations'] = $registrationModel
                    ->where($filterType, $filterValue)
                    ->findAll();
            } else {
                // No filter, fetch all
                $data['registrations'] = $registrationModel->findAll();
            }

            $data['pageTitle'] = "Registrations";
            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/report/subject_wise_report", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    function categoryWiseReport(){
        try {
            $registrationModel = new RegistrationModel();

            $data = [];

            $filterType = $this->request->getVar('filterType'); // 'state' or 'subject'
            $filterValue = $this->request->getVar('filterValue'); // e.g., 'Rajasthan' or 'Mathematics'
            
            $data['filterValue'] = $filterValue;

            if (!empty($filterType) && !empty($filterValue)) {
                // Validate
                if (!in_array($filterType, ['category'])) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid filter type.']);
                }
    
                $data['registrations'] = $registrationModel
                    ->where($filterType, $filterValue)
                    ->findAll();
            } else {
                // No filter, fetch all
                $data['registrations'] = $registrationModel->findAll();
            }

            $data['pageTitle'] = "Registrations";
            return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/report/category_wise_report", $data) . view('admin/template/footer');
        } catch (Exception $exception) {
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }
    }

    public function fetchRegistrations()
    {
        try {
            $registrationModel = new RegistrationModel();

            $data = [];
            $data['pageTitle'] = 'Report';
            
            $filterType = $this->request->getVar('filterType'); // 'state' or 'subject'
            $filterValue = $this->request->getVar('filterValue'); // e.g., 'Rajasthan' or 'Mathematics'
            
            $data['filterValue'] = $filterValue;
            //var_dump($filterType, $filterValue);exit;

            if (!empty($filterType) && !empty($filterValue)) {
                // Validate
                if (!in_array($filterType, ['state', 'course','category'])) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid filter type.']);
                }
    
                $data['registrations'] = $registrationModel
                    ->where($filterType, $filterValue)
                    ->findAll();
            } else {
                // No filter, fetch all
                $data['registrations'] = $registrationModel->findAll();
            }

            if($filterType == 'course'){
                return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/registrations/subject_wise_report", $data) . view('admin/template/footer');
            }elseif($filterType == 'state'){
                return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/registrations/registration_report", $data) . view('admin/template/footer');
            }elseif($filterType == 'category') {
                return view('admin/template/header', $data) . view('admin/template/navbar', $data) . view("admin/registrations/category_wise_report", $data) . view('admin/template/footer');
            }
            
            // return $this->response->setJSON([
            //     'status' => 'success',
            //     'result' => $registrations
            // ]);
            //return $this->getResponse(['status' => 200, 'result' => $registrations]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
