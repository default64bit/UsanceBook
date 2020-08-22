<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Repositories\TransactionRepository\TransactionRepositoryInterface;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class TransactionController extends Controller
{
    private $transaction;

    public function __construct(TransactionRepositoryInterface $transactionRepository){
        $this->transaction = $transactionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $all_transactions = $this->transaction->all_transactions($request->search,$user->id,Transaction::PER_PAGE);
        return TransactionResource::collection($all_transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        $date = (Jalalian::fromFormat('Y/m/d H:i:s',$request->date))->toCarbon()->toDateTimeString();
        $new_transaction = $this->transaction->create([
            'title' => $request->name,
            'amount' => $request->amount,
            'type' => $request->type,
            'date' => $date,
            'user_id' => $request->user()->id,
            'for_user_id' => $request->for_user,
            'card_id' => $request->card,
            'transaction_group_id' => $request->transaction_group,
        ]);

        return new TransactionResource($this->transaction->read($new_transaction->id));
        // return response('',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
