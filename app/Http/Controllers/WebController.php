<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\LogTime;
use App\Job;
use App\HourType;

use App\Http\Requests\SignInRequest;
use App\Http\Requests\AddJobRequest;

class WebController extends Controller
{

    public function __construct()
    {
        $this->middleware('operative');
    }


    public function index()
    {
        $logTimes = LogTime::where('user_id', '=', \Auth::user()->id)->where('date', '=', date('Y-m-d'))->with('Job', 'HourType')->get();
        if(count($logTimes)){
            //Redirect to the edit times page
        	return View('frontend.editTimes', compact('logTimes'));
        }else{
            //First time the guy log time today, redirect to the sign in page
            $jobs = Job::where('active', '=', 1)->lists('number', 'id');
            $hourTypes = HourType::lists('value', 'id');
            return View('frontend.selectJob', compact('jobs', 'hourTypes'));
        }
        
    }

    public function signIn(SignInRequest $request){
        $logTime = new LogTime;
        $logTime->fill($request->all());
        $logTime->user_id = \Auth::user()->id;
        $logTime->date = date('Y-m-d');
        $logTime->save();
        return \Redirect::to('/');
    }

    public function changeDate(){
    	$date = \Request::get('date');
    	if($date == date('d/m/y')){
    		return \Redirect::to('/');
    	}else{
    		return \Redirect::to('/'.$date);
    	}
    }

    public function showDate($date){
    	if($date == date('Y-m-d')){
    		return \Redirect::to('/');
    	}elseif(date_diff(date_create(date('Y-m-d')), date_create($date))->format('%a') > 6){
    			//NO allow to check more than 1 week.
    		return \Redirect::to('/');
    	}else{
    		$logTimes = LogTime::where('user_id', '=', \Auth::user()->id)->where('date', '=', $date)->with('Job', 'HourType')->get();
    		return View('frontend.editTimes', compact('logTimes', 'date'));
    	}
    }

    public function editTimes(){
    	foreach(\Request::except('_token') as $id => $values){
    		$logTime = LogTime::find($id);
    		$logTime->fill($values);
    		$logTime->save();
    	}
    	return \Redirect::to('/')->with('success', 'The times has been saved.');
    }

    public function addJob(){
		$jobs = Job::where('active', '=', '1')->whereNotIn('id', LogTime::where('user_id', '=', \Auth::user()->id)->where('date', '=', date('Y-m-d'))->lists('job_id')->toArray())->lists('number', 'id');
		$hourTypes = HourType::lists('value', 'id');
    	return View('frontend.addJob', compact('jobs', 'hourTypes'));
    }

    public function processAddJob(AddJobRequest $request){
    	$logTime = new LogTime;
        $logTime->job_id = $request->get('job_id');
        $logTime->user_id = \Auth::user()->id;
        $logTime->date = date('Y-m-d');
        $logTime->hour_type_id = LogTime::where('user_id', '=', \Auth::user()->id)->where('date', '=', date('Y-m-d'))->with('Job', 'HourType')->first()->hour_type_id;
        $logTime->save();
        return \Redirect::to('/')->with('success', 'The job has been added.');;
    }
}
