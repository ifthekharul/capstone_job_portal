<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job_details;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $count = 0;
        if(Auth::user()){
            $count = SavedJob::where([
                'user_id'=>Auth::user()->id,
                'job_details_id' =>$id
            ])->count();

        }
        // fetch Applicants
        $applications = JobApplication::where('job_details_id',$id)->with('user')->get();
       // dd($applications);
        
        return view('jobDetailpage',[
            'job' => $job,
            'count'=>$count,
            'applications'=>$applications
        ]);
    }
    public function applyJob(Request $request){
        $id = $request->id;
        $job=Job_details::where('id',$id)->first();
        if($job == null){
            session()->flash('error','Job does not exists');
            return response()->json([
                'status'=>false,
                'message'=>'Job doesnot exits'
            ]);
        }

        // You cannot apply on your own Job

            $employer_id =$job->user_id;
            if($employer_id == Auth::user()->id){
            session()->flash('error','You cannot apply on your own job');
            return response()->json([
                'status'=>false,
                'message'=>'You cannot apply on your own job'
            ]);
            }

            //You cannot apply on ajob twice
            $jobApplicationCount = JobApplication::where([
                'user_id'=>Auth::id(),
                'job_details_id' =>$id
            ])->count();
            if($jobApplicationCount >0){
                session()->flash('error','You applied on this job');
            return response()->json([
                'status'=>false,
                'message'=>'You applied on this job'
            ]);
            }

            $application = new JobApplication();
            $application->job_details_id= $id;
            $application->user_id= Auth::user()->id;
            $application->employer_id = $employer_id;
            $application->applied_date = now();
            $application->save();

            session()->flash('success','You have successfully applied');
            return response()->json([
                'status'=>true,
                'message'=>'You have successfully applied'
            ]);

    }

    public function saveJob(Request $request){
        $id = $request->id;
        $job=Job_details::where('id',$id)->first();
        if($job == null){
            session()->flash('error','Job does not exists');
            return response()->json([
                'status'=>false,
                'message'=>'Job doesnot exits'
            ]);
        }


            //You cannot save on ajob twice
            $count = SavedJob::where([
                'user_id'=>Auth::user()->id,
                'job_details_id' =>$id
            ])->count();
            if($count >0){
                session()->flash('error','You already saved this job');
            return response()->json([
                'status'=>false,
                'message'=>'You already saved this job'
            ]);
            }

            $savedJob = new savedJob();
            $savedJob->job_details_id= $id;
            $savedJob->user_id= Auth::user()->id;
            $savedJob->save();

            session()->flash('success','You have successfully Saved the Job');
            return response()->json([
                'status'=>true,
                'message'=>'You have successfully Saved the Job'
            ]);

    }
}
