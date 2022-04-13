<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CekLogin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // $auth = service('auth');

        // dd($auth->isLoggedIn());

        // if (!$auth->isLoggedIn()) {
        //     return redirect()->to(site_url('/'));
        // }

        if (!session()->get('isLoggedIn')) {
            return redirect()
                ->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
