<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface BaseInterface
 * @package App\Repositories
 */
interface BaseInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param int id
     * @return Model
     */
    public function find(int $id): Model;
}
