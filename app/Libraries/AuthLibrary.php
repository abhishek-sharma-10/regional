<?php

namespace App\Libraries;
use Config\Services;


class AuthLibrary{

    public static function route_access($role_name){
        $current_uri = new \CodeIgniter\HTTP\URI(current_url());  
        // var_dump($current_uri);exit;  //for testing purpose
        $route_name = (is_null($current_uri->getSegment(2)) || empty($current_uri->getSegment(2))) ? "home" : $current_uri->getSegment(2);   
        $routes_permission = Services::getRoleBasedRoutes($role_name);
        return in_array($route_name, $routes_permission) ? true : false;
    } 

}
?>