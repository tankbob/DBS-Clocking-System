<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\LogTime;
use App\User;
use App\Job;
use App\HourType;
use App\UserType;

use App\Http\Requests\MissedHoursRequest;

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

        $fromDate = \Request::get('date');
        if(!$fromDate){
            $fromDate = $dates[0];
        }

        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $payment = LogTime::leftJoin('users', 'user_id', '=', 'users.id')->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->groupBy('user_id')->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->lists('id')->toArray())->get(['name', 'telephone', 'user_id', \DB::raw('MIN(aproved) as approved')]);

        return View('backend.payment.paymentView', compact('page', 'dates', 'fromDate', 'payment'));
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
            $times[$i] = array('overtime' => 0, 'Mon-Fri' => 0, 'Weekends' => 0, 'Holiday' => 0);
        }

        foreach(LogTime::with('HourType')->whereIn('job_id', Job::lists('id'))->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->where('user_id', '=', $user_id)->get() as $logTime){
            $w = (1+date('w', strtotime($logTime->date)))%7;
            $type = $logTime->HourType->value;
            $times[$w][$type] += $logTime->time;
            $times[$w]['overtime'] += $logTime->overtime;
        }

        $hourTypes = HourType::lists('value', 'id');

        $missed = LogTime::where('date', '=', $fromDate)->where('job_id', '=', -1)->where('user_id', '=', $user_id)->first();

        return View('backend.payment.paymentEdit', compact('page', 'fromDate', 'payment', 'user', 'dates', 'times', 'hourTypes', 'missed'));
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

    public function addMissedHours(MissedHoursRequest $request){
        $logTime = LogTime::where('user_id', '=', $request->get('user_id'))->where('date', '=', $request->get('fromDate'))->where('job_id', '=', -1)->first();

        if(!$logTime){
            $logTime = new LogTime;
        }

        $logTime->user_id = $request->get('user_id');
        $logTime->date = $request->get('fromDate');
        $logTime->job_id = -1;
        $logTime->aproved = 1;
        if($request->get('hour_type_id') == 'overtime'){
            $logTime->hour_type_id = 1;
            $logTime->time = 0;
            $logTime->overtime = $request->get('time');
        }else{
            $logTime->hour_type_id = $request->get('hour_type_id');
            $logTime->time = $request->get('time');
            $logTime->overtime = 0;
        }
        $logTime->save();
        var_dump($logTime->id);

        return \Redirect::back()->with('success', 'The missed hours has been edited.');
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
                $logArray[$job->Job->number][$i] = ['Mon-Fri' => 0, 'Weekends' => 0, 'Holiday' => 0, 'overtime' => 0];
            }
        }

        foreach(LogTime::with('HourType', 'Job')->whereIn('job_id', Job::lists('id'))->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->where('user_id', '=', $user_id)->get() as $logTime){
            $w = (1+date('w', strtotime($logTime->date)))%7;
            $type = $logTime->HourType->value;
            $jobNumb = $logTime->Job->number;
            $logArray[$jobNumb][$w][$type] = $logTime->time;
            $logArray[$jobNumb][$w]['overtime'] = $logTime->overtime;
        }

        $missed = LogTime::with('HourType')->where('job_id', '=', '-1')->where('date', '>=', $fromDate)->where('user_id', '=', $user_id)->first();

        $excel = \Excel::create('file', function($excel) use ($fromDate, $toDate, $logArray, $missed){
        $excel->setTitle('Payment');
            $excel->sheet('Payment', function($sheet)  use ($fromDate, $toDate, $logArray, $missed){
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
                    $sheet->cell('B'.($number+3), 'overtime');

                    
                    foreach(['Mon-Fri' => 0, 'Weekends' => 1, 'Holiday' => 2, 'overtime' => 3] as $type => $offset){
                        for($i = 0; $i <7; $i++){
                            $sheet->cell($letters[$i].($number+$offset), $days[$i][$type]);
                        }
                    }

                    $number += 4;    
                }

                if($missed){
                    if($missed->time){
                        $sheet->cell('A'.$number, 'Missed Hours');
                        $sheet->cell('B'.$number, $missed->HourType->value);
                        $sheet->cell('C'.$number, $missed->time);
                    }elseif($missed->overtime){
                        $sheet->cell('A'.$number, 'Missed Hours');
                        $sheet->cell('B'.$number, 'overtime');
                        $sheet->cell('C'.$number, $missed->overtime);
                    }
                    
                }
            });
        })->download('xls');
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
                $logArray[$user->User->name][$i] = ['Mon-Fri' => 0, 'Weekends' => 0, 'Holiday' => 0, 'overtime' => 0];
            }
            $logArray[$user->User->name]['missed'] = ['Mon-Fri' => 0, 'Weekends' => 0, 'Holiday' => 0, 'overtime' => 0];
        }

        $ht = HourType::lists('value', 'id')->toArray();

        foreach($logTimes as $l){
            $user_name = $l->User->name;
            $log_type = $ht[$l->hour_type_id];
            $log_time = $l->time;
            $log_overtime = $l->overtime;
            $log_date = (1 + date('w', strtotime($l->date))) % 7;
            
            if($l->job_id == -1){
                if($log_overtime && $log_type == 'Mon-Fri'){
                    $logArray[$user_name]['missed']['overtime'] += $log_overtime;
                }else{
                    $logArray[$user_name]['missed'][$log_type] += $log_time;
                }
            }else{
                $logArray[$user_name][$log_date][$log_type] += $log_time;
                $logArray[$user_name][$log_date]['overtime'] += $log_overtime;
            }
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
                $sheet->cell('J1', 'Missed hours');

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
                    $sheet->cell('B'.($number+3), 'overtime');

                    
                    foreach(['Mon-Fri' => 0, 'Weekends' => 1, 'Holiday' => 2, 'overtime' => 3] as $type => $offset){
                        for($i = 0; $i <7; $i++){
                            $sheet->cell($letters[$i].($number+$offset), $days[$i][$type]);
                        }
                        $sheet->cell('J'.($number+$offset), $days['missed'][$type]);
                    }

                    $number += 4;    
                }
            });
        })->download('xls');

    }

}
