<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

use App\Models\HomeModel;
// use App\Models\LectureModel;
use App\Models\CommonModel;

class Home extends BaseController
{
    // public function index(): string
    // {
    //     return view('welcome_message');
    // }

    public function index(){		
        try{
            $homeModel = new HomeModel();
            $commonModel = new CommonModel();

            $session_data = session()->get();

            // var_dump($session_data);exit;

            $data = [];
            // $lastTwoLecturesData = array();
            // $studentAssignments = array();
            // $lecturesData = array();
            // $scheduleExams = array();
            // $feedbackResponse = array();
            // $feeDepositeDetail = array();
            //var_dump($_SESSION["student"]);
            // if(isset($_SESSION["student"]) && count($_SESSION["student"]) > 0 && isset($_SESSION["student"][0]->reg_no) && !empty($_SESSION["student"][0]->reg_no)){
            //     //===START_7OCT2020_KIRAN_Description: get data for last two days add date argument
            //     $date = date("Y-m-d");
            //     $dayBefore = date( 'Y-m-d', strtotime( $date . ' -2 day' ));
            //     $data['studentData'] = $_SESSION['student'][0];
            //     // $lastTwoLecturesData = $lectureModel->getBatchLastTwoLectures($dayBefore);
            //     //===END_7OCT2020_KIRAN_Description: get data for last two days add date argument
            //     // $studentAssignments = $lectureModel->studentAssignmentList();
            //     $scheduleExams = $homeModel->getScheduleExams();
            //     $feeDepositeDetail =  $homeModel->getFeesDetail();
            //     #var_dump($scheduleExams);
            //     //$data['studentLastTwoLectures']
            // }
            
            //     if(count($feeDepositeDetail) > 0){
            //     $data['feeDepositeDetail'] = $feeDepositeDetail; 
            // }
            // if(count($scheduleExams) > 0){
            //     /*KIRAN_9-NOV-2020_for show upcoming exam with time filter*/
            //     foreach($scheduleExams as $key => $value){
                    
            //         if(date('H:i') >= $value['start_time']){
            //             unset($scheduleExams[$key]);
            //         }
            //     }
            //     $data['scheduleExams'] = $scheduleExams; 
            // }
            // if(count($lastTwoLecturesData) > 0){
            //     foreach($lastTwoLecturesData as $dataLectures){
            //         $dateArr= explode(",",$dataLectures['delivered_date']);
            //         $topicArr = explode(":|:",$dataLectures['content_master_data']);
            //         $deliveredArr = explode(":|:",$dataLectures['content_delivered_data']);
            //         if( count($dateArr) > 0 && count($topicArr) > 0 && count($deliveredArr) > 0){
                    
            //             foreach($dateArr as $key => $value){
            //                 $lecturesData[$dataLectures['batch_table_id']][] = array(
            //                                                             'batch-name'=>$dataLectures['batch_name'],
            //                                                             'subject-name'=>$dataLectures['tag_name'],
            //                                                             'delivered-date'=>date('d-m-Y', strtotime($dateArr[$key])),
            //                                                             'master-data'=>$topicArr[$key],
            //                                                             'delivered-data'=>$deliveredArr[$key],
            //                                                             );
            //             }
            //         }
                            
            //     }
                
            //     $data['studentLastTwoLectures'] = $lecturesData;
            // }
            // if(count($studentAssignments) > 0){
            //     foreach($studentAssignments as $assgn){
            //         $pendingCount = 0;
            //         if(strtolower($assgn->student_file_status) == 'pending' && $pendingCount < 5){
            //             $pendingCount++;
            //             $data['pendingAssignment'][] = $assgn->file_name;
            //         }
            //     }    
            // }
            // $feedbackResult =  $homeModel->getTodayResponseOfFeedback();
            // if(!empty($feedbackResult)){
            //     $data['feedbackResponse'] = $feedbackResult;
            // }
            $data['pageTitle'] = "Home";
            $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));
            return view('admin/template/header',$data). view('admin/template/navbar',$data) . view("admin/home/home", $data) . view('admin/template/footer');
        }catch(Exception $exception){
            var_dump($exception);
            return $this->getResponse(
                ['status' => 'ERROR', 'message' => $exception->getMessage()],
                 ResponseInterface::HTTP_BAD_REQUEST
            );
        }
	}

	function resetPassword(){

        $homeModel = new HomeModel();

        $data = array();
                
        if(isset($_REQUEST['newpswd']) && isset($_SESSION["student"][0]->id) ){
        	
        	$flag = $homeModel->changePassword($_REQUEST['newpswd'],$_SESSION["student"][0]->id);
        	$data['validToken'] = false;
        	$data['confirmBox'] = true;
        }else{
            $data['validToken'] = true;
            $data['confirmBox'] = false;
        }
        $data['pageTitle'] = "Reset Password";
        return view('admin/template/header',$data). view('admin/template/navbar') . view("admin/home/reset_password", $data) . view('admin/template/footer');
    }

    function profile(){
        $data = array();
        $data['pageTitle'] = "Profile";
        $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));
        return view('admin/template/header',$data). view('admin/template/navbar', $data) . view("admin/home/profile", $data) . view('admin/template/footer');
    }

    function profileEdit($newValue=null){

        $homeModel = new HomeModel();
        $commonModel = new CommonModel();

        $data = array();
        if(isset($_REQUEST['email'])){
            $result = $homeModel->updateStudent($_REQUEST);
            $data = $homeModel->updateStudentSession();
            $_SESSION['student'] = $data;
            return redirect()->to('admin/home/profile');
            #profile();
        }
        $data['allStates'] = $commonModel->getAllStates($_SESSION['student'][0]->state);
        $data['citiesOfStates'] = $commonModel->getAllCitiesOfState($_SESSION['student'][0]->state,$_SESSION['student'][0]->city);
        #var_dump($data['citiesOfStates'] );
        $data['btnTitle'] = $_REQUEST['btnTitle'];
        unset($_REQUEST['btnTitle']);
        $data['pageTitle'] = "Edit Profile";
        $data['navbar'] = $this->navbar_configuration->get_navbar(session()->get('role'));
        return view('admin/template/header',$data). view('admin/template/navbar', $data) . view("admin/home/edit_profile", $data) . view('admin/template/footer');
    }
}
