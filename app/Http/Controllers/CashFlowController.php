<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->query('paginate') === 'false') {
            $data = CashFlow::with('detail')->filter($request->only(['status', 'from', 'to']))->orderBy('updated_at', 'ASC')->get();
        } else {
            $data = CashFlow::with('detail')->filter($request->only(['status', 'from', 'to']))->orderBy('updated_at', 'ASC')->paginate($request->query('paginate') ?? 15)->setPath('')->withQueryString();
        }

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\CashFlow  $cashFlow
     * @return \Illuminate\Http\Response
     */
    public function show(CashFlow $cashFlow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CashFlow  $cashFlow
     * @return \Illuminate\Http\Response
     */
    public function edit(CashFlow $cashFlow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CashFlow  $cashFlow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CashFlow $cashFlow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CashFlow  $cashFlow
     * @return \Illuminate\Http\Response
     */
    public function destroy(CashFlow $cashFlow)
    {
        //
    }
}
