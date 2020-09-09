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
        $filters = [
            'search' => $request->search,
            'card' => $request->card,
        ];
        $all_transactions = $this->transaction->all_transactions($filters,$user->id,Transaction::PER_PAGE);
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
            'transaction_groups' => $request->transaction_groups,
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
    public function show(Request $request,$id)
    {
        $user = $request->user();
        $this->transaction->check_user($user->id,$id);

        $transaction = $this->transaction->read($id);
        return new TransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request, $id)
    {
        $user = $request->user();
        $this->transaction->check_user($user->id,$id);

        $date = (Jalalian::fromFormat('Y/m/d H:i:s',$request->date))->toCarbon()->toDateTimeString();
        $transaction = $this->transaction->update([
            'title' => $request->name,
            'amount' => $request->amount,
            'type' => $request->type,
            'date' => $date,
            'user_id' => $request->user()->id,
            'for_user_id' => $request->for_user,
            'card_id' => $request->card,
            'transaction_groups' => $request->transaction_groups,
        ],$id);

        return new TransactionResource($this->transaction->read($transaction->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $user = $request->user();
        $this->transaction->check_user($user->id,$id);

        $this->transaction->delete($id);
        return response('',200);
    }

    // ======================================================================
    // ======================================================================

    /**
     * Get the top transaction infos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_top_info(Request $request){
        $user = $request->user();
        $top_transaction_info = $this->transaction->top_transactions($user->id);
        $response = [
            'total_number_of_transactions' => $top_transaction_info['total_number_of_transactions'],
            'total_positive_transactions' => $top_transaction_info['total_positive_transactions'],
            'total_negative_transactions' => $top_transaction_info['total_negative_transactions'],
            'top_positive' => [
                'name' => $top_transaction_info['top_positive']['title'],
                'date' => date('d M,Y',strtotime($top_transaction_info['top_positive']['date'])),
                'amount' => number_format($top_transaction_info['top_positive']['amount']),
                'unit' => 'T',
                'payed_by' => [
                    'avatar' => $top_transaction_info['top_positive']['user']['avatar'],
                    'name' => $top_transaction_info['top_positive']['user']['name'],
                    'family' => $top_transaction_info['top_positive']['user']['family'],
                ],
            ],
            'top_negative' => [
                'name' => $top_transaction_info['top_negative']['title'],
                'date' => date('d M,Y',strtotime($top_transaction_info['top_negative']['date'])),
                'amount' => number_format($top_transaction_info['top_negative']['amount']),
                'unit' => 'T',
                'payed_by' => [
                    'avatar' => $top_transaction_info['top_negative']['user']['avatar'],
                    'name' => $top_transaction_info['top_negative']['user']['name'],
                    'family' => $top_transaction_info['top_negative']['user']['family'],
                ],
            ],
        ];

        return response()->json($response);
    }

}
