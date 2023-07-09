<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ErrorController extends Controller
{
    public function notFound()
    {
        return Inertia::render("Errors/404");
    }

    public function unAuthenticated()
    {
        return Inertia::render("Errors/403");
    }
}
