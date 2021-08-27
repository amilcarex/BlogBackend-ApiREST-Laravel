<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use App\Helpers\FormatTime;

class TaskController extends Controller
{
    //
    public function listTasks(Request $request)
    {
        $order = $request->order ?: 'desc';
        $field = $request->field ?: 'created_at';
        $search = $request->search ?: '';
        $per_page = intval($request->perPage) ?: 5;
        if ($search != '') {
            $tasks = Task::Select('id', 'name', 'task_id', 'created_at',  'end_at', 'status_id')->where('name', 'like', '%' . $search . '%')->orderBy($field, $order)->whereNull('task_id')->with('childrenTasks')->paginate($per_page);
        } else {
            $tasks = Task::Select('id', 'name', 'task_id', 'created_at', 'end_at', 'status_id')->orderBy($field, $order)->whereNull('task_id')->with('childrenTasks')->paginate($per_page);
        }


        foreach ($tasks as $task) {
            $childrens = Task::Select('status_id')->where('task_id', '=', $task->id)->get();
            $childrens_array = [];
            $completed = [];
            foreach ($childrens as $children) {

                array_push($childrens_array, $children->status_id);

                if ($children->status_id == 3) {
                    array_push($completed, $children->status_id);
                }
            }
            if (count($childrens_array) > 0) {
                $task->progress = intval((count($completed) / count($childrens_array)) * 100);
            } else {
                if (count($childrens_array) <= 0) {
                    if ($task->status_id == 3) {
                        $task->progress = 100;
                    } else {
                        $task->progress = 0;
                    }
                }
            }
        }
        return response()->json([
            'pagination' => [
                'total' => $tasks->total(),
                'currentPage' => $tasks->currentPage(),
                'perPage' => $tasks->perPage(),
                'last_page' => $tasks->lastPage(),
                'from' => $tasks->firstItem(),
                'to' => $tasks->lastItem(),
            ],
            'tasks' => $tasks
        ]);
    }
    public function create(Request $request)
    {


        $this->validate(
            $request,
            [
                'name' => 'required|string|max:60',
                'description' => 'required|string|max:150',
                'date_to_end' => 'required'
            ]
        );
        $min_date = date('Y-m-d');
        if ($request->date_to_end < $min_date) {
            return response()->json(['errors' => 'The minimum date is today']);
        }
        $date_to_end = $request->date_to_end;
        $name = $request->name != "null" ? $request->name : null;
        $description = $request->description != "null" ? $request->description : null;
        $parent_id = $request->parent != "false" ? intval($request->parent) : null;
        if ($name  == null || $description == null) :
            return response()->json(['error' => 'Name and Description Cannot be null']);
        endif;
      

        $task = Task::create([
            'name' => $name,
            'description' => $description,
            'date_to_end' => $date_to_end,
            'task_id' => $parent_id,
        ]);
        if ($parent_id != null) {
            $parent = Task::find($parent_id);
            $parent->status_id = 2;
            $parent->update();
            
        }
        if ($task) {
            return response()->json(['success' => 'Task Succesfully created', 'task' => $task]);
        }
    }

    public function get(Request $request)
    {

        $task = Task::find($request->id);

        return response()->json($task);
    }
    public function statuses()
    {
        $statuses =  DB::table('statuses')->select('id', 'name')->get();
        return response()->json($statuses);
    }

    public function parents()
    {
        $tasks = Task::whereNull('task_id')->get();
        return response()->json($tasks);
    }

    public function children(Request $request)
    {
        $order = $request->order ?: 'desc';
        $field = $request->field ?: 'created_at';
        $search = $request->search ?: '';
        $per_page = intval($request->perPage) ?: 5;
        $task_id = intval($request->id);
        if ($search != '') {
            $tasks = Task::Select('id', 'name', 'task_id', 'created_at', 'updated_at', 'end_at', 'date_to_end', 'status_id')->where('task_id', '=', $task_id)->where('name', 'like', '%' . $search . '%')->orderBy($field, $order)->paginate($per_page);
        } else {
            $tasks = Task::Select('id', 'name', 'task_id', 'created_at', 'updated_at', 'end_at', 'date_to_end', 'status_id')->where('task_id', '=', $task_id)->orderBy($field, $order)->paginate($per_page);
        }

        return response()->json([
            'pagination' => [
                'total' => $tasks->total(),
                'currentPage' => $tasks->currentPage(),
                'perPage' => $tasks->perPage(),
                'last_page' => $tasks->lastPage(),
                'from' => $tasks->firstItem(),
                'to' => $tasks->lastItem(),
            ],
            'tasks' => $tasks
        ]);
    }
    public function update(Request $request, Task $task)
    {

        $date_time = date('Y-m-d H:i:s');
        $date = strtotime('-4 hour', strtotime($date_time));
        $date = date('Y-m-d H:i:s', $date);

        $name = $request->name != "null" ? $request->name : null;
        $description = $request->description != "null" ? $request->description : null;
        $status = $request->status_id != "null" ? $request->status_id : null;
        if ($name == null || $description == null) {
            return response()->json(['errors' => 'Name and Description cannot be null']);
        }
        $task->status_id = $status;
        $task->name = $name;
        $task->description = $description;
        if ($status == 3) {
            $task->end_at = $date;
        }


        $task->update();

        if ($task->update()) {

            if ($task->task_id != null) {
                $parent_task = Task::find($task->task_id);
                $childrens = Task::Select('status_id')->where('task_id', '=', $parent_task->id)->get();
                $childrens_array = [];
                $completed = [];
                foreach ($childrens as $children) {

                    array_push($childrens_array, $children->status_id);

                    if ($children->status_id == 3) {
                        array_push($completed, $children->status_id);
                    }
                }
                if (count($childrens_array) > 0) {

                    $progress = intval((count($completed) / count($childrens_array)) * 100);

                    if ($progress == 100) {
                        $parent_task->status_id = 3;
                        $parent_task->end_at = $date;
                        $parent_task->update();
                    }
                    if ($progress < 100) {
                        $parent_task->status_id = 2;
                        $parent_task->update();
                    }
                }
            }
            return response()->json([
                'success' => 'Task Updated Succesfully', 'task' => $task
            ]);
        }
    }
    public function destroy($id)
    {

        $task = Task::find($id);
        if ($task->delete()) {

            return response()->json(['success' => 'Category Successfully Delete.']);
        } else {
            return response()->json(['errors' => 'Failed to delete category.']);
        }
    }

    public function statistics()
    {
        $months_array = [];
        $tasks_array =[];
        $completeTasks_array =[];
        $incompleteTasks_array =[];
        $total_tasks = [];
        $months = 4;
        $year = date('Y');
        for ($i = 0; $i < $months; $i++) {
            $date = date('F', strtotime('-' . $i . ' month')); // previous month
            $month = date('m', strtotime('-'.$i.' month'));
            $complete_tasks = count(Task::whereNull('task_id')->where('status_id', '=', 3)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->get());
            $incomplete_tasks = count(Task::whereNull('task_id')->where('status_id', '<>', 3)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->get());
            $date = substr($date, 0, 3);
            $tasks_per_month = $complete_tasks + $incomplete_tasks;
            if($i == 0){
                $completed_current_month = $complete_tasks;
            }
            if($i == 1){
                $completed_previous_month = $complete_tasks;
            }
            array_push($total_tasks, $tasks_per_month);
            array_push($months_array, $date);
            array_push($completeTasks_array, $complete_tasks);
            array_push($incompleteTasks_array, $incomplete_tasks);
        }
        $completed_annual = count(Task::whereNull('task_id')->where('status_id', '=', 3)->whereYear('created_at', '=', $year)->get());
        $plus = 0;
        if(Task::whereNull('task_id')->orderBy('updated_at', 'desc')->first()){
            $updated =  Task::whereNull('task_id')->orderBy('updated_at', 'desc')->first();
            $updated = FormatTime::LongTimeFilter($updated->updated_at);
        }else{
            $updated = null;
        }   
        foreach($total_tasks as $tasks){
            $plus += $tasks;
        }
        $total = $plus;
        $completed = 0;
        foreach($completeTasks_array as $complete){
            $completed += $complete;
        }
        $incompleted = $total - $completed;
        
        $plus = (round(($plus + 5 / 2) / 5) * 5)+5;
        $months_array = array_reverse($months_array);
        $completeTasks_array = array_reverse($completeTasks_array);
        $incompleteTasks_array = array_reverse($incompleteTasks_array);

        array_push($tasks_array, $completeTasks_array);
        array_push($tasks_array, $incompleteTasks_array);
        return response()->json([ 'labels' => $months_array, 'series' => $tasks_array, 'high' => $plus, 'updated' => $updated, 'total' => $total, 'completed' => $completed, 'incompleted' => $incompleted, 'completed_annual' => $completed_annual, 'current_month' => $completed_current_month, 'last_month' => $completed_previous_month]);
    }

    public function pendingTasks(){
        $tasks = Task::whereNull('task_id')->where('status_id', '<>', 3)->orderBy('created_at', 'asc')->take(10)->get();
        foreach($tasks as $task){
            $task->tab = $task->name; 
        }
        return response()->json($tasks);
    }
}
