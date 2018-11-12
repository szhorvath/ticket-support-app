<?php

namespace App\Controllers;

use App\Models\User;
use App\Http\Request;
use App\Http\Response;
use App\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $users = $this->db->query("SELECT * FROM users")->fetchAll(\PDO::FETCH_CLASS, User::class);

        return $response->withJson($users);
    }

    public function store(Request $request, Response $response)
    {
        var_dump($request->getParams());
        die;
        // $users = $this->db->query("SELECT * FROM users")->fetchAll(\PDO::FETCH_CLASS, User::class);

        return $response->withJson($users);
    }
}
