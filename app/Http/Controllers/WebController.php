<?php

namespace Dbs\Http\Controllers;

use Illuminate\Http\Request;

use Dbs\Http\Requests;
use Dbs\Http\Controllers\Controller;

use Dbs\User;
use Dbs\LogTime;
use Dbs\Job;
use Dbs\HourType;

use Dbs\Http\Requests\SignInRequest;
use Dbs\Http\Requests\AddJobRequest;

class WebController extends Controller
{

    public function __construct()
    {
        $this->middleware('operative');
    }


    public function index()
    {
        $logTimes = LogTime::where('user_id', '=', \Auth::user()->id)->where('date', '=', date('Y-m-d'))->whereIn('job_id', Job::lists('id')->toArray())->with('Job', 'HourType')->get();
        $hourTypes = HourType::lists('value', 'id');
        if(count($logTimes)){
            //Redirect to the edit times page
        	return view('frontend.editTimes', compact('logTimes', 'hourTypes'));
        }else{
            //First time the guy log time today, redirect to the sign in page
            $jobs = Job::where('active', '=', 1)->lists('number', 'id');         
            return view('frontend.selectJob', compact('jobs', 'hourTypes'));
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
    	}elseif(date_diff(date_create(date('Y-m-d')), date_create($date))->format('%a') > ((1 + date('w')) % 7)){
    			//NO allow to check more than 1 week.
    		return \Redirect::to('/');
    	}else{
            $hourTypes = HourType::lists('value', 'id');
    		$logTimes = LogTime::where('user_id', '=', \Auth::user()->id)->where('date', '=', $date)->whereIn('job_id', Job::lists('id')->toArray())->with('Job', 'HourType')->get();
    		return view('frontend.editTimes', compact('logTimes', 'date', 'hourTypes'));
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
    	return view('frontend.addJob', compact('jobs', 'hourTypes'));
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
