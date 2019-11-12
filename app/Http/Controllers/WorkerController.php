<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        return view('workers.index');
    }

    public function edit(int $id)
    {
        return view('workers.edit', [
            'id' => $id
        ]);
    }

    public function create()
    {
        return view('workers.create');
    }
}
