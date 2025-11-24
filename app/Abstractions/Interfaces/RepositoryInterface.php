<?php

namespace App\Abstractions\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{

    /**
     * @return Model
     */
    public function getModel() :Model;

    /**
     * Find Model by id
     *
     * @param int $modelId
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     * @param bool $useLock
     * @return Model|null
     */
    public function findById(int $modelId, array $columns = ['*'], array $relations= [], array $appends = [], bool $useLock = false): ?Model;

    /**
     * This creates a new Model by the Model's properties
     *
     * @param array $attributes
     * @param array $relationships
     * @param bool $useLock
     */
    public function createModel(array $attributes, array $relationships = [], bool $useLock = false);

    /**
     * This updates an existing model by its id
     *
     * @param int $modelId
     * @param array $attributes
     * @return bool
     */
    public function updateById(int $modelId, array $attributes): bool;

    /**
     * This updates an existing model by its id
     *
     * @param int $modelId
     * @param array $attributes
     * @param array $relationships
     * @param array $columns
     * @return Model
     */
    public function updateByIdAndGetBackRecord(int $modelId, array $attributes, array $relationships = [], array $columns = ['*']): Model;


}
