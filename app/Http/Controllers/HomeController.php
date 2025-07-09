<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job_details;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){

        $categories=Category::where('status',1)->orderBy('name','ASC')->take(8)->get();
        $featuredJobs = Job_details::where('status', 1)
            ->where('isFeatured', 1)
            ->orderBy('created_at', 'DESC')
            ->with('jobType')
            ->take(6)
            ->get();
        $latestJobs = Job_details::where('status',1)->with('jobType')->orderBy('created_at','DESC')->take(6)->get();

        return view('home',[
            'categories'=>$categories,
            'featuredJobs'=>$featuredJobs,
            'latestJobs'=>$latestJobs,
        ]);
    }
}
