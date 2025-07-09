<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job_details;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    public function registration(){
        return view('account.registration');
    }

    public function logout(){
        Auth::logout();
        return redirect()-> route('account.login');
    }

    public function processRegistration( Request $request){
       $validator=Validator::make($request->all(),[
        'name'=>'required',
        'email'=>'required|email|unique:users,email',
        'password'=>'required|min:5|same:confirm_password',
        'confirm_password'=>'required',
       ]);
       if($validator->passes()){
         $user = new User();
         $user-> name = $request->name;
         $user-> email = $request->email;
         $user-> password = Hash::make($request->password);
         $user->  save();

        session()->flash('success', 'Registration successful!');

        return response()->json([
        'status' => true,
        'message' => 'Registration successful!'
    ]);
       }
       else{
        return response()->json([
            'status'=>false,
            'error'=> $validator->errors()
        ]);

       }
    }

     public function login(){
       return view('account.login');
    }

    public function authenticate(Request $request){
       $validator=Validator::make($request->all(),[
        'email'=>'required|email',
        'password'=>'required', 
       ]);

       if($validator->passes()){
        if(Auth::attempt(['email'=> $request-> email, 'password'=> $request-> password])){
            return redirect()-> route('account.profile');
        }
        else{
            return redirect()-> route('account.login')->with('error','Either Email/Password is incorrect');
        }
         
       }
       else{
         return redirect()
            ->route('account.login') 
            ->withErrors($validator)
            ->withInput($request->only('email')); 
       }
    }

    public function profile(){
        $id = Auth::user()->id;
        $user = User::where('id',$id)->first();

        return view('account.profile',[
            'user' =>$user
        ]);
    }
    public function updateProfile(Request $request){
        $id = Auth::user()->id;

        $validator=Validator::make($request->all(),[
        'name'=>'required|min:5|max:20',
        'email'=>'required|email|unique:users,email,' . $id . ',id',
       ]);

       if($validator->passes()){
         $user = User::find($id);

         $user->name = $request->name;
         $user->email = $request->email;
         $user->designation = $request->designation;
         $user->mobile = $request->mobile;
         $user->save();

         session()->flash('success', 'Profile Updated successfully');

        return response()->json([
        'status' => true,
        'error' =>[]
    ]);
       }
       else{
        return response()->json([
            'status'=>false,
            'error'=> $validator->errors()
        ]);
       }

    }

    public function updateProfilePic(Request $request){
        $id = Auth::user()->id;
        $validator=Validator::make($request->all(),[
        'image'=>'required|image',
       ]);
       

         if($validator->passes()){
            
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imagename = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profilePic/'),$imagename);
            

            //Create Small thumbnail
            $sourcePath = public_path('/profilePic/' . $imagename);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);

            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profilePic/thumb/'. $imagename));

            //Delete Old Profile
            File::delete(public_path('/profilePic/thumb/'. Auth::user()->image));
            File::delete(public_path('/profilePic/'. Auth::user()->image));



            User::where('id',$id)->update(['image' => $imagename]);

            session()->flash('success', 'Profile Picture Update successfully!');

            return response()->json([
            'status'=>true,
            'errors'=> []
        ]);

       }
       else{
        return response()->json([
            'status'=>false,
            'errors'=> $validator->errors()
        ]);
       }
    }


    public function createJob(){

        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobtypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        return view('account.job.create',[
            'categories' => $categories,
            'jobtypes' => $jobtypes
        ]);
    }

    public function saveJob(Request $request){
        $rules=[
            'title'=>'required|min:5|max:200',
            'category'=>'required',
            'jobType'=>'required',
            'vacancy'=>'required|integer',
            'location'=>'required|min:2|max:80',
            'description'=>'required|min:10|max:2000',
            'company_name'=>'required|min:3|max:80',
        ];

        $validator=Validator::make($request->all(),$rules);

        if($validator->passes()){
            $job = new Job_details();
            $job-> title = $request->title;
            $job-> category_id = $request->category;
            $job-> job_type_id  = $request->jobType;
            $job-> user_id  = Auth::user()->id;
            $job-> vacancy = $request->vacancy;
            $job-> salary = $request->salary;
            $job-> location = $request->location;
            $job-> description = $request->description;
            $job-> benefits = $request->benefits;
            $job-> responsibility = $request->responsibility;
            $job-> qualifications = $request->qualifications;
            $job-> keywords = $request->keywords;
            $job-> experience = $request->experience;
            $job-> company_name = $request->company_name;
            $job->  company_location  = $request-> company_location ;
            $job-> company_website = $request->company_website;
            $job->  save();

            session()->flash('success', 'Job Added successfully');

            return response()->json([
            'status'=>true,
            'errors'=> []
            ]);

        }else{
            return response()->json([
            'status'=>false,
            'errors'=> $validator->errors()
        ]);

        }


    }
    public function myJobs(){
        $jobs=Job_details::where('user_id',Auth::user()->id)->with('jobType')->orderBy('created_at','DESC')->paginate(10);
        return view('account.job.myjobs',[
            'jobs' => $jobs
        ]);
    }

    public function editJobs(Request $request ,$id){
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobtypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        $job = Job_details::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();
        if($job == null){
            abort(404);
        }

        return view('account.job.edit',[
            'categories' => $categories,
            'jobtypes' => $jobtypes,
            'job' => $job
        ]);
    }

    public function updateJob(Request $request ,$id){
        $rules=[
            'title'=>'required|min:5|max:200',
            'category'=>'required',
            'jobType'=>'required',
            'vacancy'=>'required|integer',
            'location'=>'required|min:2|max:80',
            'description'=>'required|min:10|max:2000',
            'company_name'=>'required|min:3|max:80',
        ];

        $validator=Validator::make($request->all(),$rules);

        if($validator->passes()){
            $job = Job_details::find($id);
            $job-> title = $request->title;
            $job-> category_id = $request->category;
            $job-> job_type_id  = $request->jobType;
            $job-> user_id  = Auth::user()->id;
            $job-> vacancy = $request->vacancy;
            $job-> salary = $request->salary;
            $job-> location = $request->location;
            $job-> description = $request->description;
            $job-> benefits = $request->benefits;
            $job-> responsibility = $request->responsibility;
            $job-> qualifications = $request->qualifications;
            $job-> keywords = $request->keywords;
            $job-> experience = $request->experience;
            $job-> company_name = $request->company_name;
            $job->  company_location  = $request-> company_location ;
            $job-> company_website = $request->company_website;
            $job->  save();

            session()->flash('success', 'Job Updated successfully');

            return response()->json([
            'status'=>true,
            'errors'=> []
            ]);

        }else{
            return response()->json([
            'status'=>false,
            'errors'=> $validator->errors()
        ]);

        }


    }
    public function deleteJob(Request $request){
        $job=Job_details::where([
            'user_id' =>Auth::user()->id,
            'id' => $request->jobId
        ])->first();

        if($job == null){
            session()->flash('error', 'Either Job deleted or not found');
            return response()->json([
                'status'=> false
            ]);
        }
        Job_details::where('id',$request->jobId)->delete();
        session()->flash('success', 'Job deleted Successfully');
            return response()->json([
                'status'=> true
            ]);
    }

    public function updatePassword(Request $request){
        $validator=Validator::make($request->all(),[
        'old_password'=>'required',
        'new_password'=>'required|min:5',
        'confirm_password'=>'required|min:5|same:new_password',
       ]);
       if($validator->fails()){
         return response()->json([
            'status'=> false,
            'errors'=> $validator->errors(),
         ]);
       }

       if(Hash::check($request->old_password , Auth::user()->password)==false){
        session()->flash('error','Your old password is incorrect.');
        return response()->json([
            'status'=> true,
         ]);
       }

       $user = User::find(Auth::user()->id);
       $user->password = Hash::make($request->new_password);
       $user->save();

       session()->flash('success','Password updated successfully');
       return response()->json([
            'status'=> true,
         ]);

    }
}
