<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Repositories\GroupRepository\GroupRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

use function PHPSTORM_META\map;

class GroupsController extends Controller
{
    private $group;

    public function __construct(GroupRepositoryInterface $groupRepository){
        $this->group = $groupRepository;
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
        }else{ $per_page = Group::PER_PAGE; }

        $all_groups = $this->group->all_groups($request->search,$user->id,$per_page);
        return GroupResource::collection($all_groups);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request, $id)
    {
        $user = $request->user();
        $from_date = $request->from_date ? (Jalalian::fromFormat('Y/m/d',$request->from_date))->toCarbon()->toDateTimeString() : null;
        $to_date = $request->to_date ? (Jalalian::fromFormat('Y/m/d',$request->to_date))->toCarbon()->toDateTimeString() : null;
        $period = $request->period;

        $statistics = $this->group->getStatistics($id,$user,$period,$from_date,$to_date);
        
        $response = [
            'categories' => [],
            'gain' => [],
            'loss' => [],
            'total' => [],
            'total_gain' => 0,
            'total_loss' => 0,
            'total_transactions' => 0,
        ];
        foreach($statistics as $statistic){
            $transaction = $statistic->transaction;
            $response['total_transactions']++;

            switch($period){
                case 'daily':
                    $key = date('Y-m-d',strtotime($transaction->date));
                    // $key = jdate($transaction->date)->format('Y-m-d');
                    $response['categories'][$key] = jdate($transaction->date)->format('Y, d F');
                break;
                case 'monthly':
                    $key = date('Y-m',strtotime($transaction->date));
                    // $key = jdate($transaction->date)->format('Y-m');
                    $response['categories'][$key] = jdate($transaction->date)->format('Y F');
                break;
                case 'yearly':
                    // $key = date('Y',strtotime($transaction->date));
                    $key = jdate($transaction->date)->format('Y');
                    $response['categories'][$key] = jdate($transaction->date)->format('Y');
                break;
            }

            if($transaction->type == '+'){
                isset($response['gain'][$key]) ? $response['gain'][$key] += $transaction->amount : $response['gain'][$key] = $transaction->amount;
                $response['loss'][$key] = $response['loss'][$key]??0;
                isset($response['total'][$key]) ? $response['total'][$key] += $transaction->amount : $response['total'][$key] = $transaction->amount;

                $response['total_gain'] += $transaction->amount;
            }else{
                $response['gain'][$key] = $response['gain'][$key]??0;
                isset($response['loss'][$key]) ? $response['loss'][$key] += $transaction->amount : $response['loss'][$key] = $transaction->amount;
                isset($response['total'][$key]) ? $response['total'][$key] -= (-1)*$transaction->amount : $response['total'][$key] = (-1)*$transaction->amount;

                $response['total_loss'] += $transaction->amount;
            }
        }

        ksort($response['categories']);
        ksort($response['gain']);
        ksort($response['loss']);
        ksort($response['total']);

        $response['categories'] = array_values($response['categories']);
        $response['gain'] = array_values($response['gain']);
        $response['loss'] = array_values($response['loss']);
        $response['total'] = array_values($response['total']);

        return response($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        $new_group = $this->group->create([
            'name' => $request->name,
            'who_can_see' => $request->who_can_see,
            'who_can_pay' => $request->who_can_pay,
            'user_id' => $request->user()->id,
        ]);

        return new GroupResource($this->group->read($new_group->id));
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
        $this->group->check_user($user->id,$id);

        $group = $this->group->read($id);
        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, $id)
    {
        $user = $request->user();
        $this->group->check_user($user->id,$id);

        $group = $this->group->update([
            'name' => $request->name,
            'who_can_see' => $request->who_can_see,
            'who_can_pay' => $request->who_can_pay,
        ],$id);

        return new GroupResource($this->group->read($group->id));
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
        $this->group->check_user($user->id,$id);

        $this->group->delete($id);
        return response('',200);
    }
}
