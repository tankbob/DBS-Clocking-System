<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\LogTime;
use App\Job;

class WebController extends Controller
{
    public function index()
    {
        $user = User::find(1);
        dd($user->Jobs->first()->Users);
    }
}
