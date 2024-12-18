<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index(Request $request){

        $category = Category::where('status', 1)->get();
        $jobTypes = JobType::where('status', 1)->get();

        $jobs = Job::where('status', 1);


        if(!empty($request->keywords)){
            $jobs = $jobs->where(function($query) use($request){
                $query->orWhere('title', 'like', '%'. $request->keywords.'%');
                $query->orWhere('keywords', 'like', '%'. $request->keywords.'%');
            });
        }

        if(!empty($request->location)){
                $jobs = $jobs->where('location', $request->location);
        }

        if(!empty($request->category)){
            $jobs = $jobs->where('category_id', $request->category);
        }
        $jobTypeArray = [];
        if(!empty($request->jobType)){
          $jobTypeArray = explode(',',$request->jobType);

            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        if(!empty($request->exp)){
            $jobs = $jobs->where('exp', $request->exp);
        }

        $jobs = $jobs->with(['jobType', 'category']);
        if( $request->sort == '0'){
            $jobs = $jobs->orderBy('created_at', 'ASC');
        }else{
            $jobs = $jobs->orderBy('created_at', 'DESC');
        }
        
        $jobs = $jobs->paginate(9);

        return view('front.jobs', [
            'category' => $category,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray
        ]);
    }
}
