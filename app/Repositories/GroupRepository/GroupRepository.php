<?php

namespace App\Repositories\GroupRepository;

use App\Models\Group;
use App\Repositories\BaseRepository;
use App\Repositories\GroupRepository\GroupRepositoryInterface;
use Illuminate\Support\Str;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    private $group;

    public function __construct(Group $group){
        $this->setModel($group);
        $this->group = $group;
    }

    public function check_user($user_id,$id){
        $user_can_access = $this->group->where('id',$id)->where('owner_id',$user_id)->exists();
        if(!$user_can_access){ abort(404); }
    }

    public function create(array $data){
        $new_card = $this->group->create([
            'name' => $data['name'],
            'who_can_see' => $data['who_can_see'],
            'who_can_pay' => $data['who_can_pay'],
            'owner_id' => $data['user_id'],
        ]);
        return $new_card;
    }

    public function update(array $data,$id){
        $group = $this->group->findOrFail($id);
        $group->update([
            'name' => $data['name'],
            'who_can_see' => $data['who_can_see'],
            'who_can_pay' => $data['who_can_pay'],
        ]);
        return $group;
    }

    public function read($id){
        return $this->group->where('id',$id)
            ->with(['owner','transactions.transaction.user'])
            ->first();
    }

    public function all_groups($search=null,$user_id,$per_page){
        $all_groups = $this->group->where('owner_id',$user_id);

        if($search != null){
            $all_groups = $this->search($all_groups,$this->group::SEARCHABLE,$search);
        }

        $all_groups = $all_groups->with(['owner','transactions.transaction.user'])->latest();
        if($per_page != 'all'){
            $all_groups = $all_groups->paginate($per_page);
        }else{ $all_groups = $all_groups->get(); }

        return $all_groups;
    }

}