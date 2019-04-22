<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //Home Page
    public function Home()
    {
        return view('Portal.public.index');
    }
    //event Page
    public function event()
    {
        return view ('Portal.public.events');
    }
    //gallery Page
    public function gallery()
    {
        return view ('Portal.public.gallery');
    }
}
