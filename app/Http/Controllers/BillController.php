<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Models\Bill;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->query('paginate') === 'false') {
            $data = Bill::with(['histories'])->filter($request->only(['search', 'branch', 'number', 'transaction_type', 'tax', 'tax_payment', 'from', 'to', 'currency', 'amount', 'status']))->orderBy('updated_at', 'ASC')->get();
        } else {
            $data = Bill::with(['histories'])->filter($request->only(['search', 'branch', 'number', 'transaction_type', 'tax', 'tax_payment', 'from', 'to', 'currency', 'amount', 'status']))->orderBy('updated_at', 'ASC')->paginate($request->query('paginate') ?? 15)->setPath('')->withQueryString();
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
     * @param  \App\Http\Requests\StoreBillRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBillRequest $request)
    {
        DB::beginTransaction();

        try {
            $bill = Bill::create([
                'number' => $request->input('number'),
                'branch_id' => $request->input('branch_id'),
                'payor_or_payee_code' => $request->input('payor_or_payee_code'),
                'currency' => $request->input('currency'),
                'rate' => $request->input('rate'),
                'discount' => $request->input('discount'),
                'dpp' => $request->input('dpp'),
                'ppn' => $request->input('ppn'),
                'ppn_percentage' => $request->input('ppn_percentage'),
                'advance_payment' => $request->input('advance_payment'),
                'grand_total' => $request->input('grand_total'),
                'balance' => $request->input('balance'),
                'note' => $request->input('note'),
                'type' => $request->input('type'),
                'status' => $request->input('status'),
                'transaction_type' => $request->input('transaction_type'),
                'created_by' => $request->input('created_by'),
                'transaction_date' => $request->input('transaction_date'),
                'due_date' => $request->input('due_date'),
                'reference_number' => $request->input('reference_number'),
            ]);

            $bill->created_at = Carbon::parse($request->input('created_at'))->format('Y-m-d H:i:s');
            $bill->updated_at = Carbon::parse($request->input('updated_at'))->format('Y-m-d H:i:s');

            $bill->save();

            DB::commit();

            return response()->json([
                'message' => 'Successfully created a new bill!',
                'data' => [
                    'bill' => $bill
                ]
            ]);
        } catch (Handler $e) {
            DB::rollBack();

            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBillRequest  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBillRequest $request)
    {
        DB::beginTransaction();

        try {
            $bill = Bill::findOrFail($request->input('number'));

            $data = $request->validatedExcept(['number']);

            $bill->update($data);

            DB::commit();

            return response()->json([
                'message' => 'Successfully updated a bill!',
                'data' => [
                    'bill' => $bill
                ]
            ]);
        } catch (Handler $e) {
            DB::rollBack();

            return response()->json($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
