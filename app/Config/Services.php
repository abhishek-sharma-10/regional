<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    public static function getSecretKey(){
		return getenv('JWT_SECRET_KEY');
	}

    public static function getRefreshTokenSecretKey(){
        return getenv('JWT_REFRESH_SECRET_KEY');
    }

    public static function getRoleBasedRoutes($role_name){

		$reservedRoutes = [
			'ADMIN' => ['home', 'registrations', 'ncet-applications', 'subject', 'counselling', 'report', 'logout'], 
			'ACCOUNT' => ['home', 'counselling', 'logout'], 
			'SUBJECT' => ['home', 'subject', 'logout'], 
		];

		$accessable_menus = [];

		if(!is_null($role_name) || !empty($role_name)){
			$role = strtoupper($role_name);
			if(isset($reservedRoutes[$role])){
				$accessable_menus = $reservedRoutes[$role];
			}
		}
		
		return $accessable_menus;
	} 
}
