<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * Returns the current logged in user
     *
     * @return \Illuminate\Http\Response
     */
    public function getAuthenticatedUser()
    {
        //return response(Auth::user(), 200);
        return new UserResource(Auth::user());
    }
}
