<?php

namespace Dbs\Http\Controllers;

use Illuminate\Http\Request;

use Dbs\Http\Requests;
use Dbs\Http\Controllers\Controller;

use Dbs\LogTime;
use Dbs\User;
use Dbs\Job;
use Dbs\HourType;
use Dbs\UserType;

class AdminPaymentController extends Controller
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
    public function index()
    {
        $page = 'payment';

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

        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $payment = LogTime::leftJoin('users', 'user_id', '=', 'users.id')
                          ->where('date', '>=', $fromDate)
                          ->where('date', '<=', $toDate)
                          ->groupBy('user_id')
                          ->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)
                            ->lists('id')
                            ->toArray()
                          )
                          ->select(\DB::raw('name, telephone, user_id, MIN(approved) as approved'))
                          ->paginate();

        return view('backend.payment.paymentView', compact('page', 'dates', 'fromDate', 'payment'));
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
    public function show($user_id)
    {
        $page = 'payment';

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

        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $user = User::find($user_id);

        $times = array();

        for($i = 0; $i <= 6; $i++){
            $times[$i] = array('Overtime' => 0, 'Mon-Fri' => 0, 'Weekends' => 0, 'Holiday' => 0);
        }

        foreach(LogTime::with('HourType')->whereIn('job_id', Job::lists('id'))->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->where('user_id', '=', $user_id)->get() as $logTime){
            $w = (1+date('w', strtotime($logTime->date)))%7;
            $type = $logTime->HourType->value;
            if($type == 'Holiday'){
                $times[$w]['Holiday'] += $logTime->time;
            }else{
                //CHECK FOR WEEKENDS
                //$w is the day of the week starting in 0=> sat, 1 => sun
                if($w <= 1){
                    $times[$w]['Weekends'] += $logTime->time;
                }else{
                    $times[$w]['Mon-Fri'] += $logTime->time;
                }
            }
            $times[$w]['Overtime'] += $logTime->overtime;
        }

        $hourTypes = HourType::lists('value', 'id');

        return view('backend.payment.paymentEdit', compact('page', 'fromDate', 'payment', 'user', 'dates', 'times', 'hourTypes'));
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

    public function operativeCsv(){
        $user_id = \Request::get('user_id');
        $fromDate = \Request::get('fromDate');

        $userName = User::find($user_id)->first()->name;

        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $times = array();

        $jobs = LogTime::where('job_id', '>', 0)->whereIn('job_id', Job::lists('id'))->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->groupBy('job_id')->with('Job')->get(['job_id']);

        $logArray = array();

        foreach($jobs as $job){
            $logArray[$job->Job->number] = array();
            for($i = 0; $i <= 6; $i ++){
                $logArray[$job->Job->number][$i] = ['Mon-Fri' => 0, 'Weekends' => 0, 'Holiday' => 0, 'Overtime' => 0];
            }
        }

        foreach(LogTime::with('HourType', 'Job')->whereIn('job_id', Job::lists('id'))->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->where('user_id', '=', $user_id)->get() as $logTime){
            $w = (1+date('w', strtotime($logTime->date)))%7;
            $type = $logTime->HourType->value;
            $jobNumb = $logTime->Job->number;
            //NEED TO CHECK SAME THAN THE OTHER
            if($type == 'Holiday'){
                $logArray[$jobNumb][$w]['Holiday'] += $logTime->time;
            }else{
                //CHECK FOR WEEKENDS
                //$w is the day of the week starting in 0=> sat, 1 => sun
                if($w <= 1){
                   $logArray[$jobNumb][$w]['Weekends'] += $logTime->time;
                }else{
                    $logArray[$jobNumb][$w]['Mon-Fri'] += $logTime->time;
                }
            }
            $logArray[$jobNumb][$w]['Overtime'] = $logTime->overtime;
        }

        $approvement = LogTime::leftJoin('jobs', 'job_id', '=', 'jobs.id')->where('user_id', '=', $user_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->whereIn('job_id', Job::lists('id')->toArray())->groupBy('job_id')->get(['number', \DB::raw('MIN(approved) as approved')]);

        foreach($approvement as $ap){
            $logArray[$ap->number]['approved'] = $ap->approved;
        }

        $excel = \Excel::create('file', function($excel) use ($fromDate, $toDate, $logArray){
        $excel->setTitle('Payment');
            $excel->sheet('Payment', function($sheet)  use ($fromDate, $toDate, $logArray){
                $sheet->cell('A1', 'Operative names');
                $sheet->cell('B1', 'Hour type');
                $letter = 'C';
                for($i = 0; $i <7; $i++){
                    $sheet->cell($letter.'1', date('D d/m/Y', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+$i, date('Y', strtotime($fromDate)))));
                    $letter++;
                }
                $sheet->cell('J1', 'Approved');

                $number = 2;

                $letters = [
                    0 => 'C',
                    1 => 'D',
                    2 => 'E',
                    3 => 'F',
                    4 => 'G',
                    5 => 'H',
                    6 => 'I',
                ];

                foreach($logArray as $name => $days){
                    $letter = 'C';
                    $sheet->cell('A'.$number, $name);
                    $sheet->cell('B'.$number, 'Mon-Fri');
                    $sheet->cell('B'.($number+1), 'Weekends');
                    $sheet->cell('B'.($number+2), 'Holiday');
                    $sheet->cell('B'.($number+3), 'Overtime');

                    
                    foreach(['Mon-Fri' => 0, 'Weekends' => 1, 'Holiday' => 2, 'Overtime' => 3] as $type => $offset){
                        for($i = 0; $i <7; $i++){
                            $sheet->cell($letters[$i].($number+$offset), $days[$i][$type]);
                        }
                    }

                    if(!isset($days['approved'])){

                    }elseif($days['approved']){
                        $sheet->cell('J'.$number, 'Yes');
                    }else{
                        $sheet->cell('J'.$number, 'No');
                    }

                    $number += 4;    
                }
            });
        })->download('csv');
    }

    public function paymentCsv(){
        $fromDate = \Request::get('fromDate');
        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $logTimes = LogTime::whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->with('User')->orderBy('user_id')->orderBy('date')->get();

        $users = LogTime::with('User')->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->groupBy('user_id')->get(['user_id']);

        $logArray = array();

        foreach($users as $user){
            $logArray[$user->User->name] = array();
            for($i = 0; $i <= 6; $i ++){
                $logArray[$user->User->name][$i] = ['Mon-Fri' => 0, 'Weekends' => 0, 'Holiday' => 0, 'Overtime' => 0];
            }
        }

        $ht = HourType::lists('value', 'id')->toArray();

        foreach($logTimes as $l){
            $user_name = $l->User->name;
            $log_type = $ht[$l->hour_type_id];
            $log_time = $l->time;
            $log_overtime = $l->overtime;
            $log_date = (1 + date('w', strtotime($l->date))) % 7;
            
            if($log_type == 'Holiday'){
                $logArray[$user_name][$log_date]['Holiday'] += $log_time;
            }else{
                //CHECK FOR WEEKENDS
                if($log_date <= 1){
                    $logArray[$user_name][$log_date]['Weekends'] += $log_time;
                }else{
                    $logArray[$user_name][$log_date]['Mon-Fri'] += $log_time;
                }
            }
            $logArray[$user_name][$log_date]['Overtime'] += $log_overtime;
        }

        $excel = \Excel::create('file', function($excel) use ($fromDate, $toDate, $logArray){
        $excel->setTitle('Payment');
            $excel->sheet('Payment', function($sheet)  use ($fromDate, $toDate, $logArray){
                $sheet->cell('A1', 'Operative names');
                $sheet->cell('B1', 'Hour type');
                $letter = 'C';
                for($i = 0; $i <7; $i++){
                    $sheet->cell($letter.'1', date('D d/m/Y', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+$i, date('Y', strtotime($fromDate)))));
                    $letter++;
                }
                $number = 2;

                $letters = [
                    0 => 'C',
                    1 => 'D',
                    2 => 'E',
                    3 => 'F',
                    4 => 'G',
                    5 => 'H',
                    6 => 'I',
                ];

                foreach($logArray as $name => $days){
                    $letter = 'C';
                    $sheet->cell('A'.$number, $name);
                    $sheet->cell('B'.$number, 'Mon-Fri');
                    $sheet->cell('B'.($number+1), 'Weekends');
                    $sheet->cell('B'.($number+2), 'Holiday');
                    $sheet->cell('B'.($number+3), 'Overtime');

                    
                    foreach(['Mon-Fri' => 0, 'Weekends' => 1, 'Holiday' => 2, 'Overtime' => 3] as $type => $offset){
                        for($i = 0; $i <7; $i++){
                            $sheet->cell($letters[$i].($number+$offset), $days[$i][$type]);
                        }
                    }

                    $number += 4;    
                }
            });
        })->download('csv');

    }

}