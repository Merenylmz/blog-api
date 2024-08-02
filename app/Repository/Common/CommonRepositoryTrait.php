<?php

namespace App\Repository\Common;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

trait CommonRepositoryTrait
{
    private $model;
    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function getAll($cache = ["status"=>false, "key"=>"catAll"]){
        if ($cache["status"]) {
            if (!Cache::has($cache["key"])) {
                $datas = $this->model->all();
                Cache::put($cache["key"], $datas, 60*60);
            }
            return Cache::get($cache["key"]);            
        }
        return $this->model->all();
    }
    public function getById($id){
        return $this->model->findOrFail($id);
    }
    public function create(array $data){
        return $this->model->create($data);
    }
    public function update(array $data, $id){
        $modelData = $this->model->findOrFail($id);
        return $modelData->update($data);
    }
    public function delete($id){
        return $this->model->destroy($id);
    }

    public function getBySlug($slug){
        if ($this->model instanceof Blog) {
            $this->model->isActive()->where("slug", $slug)->firstOrFail();
        }
        return $this->model->where("slug", $slug)->first();
    }
}
