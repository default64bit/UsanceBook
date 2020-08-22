<?php

namespace App\Repositories\TransactionRepository;

use App\Repositories\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $data);

    public function update($id);

    public function read($id);

    public function all_transactions($search=null,$user_id,$per_page);
    
    public function my_transactions($user_id,$per_page);
    
    public function payed_by_me_for_others($user_id,$per_page);
    
    public function others_pay_for_me($user_id,$per_page);
    
}