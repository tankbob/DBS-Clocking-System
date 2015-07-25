<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\LogTime;
use App\User;
use App\HourType;

use App\Http\Requests\MissedHoursRequest;

class AdminPaymentController extends Controller
{
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

        $payment = LogTime::with('user')->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->groupBy('user_id')->get(['user_id', min(['aproved'])]);

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
        }else{
            $fromDate = $dates[0];
        }

        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $user = User::find($user_id);

        $times = array();

        for($i = 0; $i <= 6; $i++){
            $times[$i] = array('overtime' => 0, 'Mon-Fri' => 0, 'Weekends' => 0, 'Holiday' => 0);
        }

        foreach(LogTime::with('HourType')->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->where('user_id', '=', $user_id)->get() as $logTime){
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

        return \Redirect::back();
    }

}
