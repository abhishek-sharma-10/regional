<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Exception;

use App\Models\LoginModel;
use Config\Services;
use \Firebase\JWT\JWT;

use function PHPUnit\Framework\isNull;

class Login extends BaseController
{
    public function __construct()
    {
        session();
    }

    public function index()
    {
        $loginModel = new LoginModel();

		try{
            $data = [];
            if(isset($_REQUEST["username"])){
                $userDetails = array_intersect_key($_REQUEST, array_flip(array('username', 'passcode')));
                $data = $loginModel->getLoginAccess($userDetails);
                // var_dump($data);
                if(count($data) > 0){
                    if(date('m') >= 4 && date('m') <=  12){
    				    $a[date('Y').'-'.(date('Y') + 1)] = date('Y').'-'.(date('Y') + 1);
    				}else if(date('m') >= 1 && date('m') <= 3){
    					$a[(date('Y')-1).'-'.((date('Y') + 1)-1)] = (date('Y')-1).'-'.((date('Y') + 1)-1);
    				}
					
					foreach($a as $key=> $value){
						$session_data['current_year']=$key;
					}
					// $sessionDateArray = $loginModel->getCurrentSessionStartAndEndDate($session_data['current_year']);
					// $_SESSION['session_start'] = $sessionDateArray[0]->session_start;
		            // $_SESSION['session_end'] = $sessionDateArray[0]->session_end;
				                    
                    // $session_data['session_start'] = $sessionDateArray[0]->session_start;
                    // $session_data['session_end'] = $sessionDateArray[0]->session_end;
                    $session_data['admin'] = $data;
                    $session_data['OTP'] = rand(10000,99999);
                    
                    session()->set($session_data);
                    // $_SESSION['paymentInfo'] = array('merchant_id'=>'267501',
					// 		 'working_key' => '058730286324FED8BA9033DE806B8DBB',
					// 		 'access_code' =>'AVDB93HG84CF55BDFC');
                    
                    // $key = Services::getSecretKey();
                    // $iat = time(); // current timestamp value
                    // $exp = $iat + 3600;
            
                    // $payload = array(
                    //     "iat" => $iat, //Time the JWT issued at
                    //     "exp" => $exp, // Expiration time of token
                    //     "email" => $data[0]->reg_no,
                    // );
                    
                    // $token = JWT::encode($payload, $key, 'HS256');

                    $_SESSION['access_token'] = getSignedJWTForUser($data[0]->id);
                    $_SESSION['refresh_token'] = getSignedRefreshToken($data[0]->id);
                    $_SESSION['role'] = 'ADMIN';

                    // var_dump($session_data);exit;

                    $msg='Dear '.$data[0]->name.',<br/>';
                    $msg.='Please find below detail of OTP .<br/>';
                    $msg.='Your OTP is :'.$session_data['OTP'];

                    $email = \Config\Services::email();

                    // $to = $data[0]->email;
                    // $toName = $data[0]->name;
                    $from = "abhishek.sharma@ibirdsservices.com";
                    $fromName = "Star Infotech College";
                    $subject = "Star Infotech College : OTP";

                    // $email->setFrom($from,$fromName);
                    // $email->setTo($to,$toName);

                    // $email->setSubject($subject);
                    // $email->setMessage($msg);

                    // $retval = $email->send();
                    
                    // var_dump($retval);exit;

                    if( $email->send() ) {
                        $isSent = true;
                    }else {
                        $isSent = false;
                        // var_dump($email->printDebugger(['headers']));exit;
                    }


                    $data['pageTitle'] = "Login";
                    // header('location: '. base_url() .'home');
                    // return redirect()->to('admin/otp-page');
                    return redirect()->to('admin/home');
                }else{
                    $data['invalid'] = true;
                    $data['pageTitle'] = "Login";
                }
            }else{
                $data['invalid'] = false;
                $data['pageTitle'] = "Login";
            }
        
            return view("admin/login/login", $data);
        }catch(Exception $e){
            echo "<pre>";print_r($e->getTrace());die();
        }
	}

	function forgetPassword(){
        $loginModel = new LoginModel();
        $data = array();
        // var_dump($this->request->getVar());exit;
        $username = $this->request->getVar('username');
        if(isset($username) && !empty($username)){
            // $username = $_REQUEST["username"];
            $isSent = $loginModel->sendResetPasswordLink($username, "username");
            if($isSent){
                return redirect()->to('admin/success');
        	}else{
        	    $msg = $loginModel->errorMsg;
                return redirect()->to("admin/failure?msg=".$msg);
        	}
        }
        $data['pageTitle'] = 'Forget Password';
        return view('admin/template/header',$data). view("admin/login/forget_password", $data) . view('admin/template/footer');
        // return $this->view("forget_password", $data);
    }

    function success(){
        $msg = $this->request->getVar('msg');
        if(is_null($msg)){
            $data['msg'] = "Sent you an email with reset password link, please check your inbox!";
        }else{
            $data['msg'] = $msg;
        }
        $data['pageTitle'] = "Success page";
        return view('admin/template/header',$data). view("admin/login/success_failure_message.php", $data) . view('admin/template/footer');
    }

    function resetPassword(){

        $loginModel = new LoginModel();

        $token = isset($_REQUEST['token'])?$_REQUEST['token']:'';
        $key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : "";
        $validToken = false;
        
        
        if(isset($_REQUEST['newpassword']) && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])){
        	//This flag variable is used to make the security token blank so that next time it cannot be used again. 
        	$blankSecuirtyToken = true;
        	$flag = $loginModel->changePassword($_REQUEST['newpassword'],$_REQUEST['userid'], $blankSecuirtyToken, 'users');
            $userInfo = $loginModel->getUserByUsername($_REQUEST['userid']);
        	$data['validToken'] = false;
        	$data['confirmBox'] = true;
            $data['userId'] = $userInfo[0]->id;
        }else if(!empty($token) && !empty($key)){
            $userId = $loginModel->encryptDecrypt('decrypt', $token, $key );
            // echo $userId;exit;
            if(!empty($userId)){
                // $loginModel->getUserById($userId);
                $data['validToken'] = true;
                $data['userId'] = $userId;
            }
        }else if(!isset($_REQUEST['confirmBox'])){
            return '';//redirect()-to('/');
        }

        $data['pageTitle'] = "Reset Password";
        return view('admin/template/header',$data). view("admin/login/reset_password", $data) . view('admin/template/footer');
        // $this->view("reset_password", $data);
    }

    function otpPage(){
        session();
        $data['pageTitle'] = "OTP";
        return view('admin/template/header',$data). view("admin/login/otp_page", $data) . view('admin/template/footer');
    }

    function otpProcess(){
        // var_dump($_REQUEST);exit;
        session();
        if(isset($_REQUEST['otp'])){
            if(strlen($_REQUEST['otp']) == 5){
                if($_SESSION['OTP'] == $_REQUEST['otp']){
                    return json_encode(['status'=>'success','message'=>"OTP Verified Successfully."]);
                }
                else{
                    return json_encode(['status'=>'error','message'=>"Invalid OTP Please try again."]);
                }
            }
            else{
                return json_encode(['status'=>'error','message'=>"Please Enter a valid OTP."]);
            }
        }else{
            return json_encode(['status'=>'error','message'=>"Please Enter a valid OTP."]);
        }
    }

    function not_found(){
        return view('admin/no_page_found');
    }

    // ----------------------------------------------------------
    function studentLogin(){
        session();
        $data = [];
        $data['pageTitle'] = "Student Login";

        $loginModel = new LoginModel();

		try{            
            $data = [];
            if(isset($_REQUEST["email"])){
                $userDetails = array_intersect_key($_REQUEST, array_flip(array('email', 'password')));
                $data = $loginModel->getStudentLoginAccess($userDetails);
                // var_dump($data);
                if(count($data) > 0){
                    if(date('m') >= 4 && date('m') <=  12){
    				    $a[date('Y').'-'.(date('Y') + 1)] = date('Y').'-'.(date('Y') + 1);
    				}else if(date('m') >= 1 && date('m') <= 3){
    					$a[(date('Y')-1).'-'.((date('Y') + 1)-1)] = (date('Y')-1).'-'.((date('Y') + 1)-1);
    				}
					
					foreach($a as $key=> $value){
						$session_data['current_year']=$key;
					}
					// $sessionDateArray = $loginModel->getCurrentSessionStartAndEndDate($session_data['current_year']);
					// $_SESSION['session_start'] = $sessionDateArray[0]->session_start;
		            // $_SESSION['session_end'] = $sessionDateArray[0]->session_end;
				                    
                    // $session_data['session_start'] = $sessionDateArray[0]->session_start;
                    // $session_data['session_end'] = $sessionDateArray[0]->session_end;
                    $session_data['student'] = $data;
                    //$session_data['OTP'] = rand(10000,99999);
                    
                    session()->set($session_data);
                    // $_SESSION['paymentInfo'] = array('merchant_id'=>'267501',
					// 		 'working_key' => '058730286324FED8BA9033DE806B8DBB',
					// 		 'access_code' =>'AVDB93HG84CF55BDFC');
                    
                    // $key = Services::getSecretKey();
                    // $iat = time(); // current timestamp value
                    // $exp = $iat + 3600;
            
                    // $payload = array(
                    //     "iat" => $iat, //Time the JWT issued at
                    //     "exp" => $exp, // Expiration time of token
                    //     "email" => $data[0]->reg_no,
                    // );
                    
                    // $token = JWT::encode($payload, $key, 'HS256');

                    $_SESSION['access_token'] = getSignedJWTForUser($data[0]->id);
                    $_SESSION['refresh_token'] = getSignedRefreshToken($data[0]->id);
                    $_SESSION['role'] = 'STUDENT';

                    // var_dump($session_data);exit;

                    // $msg='Dear '.$data[0]->name.',<br/>';
                    // $msg.='Please find below detail of OTP .<br/>';
                    // $msg.='Your OTP is :'.$session_data['OTP'];

                    // $email = \Config\Services::email();

                    // $to = $data[0]->email;
                    // $toName = $data[0]->name;
                    // $from = "abhishek.sharma@ibirdsservices.com";
                    // $fromName = "Star Infotech College";
                    // $subject = "Star Infotech College : OTP";

                    // $email->setFrom($from,$fromName);
                    // $email->setTo($to,$toName);

                    // $email->setSubject($subject);
                    // $email->setMessage($msg);

                    // $retval = $email->send();
                    
                    // var_dump($retval);exit;

                    // if( $email->send() ) {
                    //     $isSent = true;
                    // }else {
                    //     $isSent = false;
                    //     // var_dump($email->printDebugger(['headers']));exit;
                    // }


                    // $data['pageTitle'] = "Login";
                    // header('location: '. base_url() .'home');
                    return redirect()->to('/dashboard/'.$data[0]->id);
                }else{
                    $data['invalid'] = true;
                    $data['pageTitle'] = "Login";
                }
            }else{
                $data['invalid'] = false;
                $data['pageTitle'] = "Login";
            }
        
            return view('student/template/header',$data). view("student/login/login", $data) . view('student/template/footer');
        }catch(Exception $e){
            echo "<pre>";print_r($e->getTrace());die();
        }
    }

    function stu_forgetPassword(){
        $loginModel = new LoginModel();
        $data = array();
        // var_dump($this->request->getVar());exit;
        $email = $this->request->getVar('email');
        if(isset($email) && !empty($email)){
            // $email = $_REQUEST["email"];
            $isSent = $loginModel->sendResetPasswordLink($email, "email");
            if($isSent){
                return redirect()->to('success');
        	}else{
        	    $msg = $loginModel->errorMsg;
                return redirect()->to("failure?msg=".$msg);
        	}
        }
        $data['pageTitle'] = 'Forget Password';
        return view('student/template/header',$data). view("student/login/forget_password", $data) . view('student/template/footer');
        // return $this->view("forget_password", $data);
    }

    function stu_success(){
        $msg = $this->request->getVar('msg');
        if(is_null($msg)){
            $data['msg'] = "Sent you an email with reset password link, please check your inbox!";
        }else{
            $data['msg'] = $msg;
        }
        $data['pageTitle'] = "Success page";
        return view('student/template/header',$data). view("student/login/success_failure_message.php", $data) . view('student/template/footer');
    }

    function stu_resetPassword(){
        try{
            $loginModel = new LoginModel();
    
            $token = isset($_REQUEST['token'])?$_REQUEST['token']:'';
            $key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : "";
            $validToken = false;
                        
            if(isset($_REQUEST['newpassword']) && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])){
                //This flag variable is used to make the security token blank so that next time it cannot be used again. 
                $blankSecuirtyToken = true;
                $flag = $loginModel->changePassword($_REQUEST['newpassword'],$_REQUEST['userid'], $blankSecuirtyToken, 'registrations');
                $userInfo = $loginModel->getStudentByMail($_REQUEST['userid']);
                $data['validToken'] = false;
                $data['confirmBox'] = true;
                $data['userId'] = $userInfo[0]->id;
            }else if(!empty($token) && !empty($key)){
                $userId = $loginModel->encryptDecrypt('decrypt', $token, $key );
                // echo $userId;exit;
                if(!empty($userId)){
                    // $loginModel->getUserById($userId);
                    $data['validToken'] = true;
                    $data['userId'] = $userId;
                }
            }else if(!isset($_REQUEST['confirmBox'])){
                return redirect()->to('/');
            }
    
            $data['pageTitle'] = "Reset Password";
            return view('student/template/header',$data). view("student/login/reset_password", $data) . view('student/template/footer');
        }catch(Exception $e){
            echo "<pre>";print_r($e->getTrace());exit();
        }
    }

    function logout(){
        session();
        session()->destroy();
        // header("location: ".base_url());
        return redirect()->to('admin/');
    }

    function student_logout(){
        session();
        session()->destroy();
        // header("location: ".base_url());
        return redirect()->to('/');
    }
}
