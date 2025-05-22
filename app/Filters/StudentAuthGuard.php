<?php 
namespace App\Filters; 
 
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;
use App\Libraries\AuthLibrary;

use App\Models\LoginModel;
 
class StudentAuthGuard implements FilterInterface{ 

    public function before(RequestInterface $request, $arguments = null){
        $key = Services::getSecretKey();
        $header = session()->get('access_token');
        $token = null;

        // extract the token from the header
        if(!empty($header)) {
            if (preg_match('/(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }
        
        // check if token is null or empty
        if(is_null($token) || empty($token)) {
            return redirect()->to('/');
        }// else{
            //     $role = session()->get('role');
            //     if(AuthLibrary::route_access($role)){
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
        }catch (\Firebase\JWT\ExpiredException $ex){ 
            $this->refreshToken();
        }catch (\Exception | \UnexpectedValueException $ex) {
            return redirect()->to('/logout');
        }
        //     }
        //     else{
        //         return redirect()->to('no-page-found');
        //     }
        // }  
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        
    }

    public function refreshToken(){
        try {
            helper(['jwt']);
            $loginModel = new LoginModel();

            $response = verifyRefreshToken(session()->get('refresh_token'));

            if($response){
                $result = $loginModel->getStudentById($response);
                if($result){
                    $ses_data = [
                        'student' => $result,
                        'role' => 'STUDENT',
                        'access_token' => getSignedJWTForUser($result[0]->id), 
                        'refresh_token' => getSignedRefreshToken($result[0]->id)
                    ];
                    session()->set($ses_data);
                    return redirect()->to(current_url());
                }   
            } 
        } catch (\Exception $ex) {
            return redirect()->to('/logout');
        }
    }//method
}