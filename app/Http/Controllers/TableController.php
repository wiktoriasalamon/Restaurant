<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWT;

class TableController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('tables/index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $table = Table::find($id);
        return view('tables/edit', compact(['table', 'id']));
    }

    /**
     * Show the form for show th table.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $table = Table::find($id);
        return view('tables/show', compact(['table', 'id']));
    }


    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function waiterIndex()
    {
        return view('tables/waiterIndex');
    }
}
