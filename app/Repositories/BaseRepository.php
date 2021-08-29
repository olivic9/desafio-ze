<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository implements BaseInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model::create($attributes);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): Model
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }


}
