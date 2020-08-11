<?php

namespace App\Repositories\UserRepository;

use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $data);

    public function update($id);

    public function userInfo($id);
}