<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Job;
use App\LogTime;

class AdminHoursController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($job_id = null, $fromDate = null)
    {
        if(\Request::has('date')){
            $fromDate = \Request::get('date');
        }else{
            $fromDate = date('Y-m-d', strtotime("last Saturday"));
        }

        if(\Request::has('job')){
            $job_id = \Request::get('job');
        }else{
            $job_id = Job::first()->id;
        }

        $dates = array();
        $dates[0] = date('Y-m-d', strtotime("last Saturday"));
        for($i = 1; $i < 10; $i++){
            $dates[$i] = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($dates[$i - 1])), date('d', strtotime($dates[$i - 1]))-7, date('Y', strtotime($dates[$i - 1]))));
        }

        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $page = 'hours';
        $jobs = Job::lists('number', 'id');       

        $logTimes = LogTime::where('job_id', '=', $job_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->with('User')->orderBy('user_id')->orderBy('date')->get();

        $logArray = array();

        $users = LogTime::with('User')->where('job_id', '=', $job_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->groupBy('user_id')->get(['user_id']);
    
        foreach($users as $user){
            $logArray[$user->User->name] = array();
            for($i = 0; $i <= 6; $i ++){
                $logArray[$user->User->name][$i] = array();
            }
        }
        
        foreach($logTimes as $l){
            $user_name = $l->User->name;
            $log_type = $l->hour_type_id;
            $log_time = $l->time;
            $log_overtime = $l->overtime;
            $log_date = (1 + date('w', strtotime($l->date))) % 7;
            
            $logArray[$user_name][$log_date] = ['type' => $log_type, 'time' => $log_time, 'overtime' => $log_overtime];
        }

        return View('backend.hours.hoursView', compact('page', 'job_id', 'fromDate', 'jobs', 'logArray', 'dates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
