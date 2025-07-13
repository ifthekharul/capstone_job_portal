<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(){
       $applications = JobApplication::orderBy('created_at','DESC')->with('Job_details','user','employer')->paginate(10);
       return view('admin.job-applications.list',[
        'applications'=>$applications
       ]);
    }

    public function destroy(Request $request){
        $id = $request->id;
        $JobApplication = JobApplication::find($id);

        if($JobApplication == null ){
            session()->flash('error','Either job Application deleted or not found');
            return response()->json([
            'status'=>false,
        ]);

        }
        $JobApplication->delete();
            session()->flash('success','Job Application deleted Successfully');
            return response()->json([
            'status'=>true,
        ]);
    }
}
