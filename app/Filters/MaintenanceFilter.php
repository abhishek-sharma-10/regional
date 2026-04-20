<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class MaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // You can make this dynamic via a config or ENV value
        if (getenv('site.maintenance') === 'true') {
            echo view('maintenance');
            die();
            return false; // Stops further execution
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
