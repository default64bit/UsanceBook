<?php

namespace App\Repositories\UserRepository;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    private $user;

    public function __construct(User $user){
        $this->setModel($user);
        $this->user = $user;
    }

    public function create(array $data){
        if(!empty($data['avatar'])){
            $file_name = Str::uuid().'.'.$data['avatar']->getClientOriginalExtension();
            $data['avatar']->move('img/users', $file_name);
            $data['avatar'] = $file_name;
        }
        $data['password'] = bcrypt($data['password']);

        return $this->user->create([
            'avatar' => $data['avatar'],
            'name' => $data['name'],
            'family' => $data['family'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
        ]);
    }

    public function update(array $data,$id){

    }

    public function userInfo($id){
        return $this->user->where('id',$id)->first();
    }

}