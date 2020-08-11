<?php

namespace App\Repositories\UserRepository;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    private $user;

    public function __construct(User $user){
        $this->setModel($user);
        $this->user = $user;
    }

    public function create(array $data){
        return $this->user->create([
            'avatar' => $data['avatar'],
            'name' => $data['name'],
            'family' => $data['family'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
        ]);
    }

    public function update($id){

    }

    public function userInfo($id){
        return $this->user->where('id',$id)->first()->format();
    }

}