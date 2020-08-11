<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    private $model;

    protected function setModel(Model $model){
        $this->model = $model;
    }
    protected function getModel(){
        return $this->model;
    }

    public function create(array $data){}

    public function update($id){}

    public function read($id){
        return $this->model->where('id',$id)->first();
    }

    public function readRange($range){
        return $this->model->paginate($range);
    }

    public function readAll(){
        return $this->model->all();
    }

    public function delete($id){
        return $this->model->where('id',$id)->delete();
    }
}