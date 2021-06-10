<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Post;
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
        $posts = Post::orderBy('id', 'desc')->get();
        $list = Helper::notifications();
        return view('home', compact('posts', 'list'));
    }

    public function indexV2()
    {
        return view('home_v2');
    }

}
