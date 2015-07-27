<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Job;
use App\LogTime;
use App\User;
use App\UserType;
use App\HourType;

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
    public function index($msg = null)
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
            $logArray[$user->User->id] = array();
            for($i = 0; $i <= 6; $i ++){
                $logArray[$user->User->id][$i] = array();
            }
        }
        
        foreach($logTimes as $l){
            $user_id = $l->User->id;
            $log_type = $l->hour_type_id;
            $log_time = $l->time;
            $log_overtime = $l->overtime;
            $log_date = (1 + date('w', strtotime($l->date))) % 7;
            
            $logArray[$user_id][$log_date] = ['type' => $log_type, 'time' => $log_time, 'overtime' => $log_overtime];
        }

        $users = User::lists('name', 'id');

        $approved = LogTime::where('date', '>=', $fromDate)->where('date', '<=', $toDate)->where('job_id', '=', $job_id)->groupBy('job_id')->get([min(['aproved'])]);
        if(isset($approved[0])){
            $approved =  $approved[0]->aproved;
        }

        $showAproved = false;
        $showUnaproved = false;

        if(!$approved && $toDate < date('Y-m-d')){
           $showAproved = true;
        }else{
            if($approved){
                $showUnaproved = true;
            }
        }

        if($msg){
            return View('backend.hours.hoursView', compact('page', 'job_id', 'fromDate', 'jobs', 'logArray', 'dates', 'users', 'showAproved', 'showUnaproved', 'msg'));
        }else{
            return View('backend.hours.hoursView', compact('page', 'job_id', 'fromDate', 'jobs', 'logArray', 'dates', 'users', 'showAproved', 'showUnaproved'));
        }
        
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

    public function ajaxEdit(){
        $user_id = \Request::get('user');
        $job_id = \Request::get('job_id');

        $date = date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+\Request::get('day'), date('Y', strtotime(\Request::get('startDate')))));
    
        $logTime = LogTime::where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->where('date', '=', $date)->first();

        if(!$logTime){
            $logTime = new LogTime;
            $logTime->user_id = $user_id;
            $logTime->job_id = $job_id;
            $logTime->hour_type_id = HourType::where('value', '=', 'Mon-Fri')->first()->id;
            $logTime->aproved = 1;
            $logTime->date = $date;
        }

        if(\Request::get('overtime') == 1){
            $logTime->overtime = \Request::get('value');

            $logTime->save();

            $total = LogTime::where('date', '>=', \Request::get('startDate'))->where('date', '<=', date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+6, date('Y', strtotime(\Request::get('startDate'))))))->where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->sum('overtime');

        }else{

            $logTime->time = \Request::get('value');

            $logTime->save();

            $total = LogTime::where('date', '>=', \Request::get('startDate'))->where('date', '<=', date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+6, date('Y', strtotime(\Request::get('startDate'))))))->where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->sum('time');
        }

        

        return \Response::json(array(
            'success' => true,
            'total' => $total
        ));
    }

    public function addOperative(){

        $page = 'hours';

        if(\Request::has('fromDate')){
            $fromDate = \Request::get('fromDate');

            /*Let's make sure than the get date is a saturday*/
            $w = (1+date('w', strtotime($fromDate)))%7;
            $fromDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))-$w, date('Y', strtotime($fromDate))));
        }else{
            $fromDate = date('Y-m-d', strtotime("last Saturday"));
        }

        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        if(\Request::has('job_id')){
            $job_id = \Request::get('job_id');
        }else{
            $job_id = Job::first()->id;
        }

        $users = User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->whereNotIn('id', LogTime::where('job_id', '=', $job_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->groupBy('user_id')->lists('user_id')->toArray())->lists('name', 'id');

        $job = Job::find($job_id);

        return View('backend.hours.addOperative', compact('page', 'job', 'job_id', 'fromDate', 'users'));
    }

    public function processAddOperative(){

        $user_id = \Request::get('user_id');
        $job_id = \Request::get('job');
        $date = \Request::get('date');

        $logTime = LogTime::where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->where('date', '=', $date)->first();

        if(!$logTime){
            $logTime = LogTime::create([
                'user_id' => $user_id,
                'job_id' => $job_id,
                'overtime' => 0,
                'time' => 0,
                'date' => $date,
                'hour_type_id' => HourType::where('value', '=', 'Mon-Fri')->first()->id
            ]);
        }
       
        return self::index('The operative has been added successfully.');
    }

    public function approve(){
        $job_id = \Request::get('job');
        $fromDate = \Request::get('date');
        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));
        
        LogTime::where('job_id', '=', $job_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->update(['aproved' => '1']);

        return self::index('The hours has been aproved.');
    } 

    public function unapprove(){
        $job_id = \Request::get('job');
        $fromDate = \Request::get('date');
        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));
        
        LogTime::where('job_id', '=', $job_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->update(['aproved' => '0']);

        return self::index('The hours has been unaproved.');
    }

}
