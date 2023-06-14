<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Models\PettyCash;
use App\Http\Requests\StorePettyCashRequest;
use App\Http\Requests\UpdatePettyCashRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PettyCashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->query('paginate') === 'false') {
            $data = PettyCash::with($request->query('with') ?? [])->filter($request->only(['search', 'branch', 'number', 'transaction_type', 'tax', 'tax_payment', 'from', 'to', 'currency', 'amount', 'status']))->orderBy('updated_at', 'ASC')->get();
        } else {
            $data = PettyCash::with($request->query('with') ?? [])->filter($request->only(['search', 'branch', 'number', 'transaction_type', 'tax', 'tax_payment', 'from', 'to', 'currency', 'amount', 'status']))->orderBy('updated_at', 'ASC')->paginate($request->query('paginate') ?? 15)->setPath('')->withQueryString();
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
     * @param  \App\Http\Requests\StorePettyCashRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePettyCashRequest $request)
    {
        DB::beginTransaction();

        try {
            $petty_cash = PettyCash::make([
                'number' => $request->input('number'),
                'branch_id' => $request->input('branch_id'),
                'currency' => $request->input('currency'),
                'rate' => $request->input('rate'),
                'grand_total' => $request->input('grand_total'),
                'balance' => $request->input('balance'),
                'source' => $request->input('source'),
                'note' => $request->input('note'),
                'transaction_type' => $request->input('transaction_type'),
                'status' => $request->input('status'),
                'created_by' => $request->input('created_by')
            ]);

            $petty_cash->created_at = Carbon::parse($request->input('created_at'))->format('Y-m-d H:i:s');
            $petty_cash->updated_at = Carbon::parse($request->input('updated_at'))->format('Y-m-d H:i:s');

            $petty_cash->save();

            $petty_cash->details()->createMany($request->input('details'));

            DB::commit();

            return response()->json([
                'message' => __('api.store', ['pluralization' => 'a', 'model' => 'petty cash']),
                'data' => [
                    'petty_cash' => $petty_cash
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
     * @param  \App\Models\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function show(PettyCash $pettyCash)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PettyCash  $pettyCash
     * @return \Illuminate\Http\Response
     */
    public function edit(PettyCash $pettyCash)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePettyCashRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePettyCashRequest $request)
    {
        DB::beginTransaction();

        try {
            $petty_cash = PettyCash::find($request->input('number'));

            $petty_cash->update($request->validatedExcept(['number']));

            $petty_cash->updated_at = Carbon::parse($request->input('updated_at'))->format('Y-m-d H:i:s');

            $petty_cash->save();

            DB::commit();

            return response()->json([
                'message' => __('api.update', ['pluralization' => 'a', 'model' => 'petty cash']),
                'data' => [
                    'petty_cash' => $petty_cash
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
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'number' => 'required|string|exists:petty_cashes,number'
        ]);

        DB::beginTransaction();

        try {
            $petty_cash = PettyCash::findOrFail($request->input('number'));

            $petty_cash->delete();

            DB::commit();

            return response()->json([
                'message' => __('api.destroy.success', ['pluralization' => 'a', 'model' => 'petty cash']),
            ]);
        } catch (Handler $e) {
            DB::rollBack();

            return response()->json($e);
        }
    }
}
