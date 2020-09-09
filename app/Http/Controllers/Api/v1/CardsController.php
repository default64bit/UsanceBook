<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\CardsRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Repositories\CardRepository\CardRepositoryInterface;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    private $card;

    public function __construct(CardRepositoryInterface $cardRepository){
        $this->card = $cardRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if($request->has('all')){
            $per_page = 'all';
        }else{ $per_page = Card::PER_PAGE; }

        $all_cards = $this->card->all_cards($request->search,$user->id,$per_page);
        return CardResource::collection($all_cards);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CardsRequest $request)
    {
        $new_card = $this->card->create([
            'bank' => $request->bank_name,
            'number' => $request->card_number,
            'user_id' => $request->user()->id,
        ]);

        return new CardResource($this->card->read($new_card->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $this->card->check_user($user->id,$id);

        $card = $this->card->read($id);
        return new CardResource($card);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CardsRequest $request, $id)
    {
        $user = $request->user();
        $this->card->check_user($user->id,$id);

        $card = $this->card->update([
            'bank' => $request->bank_name,
            'number' => $request->card_number,
        ],$id);

        return new CardResource($this->card->read($card->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $this->card->check_user($user->id,$id);

        $this->card->delete($id);
        return response('',200);
    }
}
