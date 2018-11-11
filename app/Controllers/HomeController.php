<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $response->setBody(file_get_contents(__DIR__ . '/../../public/dist/index.html', true));
    }
}
