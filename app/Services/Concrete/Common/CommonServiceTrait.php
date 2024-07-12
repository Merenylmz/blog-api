<?php

namespace App\Services\Concrete\Common;


trait CommonServiceTrait
{
    private $modelRepository;
    public function __construct($modelRepository) {
        $this->modelRepository = $modelRepository;
    }
    public function all($cache = ["status"=>false, "key"=>null]){
        return $this->modelRepository->getAll($cache);
    }
    public function find($id){
        return $this->modelRepository->getById($id);
    }
    public function create(array $data){
        return $this->modelRepository->create($data);
    }
    public function update(array $data, $id){
        $modelData = $this->modelRepository->getById($id);
        return $modelData->update($data);
    }
    public function delete($id){
        return $this->modelRepository->delete($id);
    }
}
