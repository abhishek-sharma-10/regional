<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->set404Override(static function () {
    $data['pageTitle'] = 'No Page Found';
    return view('admin/template/header',$data). view('admin/no_page_found');
});

$routes->get('/', 'Login::studentLogin');
$routes->post('login', 'Login::studentLogin');
$routes->get('forget-password', 'Login::stu_forgetPassword');
$routes->post('forget-password', 'Login::stu_forgetPassword');
$routes->get('success', 'Login::stu_success');
$routes->get('failure', 'Login::stu_success');
$routes->get('reset-password', 'Login::stu_resetPassword');
$routes->post('reset-password', 'Login::stu_resetPassword');

$routes->get('registrations', 'Registration::studentRegistration');
$routes->post('registrations', 'Registration::studentRegistration');
$routes->get('checkApplicationNo/(:num)', 'Registration::checkApplicationNo/$1');
$routes->get('instructions', 'Registration::getInstruction');

$routes->group('/', ['filter' => 'studentAuthGuard'], static function ($routes) {
    $routes->get('dashboard/(:num)', 'Registration::studentDashboard/$1');
    $routes->post('dashboard/(:num)', 'Registration::studentDashboard/$1');
    
    $routes->get('academic', 'Registration::academicProfile');
    $routes->get('academic/(:num)', 'Registration::academicProfile/$1');
    $routes->post('update-academic-profile', 'Registration::updateAcademicProfile');
    $routes->get('fetch-subject/(:num)', 'Registration::fetchSubjects/$1');
    
    $routes->get('payment/(:num)', 'Registration::paymentInfo/$1');
    $routes->get('print-academic-details/(:num)','Registration::printAcademicDetails/$1');
    $routes->get('pay-registration-fee/(:num)','Registration::payRegistrationFee/$1');
    $routes->post('pay-registration-fee','Registration::paymentRegistrationFee');
    
    $routes->get('logout', 'Login::student_logout');
});

$routes->get('admin/', 'Login::index');
$routes->post('admin/login', 'Login::index');
// $routes->get('admin/otp-page', 'Login::otpPage');
// $routes->get('admin/otp-process', 'Login::otpProcess');
$routes->get('admin/forget-password', 'Login::forgetPassword');
$routes->post('admin/forget-password', 'Login::forgetPassword');
$routes->get('admin/success', 'Login::success');
$routes->get('admin/failure', 'Login::success');
$routes->get('admin/reset-password', 'Login::resetPassword');
$routes->post('admin/reset-password', 'Login::resetPassword');
$routes->get('admin/no-page-found', 'Login::not_found');

$routes->group('admin', ['filter' => 'authGuard'], static function ($routes) {
    // $routes->group('admin/home', static function ($routes) {
    $routes->group('home', static function ($routes) {
        $routes->get('/', 'Home::index');
        $routes->get('profile', 'Home::profile');    
        $routes->get('edit-profile', 'Home::profileEdit');    
        $routes->post('edit-profile', 'Home::profileEdit');    
        $routes->get('cities-of-state', 'Home::getCitiesOfState');    
        $routes->get('reset-password', 'Home::resetPassword');    
        $routes->post('reset-password', 'Home::resetPassword');    
    });

    $routes->group('registrations', static function ($routes) {
        $routes->get('/', 'Registration::index');
        $routes->get('detail/(:num)', 'Registration::getRegistrationDetail/$1');
    });

    $routes->group('report', static function ($routes) {
        $routes->get('state-wise-report', 'Report::registrationReport');
        $routes->post('state-wise-report', 'Report::registrationReport');
        $routes->get('subject-wise-report', 'Report::subjectWiseReport');
        $routes->post('subject-wise-report', 'Report::subjectWiseReport');
        $routes->get('category-wise-report', 'Report::categoryWiseReport');
        $routes->post('category-wise-report', 'Report::categoryWiseReport');
    });
    
    $routes->get('logout', 'Login::logout');
});