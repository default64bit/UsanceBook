<?php

namespace App\Repositories\UserRepository;

interface UserRepositoryInterface
{
    public function create(array $data);

    public function update($id);

    public function userInfo($id);
}