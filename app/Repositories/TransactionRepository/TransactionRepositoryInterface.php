<?php

namespace App\Repositories\TransactionRepository;

use App\Repositories\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    public function check_user($user_id,$id);

    public function create(array $data);

    public function update(array $data,$id);

    public function read($id);

    public function all_transactions($search=null,$user_id,$per_page);

    public function top_transactions($user_id);
    
    public function my_transactions($user_id,$per_page);
    
    public function transactions_payed_by_me_for_others($user_id,$per_page);
    
    public function transactions_payed_by_others_for_me($user_id,$per_page);
    
}