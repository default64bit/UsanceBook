<?php

namespace App\Repositories\UserFriendRepository;

use App\Repositories\BaseRepositoryInterface;

interface UserFriendRepositoryInterface extends BaseRepositoryInterface
{
    public function check_user($user_id,$id);
    
    public function create(array $data);

    public function update(array $data,$id);

    public function delete_friendship($user_id,$with_whom_email);

    public function read($id);

    public function all_friends($search=null,$status='all',$user_id,$per_page);

    public function users_list($search=null,$user_id);
    
    public function friend_requests($user_id);

    public function friends_count($user_id);

    public function change_state($user_id,$with_whom_email,$state);
    
}