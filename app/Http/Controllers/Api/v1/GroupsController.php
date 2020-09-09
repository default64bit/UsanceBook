<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Repositories\GroupRepository\GroupRepositoryInterface;
use Illuminate\Http\Request;

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
