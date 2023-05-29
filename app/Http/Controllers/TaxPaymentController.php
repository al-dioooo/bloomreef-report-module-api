<?php

namespace App\Http\Controllers;

use App\Models\TaxPayment;
use App\Http\Requests\StoreTaxPaymentRequest;
use App\Http\Requests\UpdateTaxPaymentRequest;

class TaxPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreTaxPaymentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaxPaymentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaxPayment  $taxPayment
     * @return \Illuminate\Http\Response
     */
    public function show(TaxPayment $taxPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaxPayment  $taxPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(TaxPayment $taxPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaxPaymentRequest  $request
     * @param  \App\Models\TaxPayment  $taxPayment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaxPaymentRequest $request, TaxPayment $taxPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaxPayment  $taxPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaxPayment $taxPayment)
    {
        //
    }
}
