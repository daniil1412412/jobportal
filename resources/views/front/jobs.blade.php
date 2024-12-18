@extends('front.layouts.app')
@section('main')
<section class="section-3 py-5 bg-2 ">
    <div class="container">     
        <div class="row">
            <div class="col-6 col-md-10 ">
                <h2>Find Jobs</h2>  
            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    <select name="sort" id="sort" class="form-control">
                        <option value="1" {{ Request::get('sort') == 1 }}>Latest</option>
                        <option value="0" {{ Request::get('sort') == 0 }}>Oldest</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-4 col-lg-3 sidebar mb-4">
                <form name="searchForm" id="searchForm" action="">
                <div class="card border-0 shadow p-4">
                    <div class="mb-4">
                        <h2>Keywords</h2>
                        <input value="{{Request::get('keyword')}}" type="text" name="keyword" id="keyword" placeholder="Keywords" class="form-control">
                    </div>

                    <div class="mb-4">
                        <h2>Location</h2>
                        <input type="text" value="{{Request::get( 'location')}}" name="location" id="location" placeholder="Location" class="form-control">
                    </div>

                    <div class="mb-4">
                        <h2>Category</h2>
                        <select  name="category" id="category" class="form-control">
                            <option value="">Select a Category</option>
                            @if ($category)
                            @foreach ($category as $categories)
                                <option value="{{$categories->id}}">{{$categories->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>                   

                    <div class="mb-4">
                        <h2>Job Type</h2>

                        @if ($jobTypes->isNotEmpty())
                            @foreach ($jobTypes as $jobType)
                             <div class="form-check mb-2"> 
                                <input {{ (in_array($jobType->id, $jobTypeArray)) }} class="form-check-input " name="job_type" type="checkbox" value="{{$jobType->id}}" id="job-type-{{ $jobType->id }}">    
                                <label class="form-check-label " for="job-type-{{ $jobType->id }}">{{$jobType->name}}</label>
                            </div>
                            @endforeach
                        @endif
                
                    </div>

                    <div class="mb-4">
                        <h2>Experience</h2>
                        <select name="exp" id="exp" class="form-control">
                            <option value="">Select Experience</option>
                            <option value="1 ">1 Year</option>
                            <option value="2 ">2 Years</option>
                            <option value="3 ">3 Years</option>
                            <option value="4 ">4 Years</option>
                            <option value="5 ">5 Years</option>
                            <option value="6 ">6 Years</option>
                            <option value="7 ">7 Years</option>
                            <option value="8 ">8 Years</option>
                            <option value="9 ">9 Years</option>
                            <option value="10 ">10 Years</option>
                            <option value="10_plus ">10+ Years</option>
                        </select>
                    </div>    
                    <button type="submit" class="btn btn-primary">Поиск</button>   
                    <a href="{{Route('jobs')}}" class="btn btn-secondary mt-2">Сбросить</a>             
                </div>
                </form>
            </div>
            <div class="col-md-8 col-lg-9 ">
                <div class="job_listing_area">                    
                    <div class="job_lists">
                    <div class="row">
                        @if ($jobs->isNotEmpty())

                        @foreach ($jobs as $job)
                                <div class="col-md-4">
                                <div class="card border-0 p-3 shadow mb-4">
                                    <div class="card-body">
                                        <h3 class="border-0 fs-5 pb-2 mb-0">{{$job->title}}</h3>
                                        <p>{{Str::words($job->description, $words=10, '...')}}</p>
                                        <div class="bg-light p-3 border">
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                <span class="ps-1">{{$job->location}}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                <span class="ps-1">{{$job->jobType->name}}</span>
                                            </p>
                                                    <p>{{$job->category->name}}</p>
                                        
                                                @if (!is_null($job->salary))
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                <span class="ps-1">{{$job->salary}}</span>
                                            </p>
                                                @endif
                                            
                                           
                                        </div>

                                        <div class="d-grid mt-3">
                                            <a href="job-detail.html" class="btn btn-primary btn-lg">Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                            @else
                            <div class="col-md-12">
                                Jobs not found
                            </div>
                        @endif               
                  
                                                 
                    </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pb-0" id="exampleModalLabel">Change Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="image"  name="image">
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mx-3">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('customJs')
    <script>
        $("#searchForm").submit(function(e){
            e.preventDefault();
            var url = '{{route("jobs")}}?';


            var keyword = $("#keyword").val();
            var location = $("#location").val();
            var category = $("#category").val();
            var exp = $("#exp").val()
            var sort = $("#sort").val()
            
            var chekedJobTypes = $("input:checkbox[name='job_type']:checked").map(function(){
                return $(this).val();
            }).get();

            if(category != ""){
                url += '&category='+category;
            }


            if(location != ""){
                url += '&location='+location;
            }

            if(keyword != ""){
                url += '&keywords='+keyword;
            }
            
            if(exp != ""){
                url += '&exp='+exp;
            }

            if(chekedJobTypes.length > 0){
                url += '&jobType='+chekedJobTypes;
            }
            
            url += '&sort='+sort;


            window.location.href=url;


            $("#sort").change(function(){
                $("#searchForm").submit();
            });
        });
    </script>
@endsection