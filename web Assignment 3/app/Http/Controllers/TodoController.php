<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        // Fixed: changed 'tasks.index' to match your folder
        return view('tasks.index', compact('todos'));
    }

    public function create()
    {
        // Fixed: changed 'todos.create' to 'tasks.create'
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        Todo::create($request->all());
        return redirect()->route('todos.index');
    }

    public function edit(Todo $todo)
    {
        // Fixed: changed 'todos.edit' to 'tasks.edit'
        return view('tasks.edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {
        $todo->update($request->all());
        return redirect()->route('todos.index');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index');
    }
}