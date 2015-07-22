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
        $fromDate = '2015-7-4';

        $date = array();
        $date[0] = date('Y-m-d', strtotime("last Saturday"));
        for($i = 1; $i < 10; $i++){
            $date[$i] = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($date[$i - 1])), date('d', strtotime($date[$i - 1]))-7, date('Y', strtotime($date[$i - 1]))));
        }

        if(!$fromDate){
            $fromDate = $date[0];
        }
        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $page = 'hours';
        $jobs = Job::all();
        $job = $jobs->first();
        $logTimes = LogTime::where('job_id', '=', $job->id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->with('User')->orderBy('user_id')->orderBy('date')->get();

        $logArray = array();

        foreach($logTimes as $l){
            if(!isset($logArray[$l->User->name])){
                $logArray[$l->User->name] = ['O' => array(), 'H' => array(), 'N' => array()];
            }
        
            if($l->time){
                $logArray[$l->User->name]['N'][$l->date] = $l->time;
            }
        }

        dd($logArray);

        return View('backend.hours.hoursView', compact('page', 'jobs', 'logTimes'));
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
