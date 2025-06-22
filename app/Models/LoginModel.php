<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DBServices;
use Exception;

class LoginModel extends Model {
    
    protected $table = 'users';
    protected $primaryKey = 'id';

    public $errorMsg;
    
    function getLoginAccess($userDetails){

        $username = $userDetails['username'];
        $password = $userDetails['passcode'];

        $result = $this->db->query('SELECT * FROM users where username="'.$username.'"');

        if($result->getNumRows() > 0)
        {
        	$result = $result->getResult();
            if(password_verify($password, $result[0]->password))
        		return $result;
        	else
        		return array();
        }
        else
        {
        	return array();
        }
    }

    // Not in use
    function getCurrentSessionStartAndEndDate($session_year){
        // $params = array("name" => $session_year);
        // $this->db->where($params);
        // $result = $this->db->get('session_master');

        $result = $this->db->query("SELECT * FROM session_master WHERE name='".$session_year."'");

        if($result->getNumRows() > 0)
        	return $result->getResult();
        else
        	return array();
    }

    function sendResetPasswordLink($userKey, $userFlag = null){
    	
        $email = \Config\Services::email();

        $from = "abhishek.sharma@ibirdsservices.com";
        $fromName = "RIE Ajmer";
        
        $userId = $userKey;
        $publicKey = md5(rand()); 
        $securityToken = $this->getSecuirtyToken($userId,$publicKey);

        $name = '';
        $user = '';
        $url = '';
	    if(strtolower($userFlag) == "username"){
	        $userInfo = $this->getUserByUsername($userId);
            $result = $this->updateToken($userId);
            $name = isset($userInfo[0]->name) && !empty($userInfo[0]->name) ? $userInfo[0]->name : '';
            $user = isset($userInfo[0]->emusernameail) && !empty($userInfo[0]->username) ? $userInfo[0]->username : '';
            $url = base_url()."admin/reset-password?token=$securityToken&key=$publicKey";
	    }else if(strtolower($userFlag) == "email"){
	        $userInfo = $this->getStudentByMail($userId);
            $name = isset($userInfo[0]->name) && !empty($userInfo[0]->name) ? $userInfo[0]->name : '';
            $user = isset($userInfo[0]->email) && !empty($userInfo[0]->email) ? $userInfo[0]->email : '';
            $url = base_url()."reset-password?token=$securityToken&key=$publicKey";
        }
	    // var_dump($userInfo);exit;

        
        if(isset($userInfo[0]->email) && !empty($userInfo[0]->email)){
            // echo "ENer";exit;

            $msg="<i><b>Hi " . ucfirst($name) . " </b>,<br/><br/>";

            $msg.="RIE recently received a request to reset the password for the username ".$user.".<br/>To finish resetting your password, go to the following link.<br/><br/>".$url;

            $msg.="<br/><br/><b>Thanks,</b> <br/>";
            $msg.="<b>RIE Ajmer</b></i>";

            $to = $userInfo[0]->email;
            $toName = $name;
            $subject = "RIE : Reset Password";
            
            // $config['protocol'] = 'ssmtp';
            // $config['charset'] = 'iso-8859-1';
            // $config['smtp_host'] = 'ssl://ssmtp.googlemail.com';
            // $config['smtp_port'] = '465';
            // $config['smtp_user'] = 'abhishek.sharma@ibirdsservices.com';
            // $config['smtp_pass'] = 'abhishek@108yash';
            // $config['wordwrap'] = TRUE;
            // $config['mailtype'] = 'html';

            // $this->email->initialize($config);

            // $this->email->set_newline("\r\n");

            $email->setFrom($from,$fromName);
            $email->setTo($to,$toName);

            $email->setSubject($subject);
            $email->setMessage($msg);

            $retval = $email->send();
            
            // var_dump($retval);exit;

            if( $retval == true ) {
                $isSent = true;
            }else {
                $isSent = false;
                print_r($email->printDebugger(['headers']));exit;

                $this->errorMsg = error_get_last();
            }
			if(!$isSent){   
			    return false;
			}
			return true;
        }else{
            $this->errorMsg = "Invalid $userFlag provided."; 
        }
	    return false;
	}

	function getUserByUsername($username){

        $result = $this->db->query("SELECT id, name, email, username FROM users where username='$username'");

        if($result->getNumRows() > 0)
        	return $result->getResult();
        else
        	return array();
    }

    function getStudentByMail($email){
        $result = $this->db->query("SELECT id, name, email FROM registrations where email='$email'");

        if($result->getNumRows() > 0)
        	return $result->getResult();
        else
        	return array();
    }

    function getStudentById($id){
        $result = $this->db->query("SELECT id, name, email FROM registrations where id='$id'");

        if($result->getNumRows() > 0)
        	return $result->getResult();
        else
        	return array();
    }

    function getSecuirtyToken($userid = NULL, $publicKey = null){
        return $this->encryptDecrypt("encrypt",$userid, $publicKey); 
    }
    
    function encryptDecrypt($action, $value, $publicKey = null) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'indiaisgreat';
        //$publicKey = 'greatiindiaibirds';
        // hash
        $key = hash('sha256', $secret_key);
        //echo $key . "<hr/>";
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $publicKey), 0, 16);
        //echo $iv . "<hr/>";
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($value, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($value), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    function updateToken($username = null){
        // $params = array("security_token" => 'Y', 'reg_no' => $userid);
        // $this->db->where("reg_no", $userid);
        $result =  $this->db->query("UPDATE users set security_token ='Y' WHERE username='$username'");
        if($result)
        	return true;
    	else
    		return false;
    }

    function changePassword($newpassword, $userid = NULL, $blankSecurityToken = null, $table = null){

    	$newpassword = password_hash($newpassword, PASSWORD_DEFAULT);

		if(empty($userid)){
            $params = array('password' => $newpassword);
            $this->db->where("id",$userid);
            $result =  $this->db->update('users');
            if($result)
	        	return true;
	        else
	        	return false;
    	    
		}else{
            $query = '';
            if($table == 'users'){
                $query = "UPDATE users SET password='$newpassword'";
    
                if($blankSecurityToken){
                    $query .= ", security_token=''";		    
                }
    
                $query .= " WHERE username='$userid'";
            }else{
                $query = "UPDATE registrations SET password='$newpassword' WHERE email='$userid'";
            }

            $result = $this->db->query($query); 

	        if($result)
	        	return true;
	        else
	        	return false;
		}
	}

    function getStudentLoginAccess($userDetails){

        $email = $userDetails['email'];
        $password = $userDetails['password'];

        $result = $this->db->query('SELECT * FROM registrations where email="'.$email.'"');

        if($result->getNumRows() > 0)
        {
        	$result = $result->getResult();
            if(password_verify($password, $result[0]->password))
        		return $result;
        	else
        		return array();
        }
        else
        {
        	return array();
        }
    }
}
