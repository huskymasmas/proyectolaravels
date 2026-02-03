<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //redirecciona segun su contenido a cada rol 
        $user = \Auth::user();
        if($user->hasRole('admin')){
            return redirect('admin/inicio');

        }elseif($user->hasRole('editor')){
            return redirect('editor/inicio');

        }else{
            return redirect('/');
        }
        //return view('home');
    }
}
