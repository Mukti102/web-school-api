<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function index(Request $request)
    {
        $todoLists = TodoList::where('user_id', $request->user_id)->get();
        return response()->json(['status' => 'success', 'data' => $todoLists], 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'todo' => 'required',
            ]);
            $request['user_id'] = auth()->user()->id;
            TodoList::create($request->all());
            return response()->json(['status' => 'success', 'message' => 'Todo List created successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function checked($id)
    {
        $todoList = TodoList::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$todoList) {
            return response()->json(['message' => 'Todo not found'], 404);
        }
        // Toggle status between 'todo' and 'done'
        $todoList->status = !$todoList->status;
        $todoList->save();
        return response()->json(['status' => 'success', 'new_status' => $todoList->status], 200);
    }

    public function destroy($id)
    {
        try {
            TodoList::destroy($id);
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Deleted is fail because ' . $th->getMessage()], 500);
        }
    }
}
