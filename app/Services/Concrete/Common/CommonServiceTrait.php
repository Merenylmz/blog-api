<?php

namespace App\Services\Concrete\Common;


trait CommonServiceTrait
{
    private $modelRepository;
    public function __construct($modelRepository) {
        $this->modelRepository = $modelRepository;
    }
    public function all(){
        return $this->modelRepository->all();
    }
    public function find($id){
        return $this->modelRepository->findOrFail($id);
    }
    public function create(array $data){
        return $this->modelRepository->create($data);
    }
    public function update(array $data, $id){
        $modelData = $this->modelRepository->findOrFail($id);
        return $modelData->update($data);
    }
    public function delete($id){
        return $this->modelRepository->destroy($id);
    }
}
