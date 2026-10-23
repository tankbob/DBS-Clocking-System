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

use Dbs\Exports\PaymentExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminPaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

private function buildPaymentExportRows($fromDate, array $logArray)
{
    $rows = [];

    foreach ($logArray as $userName => $jobs) {
        // Username row: value in column A.
        $rows[] = [
            $userName,
        ];

        // Date headings: columns C through I, approval heading in K.
        $dateRow = [
            '', // A
            '', // B
        ];

        for ($i = 0; $i < 7; $i++) {
            $dateRow[] = date(
                'D d/m/Y',
                mktime(
                    0,
                    0,
                    0,
                    date('m', strtotime($fromDate)),
                    date('d', strtotime($fromDate)) + $i,
                    date('Y', strtotime($fromDate))
                )
            );
        }

        $dateRow[] = '';          // J
        $dateRow[] = 'Approved?'; // K

        $rows[] = $dateRow;

        foreach ($jobs as $jobName => $days) {
            // NORMAL row.
            $normalRow = [
                $jobName,
                'NORMAL',
            ];

            for ($i = 0; $i < 7; $i++) {
                $normalRow[] = isset($days[$i]['Normal'])
                    ? $days[$i]['Normal']
                    : '';
            }

            $normalRow[] = ''; // J
            $normalRow[] = $days['Approved'] == 1 ? 'Yes' : 'NO';

            $rows[] = $normalRow;

            // OVERTIME row.
            $overtimeRow = [
                '',
                'OVERTIME',
            ];

            for ($i = 0; $i < 7; $i++) {
                $overtimeRow[] = isset($days[$i]['Overtime'])
                    ? $days[$i]['Overtime']
                    : '';
            }

            // Pad to column K so every row has the same CSV width.
            $overtimeRow[] = ''; // J
            $overtimeRow[] = ''; // K

            $rows[] = $overtimeRow;

            // HOLIDAY row.
            $holidayRow = [
                '',
                'HOLIDAY',
            ];

            for ($i = 0; $i < 7; $i++) {
                $holidayRow[] = isset($days[$i]['Holiday'])
                    ? $days[$i]['Holiday']
                    : '';
            }

            $holidayRow[] = ''; // J
            $holidayRow[] = ''; // K

            $rows[] = $holidayRow;

            // Preserve the blank row created by the old $number += 4.
            $rows[] = array_fill(0, 11, '');
        }
    }

    return $rows;
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
                            ->pluck('id')
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

        foreach(LogTime::with('HourType')->whereIn('job_id', Job::pluck('id'))->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->where('user_id', '=', $user_id)->get() as $logTime){
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

        $hourTypes = HourType::pluck('value', 'id');

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
        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $logTimes = LogTime::where('user_id', '=' , $user_id)->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->whereIn('job_id', Job::pluck('id')->toArray())->with('User', 'Job')->orderBy('user_id')->orderBy('date')->get();

        $user = User::find($user_id);
        
        $logArray = array();
        $logArray[$user->name] = array();
      

   /*     $approvedQuery = \DB::select("Select 
            users.name, jobs.number, MIN(approved) as approved 
            from log_times 
            join users on log_times.user_id = users.id 
            join jobs on log_times.job_id = jobs.id
            where log_times.date >= '".$fromDate."'
            and log_times.date <= '".$toDate."'
            group by users.name, jobs.number"); */

    
            

        $ht = HourType::pluck('value', 'id')->toArray();

        foreach($logTimes as $l){
            $job_name = $l->Job->number;
            $user_name = $l->User->name;
            $log_type = $ht[$l->hour_type_id];
            $log_time = $l->time;
            $log_overtime = $l->overtime;
            $log_date = (1 + date('w', strtotime($l->date))) % 7;
            
            if(!isset($logArray[$user_name][$job_name])) {
                $logArray[$user_name][$job_name] = array();
                for($i = 0; $i <= 6; $i ++){
                    $logArray[$user_name][$job_name][$i] = ['Normal' => 0, 'Holiday' => 0, 'Overtime' => 0];
                }
                $logArray[$user_name][$job_name]['Approved'] = 1;
            }

            if($log_type == 'Holiday'){
                $logArray[$user_name][$job_name][$log_date]['Holiday'] += $log_time;
            }else{           
                $logArray[$user_name][$job_name][$log_date]['Normal'] += $log_time;
            }
            $logArray[$user_name][$job_name][$log_date]['Overtime'] += $log_overtime;

            if($l->approved == 0){
                $logArray[$user_name][$job_name]['Approved'] = 0;
            }
        }

$rows = [];

foreach ($logArray as $userName => $jobs) {
    /*
     * User heading row.
     *
     * Columns:
     * A = username
     */
    $rows[] = [
        $userName,
    ];

    /*
     * Date heading row.
     *
     * A and B are empty.
     * C through I contain the seven dates.
     * J is empty.
     * K contains Approved?
     */
    $dateRow = [
        '',
        '',
    ];

    for ($i = 0; $i < 7; $i++) {
        $dateRow[] = date(
            'D d/m/Y',
            mktime(
                0,
                0,
                0,
                date('m', strtotime($fromDate)),
                date('d', strtotime($fromDate)) + $i,
                date('Y', strtotime($fromDate))
            )
        );
    }

    $dateRow[] = '';
    $dateRow[] = 'Approved?';

    $rows[] = $dateRow;

    foreach ($jobs as $jobName => $days) {
        /*
         * Normal-hours row.
         *
         * A = job name
         * B = NORMAL
         * C through I = daily values
         * J = empty
         * K = approval status
         */
        $normalRow = [
            $jobName,
            'NORMAL',
        ];

        for ($i = 0; $i < 7; $i++) {
            $normalRow[] = isset($days[$i]['Normal'])
                ? $days[$i]['Normal']
                : '';
        }

        $normalRow[] = '';
        $normalRow[] = $days['Approved'] == 1 ? 'Yes' : 'NO';

        $rows[] = $normalRow;

        /*
         * Overtime row.
         */
        $overtimeRow = [
            '',
            'OVERTIME',
        ];

        for ($i = 0; $i < 7; $i++) {
            $overtimeRow[] = isset($days[$i]['Overtime'])
                ? $days[$i]['Overtime']
                : '';
        }

        $rows[] = $overtimeRow;

        /*
         * Holiday row.
         */
        $holidayRow = [
            '',
            'HOLIDAY',
        ];

        for ($i = 0; $i < 7; $i++) {
            $holidayRow[] = isset($days[$i]['Holiday'])
                ? $days[$i]['Holiday']
                : '';
        }

        $rows[] = $holidayRow;

        /*
         * Preserve the blank fourth row that the old
         * "$number += 4" logic introduced between jobs.
         */
        $rows[] = [];
    }
}

return Excel::download(
    new PaymentExport($rows),
    'file.csv',
    ExcelWriter::CSV,
    [
        'Content-Type' => 'text/csv',
    ]
);


    }

    public function paymentCsv(){
        $fromDate = \Request::get('fromDate');
        $toDate = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($fromDate)), date('d', strtotime($fromDate))+6, date('Y', strtotime($fromDate))));

        $logTimes = LogTime::whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->pluck('id')->toArray())->whereIn('job_id', Job::pluck('id')->toArray())->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->with('User', 'Job')->orderBy('user_id')->orderBy('date')->get();

        $users = LogTime::with('User')->whereIn('user_id', User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->pluck('id')->toArray())->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->groupBy('user_id')->get(['user_id']);

        $logArray = array();

   /*     $approvedQuery = \DB::select("Select 
            users.name, jobs.number, MIN(approved) as approved 
            from log_times 
            join users on log_times.user_id = users.id 
            join jobs on log_times.job_id = jobs.id
            where log_times.date >= '".$fromDate."'
            and log_times.date <= '".$toDate."'
            group by users.name, jobs.number"); */

        foreach($users as $user){
            $logArray[$user->User->name] = array();
        }

        $ht = HourType::pluck('value', 'id')->toArray();

        foreach($logTimes as $l){
            $job_name = $l->Job->number;
            $user_name = $l->User->name;
            $log_type = $ht[$l->hour_type_id];
            $log_time = $l->time;
            $log_overtime = $l->overtime;
            $log_date = (1 + date('w', strtotime($l->date))) % 7;
            
            if(!isset($logArray[$user_name][$job_name])) {
                $logArray[$user_name][$job_name] = array();
                for($i = 0; $i <= 6; $i ++){
                    $logArray[$user_name][$job_name][$i] = ['Normal' => 0, 'Holiday' => 0, 'Overtime' => 0];
                }
                $logArray[$user_name][$job_name]['Approved'] = 1;
            }

            if($log_type == 'Holiday'){
                $logArray[$user_name][$job_name][$log_date]['Holiday'] += $log_time;
            }else{           
                $logArray[$user_name][$job_name][$log_date]['Normal'] += $log_time;
            }
            $logArray[$user_name][$job_name][$log_date]['Overtime'] += $log_overtime;

            if($l->approved == 0){
                $logArray[$user_name][$job_name]['Approved'] = 0;
            }
        }


	$rows = $this->buildPaymentExportRows($fromDate, $logArray);

	return Excel::download(new PaymentExport($rows), 'file.csv', ExcelWriter::CSV,   [        'Content-Type' => 'text/csv',    ]);


    }

}
