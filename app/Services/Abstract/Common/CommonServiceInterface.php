<?php

namespace App\Services\Abstract\Common;

interface CommonServiceInterface
{
    public function all($cache);
    public function find($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
