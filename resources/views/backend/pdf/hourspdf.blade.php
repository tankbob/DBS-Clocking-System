<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        body {
            margin:     0;
            padding:    0;
            width:      21cm;
            height:     29.7cm;
        }

        /* Printable area */
        #print-area {
            position:   relative;
            top:        1cm;
            left:       1cm;
            width:      19cm;
            height:     27.6cm;

            font-size:      10px;
            font-family:    Arial;
        }

        #header {
            height:     104px;
        }
        #footer {
            position:   absolute;
            bottom:     0;
            width:      100%;
            height:     1cm;

            color: #ccc;
            text-align: center;
        }

        h2{
            text-align: center;
            font-size: 20px;
            font-style: normal;
        }

        table, th, td {
           border: 1px solid black;
           border-collapse: collapse;
           text-align: center;
        }
        th,td{
            height: 22px;
        }

        .blueCells{
            background: #8BD9F7;
        }

        .logo{
            background-image: url('http://dbs.app:8000/images/dbs_logo.jpg');
            width: 173px;
            height: 104px;
        }

    </style>
</head>
<body>

    <div id="print-area">
        <div id="header">
            <img width="173" height="104" alt="DBS Contracts" src="data:image/jpg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMtaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NjEwQ0E0RjkwQzRBMTFFNTgwNzNFQjc3OURDRkEwRTQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NjEwQ0E0RkEwQzRBMTFFNTgwNzNFQjc3OURDRkEwRTQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2MTBDQTRGNzBDNEExMUU1ODA3M0VCNzc5RENGQTBFNCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2MTBDQTRGODBDNEExMUU1ODA3M0VCNzc5RENGQTBFNCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv/uAA5BZG9iZQBkwAAAAAH/2wCEAAYEBAQFBAYFBQYJBgUGCQsI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"/>
        </div>


        <div id="content">
            <h2>Hours worked in Job: {{$job}} from {{date('d/m/Y', strtotime($fromDate))}} to {{date('d/m/Y', strtotime($toDate))}}</h2>
            

        </div>



        <table width="100%">
            <thead>
                <th>
                    Operative
                </th>
                <th>
                    Sat
                </th>
                <th>
                    Sun
                </th>
                <th>
                    Mon
                </th>
                <th>
                    Tues
                </th>
                <th>
                    Wed
                </th>
                <th>
                    Thurs
                </th>
                <th>
                    Fri
                </th>
                <th>
                    Total
                </th>
            </thead>
            <tbody class="text-center">
                @foreach($logArray as $user => $weeklyTimes)

                    <tr>
                        <td>
                            {{ $user }}
                        </td>
                        <?php $total = 0; ?>
                        @foreach($weeklyTimes as $day => $time)
                            <td>
                                @if(isset($time['time']) && $time['time'])
                                    {{$time['time']}}
                                    ({{$time['type']}})
                                    <?php $total += $time['time']; ?>

                                @else
                                    -
                                @endif
                            </td>
                        @endforeach

                        <td>
                            <div>
                                {{$total}}
                            </div>
                        </td>

                    </tr>

                    <tr class="blueCells">
                        <td>
                            Overtime
                        </td>
                        <?php $total = 0; ?>
                        @foreach($weeklyTimes as $day => $time)
                            
                            <td>
                                @if(isset($time['overtime']) && $time['overtime'])
                                    {{$time['overtime']}}
                                    <?php $total += $time['overtime']; ?>
                                @else
                                    -
                                @endif
                            </td>
                            
                        @endforeach

                        <td>
                            <div>
                                {{$total}}
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>


        <div id="footer">
            Target Ink © 2015 DBS Contracts Ltd.
        </div>
    </div>

</body>
</html>