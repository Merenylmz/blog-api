<?php

namespace App\Interface\Common;

interface CommonRepositoryInterface
{
    public function getAll($cache = ["status"=>false, "key"=>"catAll"]);
    public function getById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
