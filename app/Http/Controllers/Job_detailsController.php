<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job_details;
use App\Models\JobType;
use Illuminate\Http\Request;

class Job_detailsController extends Controller
{
    //This Method will be show job posts
    public function index(Request $request){

        $categories = Category::where('status',1)->get();
        $jobTypes = JobType::where('status',1)->get();

        $jobs = Job_details::where('status',1);

        //Search with Keyword and title
        if (!empty($request->keyword)) {
        $jobs = $jobs->where(function($query) use ($request) {
            $query->orWhere('title', 'like', '%' . $request->keyword . '%');
            $query->orWhere('keywords', 'like', '%' . $request->keyword . '%');
        });
    }

       //Search with Location
       if (!empty($request->location)) {
        $jobs = $jobs->where('location',$request->location);
       }

       //Search with category
       if (!empty($request->category)) {
        $jobs = $jobs->where('category_id',$request->category);
       }
       $jobTypeArray =[];

       //Search with Job Type
      if (!empty($request->jobType)) {
        $jobTypeArray = explode(',',$request->jobType );
        $jobs = $jobs->whereIn('job_type_id',$jobTypeArray);
       }

       //Search with Experience
       if (!empty($request->experience)) {
        $jobs = $jobs->where('experience',$request->experience);
       }
       if(!is_null($request->sort) && $request->sort ==0){
        $jobs = $jobs->orderBy('created_at','ASC');

       }else{
        $jobs = $jobs->orderBy('created_at','DESC');

       }
       $jobs = $jobs->paginate(9);

     // $jobs = $jobs->with(['jobType','category'])->orderBy('created_at','DESC')->paginate(9);

        return view('job_details',[
            'categories'=>$categories,
            'jobTypes'=>$jobTypes,
            'jobs'=>$jobs,
            'jobTypeArray'=>$jobTypeArray
        ]);
        
    }

    //Job details page
    public function detail($id){

        $job = Job_details::where(['id'=>$id ,'status'=> 1])->with(['jobType','category'])->first();

        if($job == null){
            abort(404);
        }
        return view('jobDetailpage',[
            'job' => $job
        ]);
    }
}
