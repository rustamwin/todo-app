<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoCollection;
use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Todo::with(['children'])
                                    ->where('parent_id', '=', null)
                                    ->orderBy('position')
                                    ->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $todo = Todo::create($data);
        return $todo;
    }

    /**
     * Sort todos.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $data = $request->get('items');
        if (TodoCollection::sortTree($data)) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return response($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $todo = Todo::find($id);
        $todo->fill($request->all());
        if ($todo->save()) {
            return response()->json(['success' => true, 'message' => 'Updated', 'model' => $todo]);
        }
        return response()->json(['success' => false], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function done(Request $request, int $id)
    {
        $todo = Todo::find($id);
        $todo->done = $request->get('done');
        if ($todo->save()) {
            return response()->json(['success' => true, 'message' => 'Done', 'model' => $todo]);
        }
        return response()->json(['success' => false], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            Todo::destroy($id);
            return response(['success' => true]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 'success' => false]);
        }
    }
}
