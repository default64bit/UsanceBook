<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function create(array $data);

    public function read($id);

    public function readRange($range);

    public function readAll();

    public function update(array $data,$id);

    public function delete($id);

    public function search($builder, array $fields = [], $value);
}