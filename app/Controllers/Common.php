<?php

namespace App\Controllers;

class Common extends BaseController
{
    public function __construct()
    {
        session();
    }

    public function getInstruction(){
        $data['pageTitle'] = "Registrations";
        $data['active'] = '';
        $id = isset($_SESSION['student']) ? $_SESSION['student'][0]->id : '';
        $data['details'] = (object)['id' => $id];
        return view('student/template/header', $data) . view('student/registrations/instruction', $data) . view('student/template/footer');
    }

    public function contactUs(){
        $data['pageTitle'] = "Contact Us";
        $data['active'] = '';

        $id = isset($_SESSION['student']) ? $_SESSION['student'][0]->id : '';
        $data['details'] = (object)['id' => $id];

        return view('student/template/header', $data) . view('student/contact_us', $data) . view('student/template/footer');
    }
    
    public function internalServer(){
        $data['pageTitle'] = "500 - Error";
        return view('500_error', $data);
    }
}
