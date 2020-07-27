<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Task;
use Illuminate\Support\Facades\Auth;
use App\TaskHistory;

class TaskController extends Controller
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
        return Task::where('userID',Auth::user()->id)->latest()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'desc' => 'required',
            'status' => 'required',
        ]);
        $taskDtls = Task::create([
           'userID' => Auth::user()->id,
           'name' => $request['name'],
           'desc' => $request['desc'],
           'status' => $request['status'],
        ]);
        // save task history
        TaskHistory::create([
           'taskID' => $taskDtls['id'],
           'userID' => Auth::user()->id,
           'status' => $taskDtls['status'],
           'new_status' => $taskDtls['status']
        ]);
        return $taskDtls;
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
        $this->validate($request, [
            'name' => 'required',
            'desc' => 'required',
            'status' => 'required',
        ]);
        $task = Task::findOrFail($id);
        $task->update($request->all());
        // save task history
        TaskHistory::create([
           'taskID' => $id,
           'userID' => Auth::user()->id,
           'status' => $request->status,
           'new_status' => $request->status,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Task::findOrFail($id);
        $user->delete();
        return response()->json([
         'message' => 'Task deleted successfully'
        ]);
    }
}
