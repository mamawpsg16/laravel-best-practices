<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Task::orderBy('status')->orderBy('order')->get();
        // $details = DB::select('CALL spGetTasks()');
        // dd($details);
       return response(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        $data = $request->validated();
        if($data['status'] == 1){
            $data['start_timestamp'] =  now();
        }
        $task = Task::create($data);
        $details = Task::findOrFail($task->id);
        return response(['data' => $details]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $data = $request->validated();
        $task->update($request->validated());
        $data = Task::findOrFail($task->id);
        return response(['data' => $data]);
    }
    public function updateStatus(Request $request){
        $task = Task::findOrFail($request->id);
        $data = [
            'status' => $request->status,
            'start_timestamp' =>  null
        ];
        if($request->status != 0){
            $data['start_timestamp'] =  now();
        }
        
        if($request->status == 2){
            $data['end_timestamp'] =  now();
        }
        $task->update($data);

        return response(['success' => true]);
    }
    
    public function updateOrder(Request $request){
        $tasks = $request->input('tasks');

        foreach ($tasks as $task) {
            // Find the task by ID
            $data = Task::find($task['id']);
            if ($task) {
                // Update the order column
                $data->order = $task['order'];
                $data->save();
            }
        }
        
        return response(['success' => true]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response(['message' => 'Task has been deleted']);
    }

    public function restoreTask(Task $task){
        $maxOrder = Task::where('user_id', auth()->id())
            ->where('status', 1)
            ->max('order');

        $task->update(['order' =>  $maxOrder + 1, 'status' => 1]);

        return response(['data' => $task]);
    }

    public function getTaskStatusDetails(){
        $taskStatusCounts = Task::select(DB::raw('COUNT(id) as count'), 'status')
                                ->groupBy('status')
                                ->orderBy('status')
                                ->get()
                                ->toArray();
        DD($taskStatusCounts);
        return response(['data' => $taskStatusCounts]);
    }
    
}
