<?php

namespace App\Repositories\GroupRepository;

use App\Repositories\BaseRepositoryInterface;

interface GroupRepositoryInterface extends BaseRepositoryInterface
{
    public function check_user($user_id,$id);
    
    public function create(array $data);

    public function update(array $data,$id);

    public function read($id);

    public function all_groups($search=null,$user_id,$per_page);
    
}