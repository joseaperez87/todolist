<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TodoController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/todo/create",
     *     summary="Creates a new task",
     *     tags={"Todo"},
     *    @OA\Parameter(
     *      name="title",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *      name="description",
     *      in="path",
     *      @OA\Schema(type="text")
     *     ),
     *     @OA\Parameter(
     *      name="end_date",
     *      in="path",
     *      @OA\Schema(type="date")
     *     ),
     *     @OA\Response(response=200, description="Task created successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    function create(Request $request){
        $rules = [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'end_date' => 'nullable|date'
        ];

        $data = $request->validate($rules);
        if (!isset($request->id)) {
            $data['user_id'] = Auth::user()->id;
            $todo = Todo::create($data);
        }else{
            $task = Todo::find($request->id);
            $task->title = $request->title;
            $task->description = $request->description;
            $task->end_date = $request->end_date;
            $todo = $task->save();
        }
        if($todo){
            return response()->json(['res' => true], 200);
        }
        return response()->json(['res' => false], 400);
    }
    /**
     * @OA\Post(
     *     path="/api/todo/get",
     *     summary="Get task information",
     *     tags={"Users"},
     *     @OA\Parameter(
     *      name="id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Task retrieved successfully"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    function get(Request $request) {
        if($request->id){
            $todo = Auth::user()->todoList->find($request->id);
            return response()->json($todo, 200);
        }
        return response()->json(['res' => false],400);
    }
    /**
     * @OA\Post(
     *     path="/api/todo/remove",
     *     summary="Get task information",
     *     tags={"Users"},
     *     @OA\Parameter(
     *      name="id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Task removed successfully"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    function remove(Request $request){
        if ($request->id){
            $todo = Auth::user()->todoList->find($request->id);
            if($todo->delete()){
                return response()->json(['res' => true], 200);
            }
        }
        return response()->json(['res' => false], 400);
    }
}
