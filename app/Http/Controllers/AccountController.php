<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Event\Test\Passed;

class AccountController extends Controller
{
    public function registration(Request $request){
        return view('front.account.registration');
    }

    public function proccessRegistration(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password'
        ]);
        if($validator->passes()){
            $user = new User();
            $user ->name = $request->name;
            $user ->email = $request->email;
            $user ->password = Hash::make($request->password);
            $user ->save();
            
            session()->flash('success', 'You have registerd succesfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function login(Request $request){
        return view('front.account.login');
    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->passes()){
            if(Auth::attempt([
                'email' => $request->email, 
                'password' => $request->password,
            ])){
                return redirect()->route('account.profile');
            }else{
                return redirect()->route('account.login')->with('error', 'email or passwor is incorrect');
            }
        }else{
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }
    public function profile(){

        $id = Auth::user()->id;

        $user = User::where('id', $id)->first();

        return view('front.account.profile', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request){

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:20',
            'email' => 'required|email|unique:users',
        ]);
        if($validator->passes()){
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->desigination = $request->desigination;
            $user->mobile = $request->mobile;
            $user->save();


            session()->flash('success', 'update succes');
            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }
    public function createJob(){

        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();

        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        return view('front.account.job.create', [
            'categories' => $categories,
            'jobTypes' => $jobTypes
        ]);
        
    }


    public function saveJobs(Request $request){

        $rules = [
            'title' => 'required|min:3|max:200',
            'category' => 'required',
            'JobTypes' => 'required', 
            'vacancy' => 'required',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()){

            $job = new Job();
            $job->title = $request->title;
            $job->category_id  = $request->category;
            $job->job_type_id  = $request->JobTypes;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsobility = $request->responsobility;
            $job->qualification = $request->qualification;
            $job->keywords = $request->keywords;
            $job->exp = $request->exp;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_web = $request->company_web;
            $job->save();

            session()->flash('success', 'Job added succesfully');
            return response()->json([
                'status' => true,
                'errors' => []
            ]);


        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function myJobs(){
        $jobs = Job::where('user_id', Auth::user()->id)
        ->with('jobType')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);
    
        return view('front.account.job.my-jobs', [
            'jobs' => $jobs
        ]);
    }

    public function editJobs(Request $request, $id){
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();

        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $id,

        ])->first();

        if($job == null){
            abort(404);
        }
        return view('front.account.job.edit', [
           'categories' => $categories,
           'jobTypes' => $jobTypes,
           'job' => $job
        ]);
    }

    public function updateJob(Request $request, $id){

        $rules = [
            'title' => 'required|min:3|max:200',
            'category' => 'required',
            'JobTypes' => 'required', 
            'vacancy' => 'required',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()){

            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id  = $request->category;
            $job->job_type_id  = $request->JobTypes;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsobility = $request->responsobility;
            $job->qualification = $request->qualification;
            $job->keywords = $request->keywords;
            $job->exp = $request->exp;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_web = $request->company_web;
            $job->save();

            session()->flash('success', 'Job update succesfully');
            return response()->json([
                'status' => true,
                'errors' => []
            ]);


        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

   public function deleteJob(Request $request){
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();

        if($job == null){
            session()->flash('error', 'запись удалена или не найдена');
            return response()->json([
                'status' => true
            ]);

        }
        Job::where('id', $request->jobId)->delete();
        session()->flash('error', 'запись удалена');
        return response()->json([
            'status' => true
        ]);
   }
}
 