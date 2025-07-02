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
use Exception;
use UnexpectedValueException;

class AuthGuard implements FilterInterface{ 

    public function before(RequestInterface $request, $arguments = null){
        // $key = Services::getSecretKey();
        $header = session()->get('access_token');
        $token = null;

        // extract the token from the header
        if(!empty($header)) {
            if (preg_match('/(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }
        
        try {
            // $token = session()->get('access_token');

            if ($token != null) {
                $role_name = session()->get('role');
                if (strcasecmp($role_name,  'ADMIN') == 0 || AuthLibrary::route_access($role_name)) {
                    $decoded = JWT::decode($token, new Key(Services::getSecretKey(), 'HS256'));
                } else {
                    return redirect()->to('/no_page_found');
                }
            } else {
                return redirect()->to('/admin');
            }

            // $decoded = JWT::decode($token, new Key($key, 'HS256'));
        }catch (\Firebase\JWT\ExpiredException $ex){ 
            $this->refreshToken();
        }catch (Exception | UnexpectedValueException $ex) {
            return redirect()->to('/admin');
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        
    }

    public function refreshToken(){
        try {
            helper(['jwt']);
            $loginModel = new LoginModel();

            $response = verifyRefreshToken(session()->get('refresh_token'));

            if($response){
                $result = $loginModel->getUserByUsername($response);
                if($result){
                    $ses_data = [
                        'user' => $result,
                        'access_token' => getSignedJWTForUser($result[0]->username), 
                        'refresh_token' => getSignedRefreshToken($result[0]->username),
                        'role' => $result[0]->role
                    ];
                    session()->set($ses_data);
                    return redirect()->to(current_url());
                }   
            } 
        } catch (Exception $ex) {
            return redirect()->to('/admin/');
        }
    }//method
}