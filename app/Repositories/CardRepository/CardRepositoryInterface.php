<?php

namespace App\Repositories\CardRepository;

use App\Repositories\BaseRepositoryInterface;

interface CardRepositoryInterface extends BaseRepositoryInterface
{
    public function check_user($user_id,$id);
    
    public function create(array $data);

    public function update(array $data,$id);

    public function read($id);

    public function all_cards($search=null,$user_id,$per_page);

    public function getStatistics($group_id,$user,$period,$from_date=null,$to_date=null);
    
}