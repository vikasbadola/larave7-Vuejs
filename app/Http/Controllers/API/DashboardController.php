<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $taskList = \DB::select("SELECT `status`,DATE_FORMAT(created_at,'%i') as dte FROM task_histories WHERE  
            created_at BETWEEN DATE_FORMAT((DATE_SUB(NOW(),INTERVAL 1 HOUR)), '%Y-%m-%d %H:00:00') AND 
            DATE_FORMAT(NOW(), '%Y-%m-%d %H:00:00') AND userID = '$userId' 
            GROUP BY taskID,status order by id");
        $tasksArr = [];
        foreach($taskList as $tasks){
            $tasksArr[ltrim($tasks->dte,0)] = $tasks;
        }
        ksort($tasksArr);
        $tasksPending = [];
        $totalTasks = [];
        $count = 0;
        $totalCount = 0;
        for($i=1;$i<=60;$i++){
            if(isset($tasksArr[$i]) && $tasksArr[$i]->status == 'P'){
                $tasksPending[] = $count++;
                $totalTasks[] = $totalCount++;
            } else if(isset($tasksArr[$i]) && $tasksArr[$i]->status == 'C') {
                $tasksPending[] = $count--;
                $totalTasks[] = $totalCount;
            } else {
                $tasksPending[] = $count+0;
                $totalTasks[] = $totalCount;
            }
        }
        $lastHourMinutes = range(1,60,1);
        $minutes = array();
        foreach ($lastHourMinutes as $value){
            $valueInteval = $value-1;
            $minutes[] = $valueInteval.'-'.$value;
        }
        return array(
            'minutes'=>array($minutes),
            'pendingTasks'=>array($tasksPending),
            'expecTasks'=>array($totalTasks),
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
