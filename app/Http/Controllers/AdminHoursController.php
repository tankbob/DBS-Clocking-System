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

use mikehaertl\wkhtmlto\Pdf;

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
        $dates = array();
        $dates[0] = date('Y-m-d', strtotime("last Saturday"));
        for($i = 1; $i < 10; $i++){
            $dates[$i] = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($dates[$i - 1])), date('d', strtotime($dates[$i - 1]))-7, date('Y', strtotime($dates[$i - 1]))));
        }

        if(\Request::has('date')){
            $fromDate = \Request::get('date');

            /*Let's make sure than the get date is a saturday*/
            $w = (1+date('w', strtotime($fromDate)))%7;
            $fromDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))-$w, date('Y', strtotime($fromDate))));
        }else{
            $fromDate = $dates[0];
        }

        if(\Request::has('job') && Job::find(\Request::get('job'))){
            $job_id = \Request::get('job');
        }else{
            $job_id = Job::first()->id;
        }

        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $page = 'hours';
        $jobs = Job::lists('number', 'id');

        $logTimes = LogTime::where('job_id', '=', $job_id)->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->with('User', 'HourType')->orderBy('user_id')->orderBy('date')->get();

        $users = LogTime::with('User')->where('job_id', '=', $job_id)->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->groupBy('user_id')->get(['user_id']);

        $logArray = array();

        foreach($users as $user){
            $logArray[$user->User->id] = array();
            for($i = 0; $i <= 6; $i ++){
                $logArray[$user->User->id][$i] = array();
            }
        }
        
        foreach($logTimes as $l){
            $user_id = $l->User->id;
            $log_type = $l->HourType->value;
            $log_time = $l->time;
            $log_overtime = $l->overtime;

            $log_date = (1 + date('w', strtotime($l->date))) % 7;

            if($log_type == 'Holiday'){
                $logArray[$user_id][$log_date]['holiday'] = $l->time;
            }else{
                $logArray[$user_id][$log_date]['time'] = $l->time;
            }
            $logArray[$user_id][$log_date]['overtime'] = $log_overtime;
        }
        $users = User::lists('name', 'id');

        $approved = LogTime::where('date', '>=', $fromDate)->where('date', '<=', $toDate)->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->where('job_id', '=', $job_id)->min('approved');

        $showApproved = false;
        $showUnapproved = false;

        if(!$approved && $toDate < date('Y-m-d')){
           $showApproved = true;
        }else{
            if($approved){
                $showUnapproved = true;
            }
        }

        if($msg){
            return View('backend.hours.hoursView', compact('page', 'job_id', 'fromDate', 'jobs', 'logArray', 'dates', 'users', 'showApproved', 'showUnapproved', 'msg'));
        }else{
            return View('backend.hours.hoursView', compact('page', 'job_id', 'fromDate', 'jobs', 'logArray', 'dates', 'users', 'showApproved', 'showUnapproved'));
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

        if(\Request::has('job') && Job::find(\Request::get('job'))){
            $job_id = \Request::get('job');
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
                'hour_type_id' => HourType::where('value', '=', 'Normal')->first()->id
            ]);
        }
       
        return \Redirect::to('admin/hours?job='.$job_id.'&date='.$date)->with('success', 'The operative has been added successfully');
    }

    public function approve(){
        $job_id = \Request::get('job');
        $fromDate = \Request::get('date');
        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));
        
        LogTime::where('job_id', '=', $job_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->update(['approved' => '1']);

        return self::index('The hours has been approved.');
    } 

    public function unapprove(){
        $job_id = \Request::get('job');
        $fromDate = \Request::get('date');
        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));
        
        LogTime::where('job_id', '=', $job_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->update(['approved' => '0']);

        return self::index('The hours has been unapproved.');
    }

    public function pdf(){

        $fromDate = \Request::get('date');
        $job_id = \Request::get('job');
        $job_id = 3;
        $job = Job::find($job_id);

        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));
        
        $logTimes = LogTime::where('job_id', '=', $job_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->with('User')->orderBy('user_id')->orderBy('date')->get();

        $logArray = array();

        $users = LogTime::with('User', 'HourType')->where('job_id', '=', $job_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->groupBy('user_id')->get(['user_id']);
        foreach($users as $user){
            $logArray[$user->User->name] = array();
            for($i = 0; $i <= 6; $i ++){
                $logArray[$user->User->name][$i] = array();
            }
        }
        
        foreach($logTimes as $l){
            $userName = $l->User->name;
            $log_type = $l->HourType->value;
            $log_time = $l->time;
            $log_overtime = $l->overtime;
            $log_date = (1 + date('w', strtotime($l->date))) % 7;
            

            if($log_type == 'Holiday'){
                $logArray[$userName][$log_date]['holiday'] = $log_time;
            }else{
                $logArray[$userName][$log_date]['time'] = $log_time;
            }
            $logArray[$userName][$log_date]['overtime'] = $log_overtime;
        }
    //   return View('demo')->with('logArray', $logArray)->with('job', $job->number)->with('fromDate', $fromDate)->with('toDate', $toDate);
        $pdf = new Pdf(array(
            'no-outline',         // Make Chrome not complain
            'margin-top'    => 0,
            'margin-right'  => 0,
            'margin-bottom' => 0,
            'margin-left'   => 0,
            'image-dpi' => 300,
            'image-quality' => 80

            // Default page options
        ));

        $data = array();

        $pdf->addPage(View('backend.pdf.hourspdf')->with('logArray', $logArray)->with('job', $job->number)->with('fromDate', $fromDate)->with('toDate', $toDate));

        if (!$pdf->send()) {
            throw new Exception('Could not create PDF: '.$pdf->getError());
        }

        $content = $pdf->toString();
        if ($content === false) {
            throw new Exception('Could not create PDF: '.$pdf->getError());
        }
    }

    public function ajaxEditOvertime(){
        $user_id = \Request::get('user');
        $job_id = \Request::get('job_id');

        $date = date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+\Request::get('day'), date('Y', strtotime(\Request::get('startDate')))));
    
        $logTime = LogTime::where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->where('date', '=', $date)->first();

        if(!$logTime){
            $logTime = new LogTime;
            $logTime->user_id = $user_id;
            $logTime->job_id = $job_id;
            $logTime->hour_type_id = HourType::where('value', '=', 'Normal')->first()->id;
            $logTime->approved = 1;
            $logTime->date = $date;
        }
        $logTime->overtime = \Request::get('value');
        $logTime->save();
        $total = LogTime::where('date', '>=', \Request::get('startDate'))->where('date', '<=', date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+6, date('Y', strtotime(\Request::get('startDate'))))))->where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->sum('overtime');

        return \Response::json(array(
            'success' => true,
            'total' => $total
        ));
    }

    public function ajaxEditTime(){
        $user_id = \Request::get('user');
        $job_id = \Request::get('job_id');

        $date = date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+\Request::get('day'), date('Y', strtotime(\Request::get('startDate')))));
    
        $logTime = LogTime::where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->where('date', '=', $date)->first();

        if(!$logTime){
            $logTime = new LogTime;
            $logTime->user_id = $user_id;
            $logTime->job_id = $job_id;
            $logTime->approved = 1;
            $logTime->date = $date;
        }
        $logTime->hour_type_id = HourType::where('value', '=', 'Normal')->first()->id;
        $logTime->time = \Request::get('value');
        $logTime->save();
        $totalTime = LogTime::where('hour_type_id', '=', HourType::where('value', '=', 'Normal')->first()->id)->where('date', '>=', \Request::get('startDate'))->where('date', '<=', date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+6, date('Y', strtotime(\Request::get('startDate'))))))->where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->sum('time');
        $totalHoliday = LogTime::where('hour_type_id', '=', HourType::where('value', '=', 'Holiday')->first()->id)->where('date', '>=', \Request::get('startDate'))->where('date', '<=', date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+6, date('Y', strtotime(\Request::get('startDate'))))))->where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->sum('time');

        return \Response::json(array(
            'success' => true,
            'totalTime' => $totalTime,
            'totalHoliday' => $totalHoliday
        ));
    }

    public function ajaxEditHoliday(){
        $user_id = \Request::get('user');
        $job_id = \Request::get('job_id');

        $date = date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+\Request::get('day'), date('Y', strtotime(\Request::get('startDate')))));
    
        $logTime = LogTime::where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->where('date', '=', $date)->first();

        if(!$logTime){
            $logTime = new LogTime;
            $logTime->user_id = $user_id;
            $logTime->job_id = $job_id;
            $logTime->approved = 1;
            $logTime->date = $date;
        }
        $logTime->hour_type_id = HourType::where('value', '=', 'Holiday')->first()->id;
        $logTime->time = \Request::get('value');
        $logTime->save();
        $totalTime = LogTime::where('hour_type_id', '=', HourType::where('value', '=', 'Normal')->first()->id)->where('date', '>=', \Request::get('startDate'))->where('date', '<=', date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+6, date('Y', strtotime(\Request::get('startDate'))))))->where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->sum('time');
        $totalHoliday = LogTime::where('hour_type_id', '=', HourType::where('value', '=', 'Holiday')->first()->id)->where('date', '>=', \Request::get('startDate'))->where('date', '<=', date('Y-m-d',  mktime(0, 0, 0, date('m', strtotime(\Request::get('startDate'))), date('d', strtotime(\Request::get('startDate')))+6, date('Y', strtotime(\Request::get('startDate'))))))->where('user_id', '=', $user_id)->where('job_id', '=', $job_id)->sum('time');

        return \Response::json(array(
            'success' => true,
            'totalTime' => $totalTime,
            'totalHoliday' => $totalHoliday
        ));
    }

}
