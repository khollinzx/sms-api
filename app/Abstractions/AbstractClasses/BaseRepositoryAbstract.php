<?php

namespace App\Abstractions\AbstractClasses;

use App\Abstractions\Interfaces\RepositoryInterface;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepositoryAbstract implements RepositoryInterface
{

    /**
     * @var int
     */
    protected int $defaultDBRetryValue = 15;

    /**
     * @var array
     */
    protected array $relationships = [];

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     * @param string $databaseTableName
     */
    public function __construct(protected Model $model, protected string $databaseTableName) {}

    /**
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

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
    public function findById(int $modelId, array $columns = ['*'], array $relations = [], array $appends = [], bool $useLock = false): ?Model
    {
        $query = $this->model->select($columns)->with($relations);
        return $query->find($modelId);
    }

    /**
     * This creates a new Model by the Model's properties
     *
     * @param array $attributes
     * @param array $relationships
     * @param bool $useLock
     * @return Model|null
     */
    public function createModel(array $attributes, array $relationships = [], bool $useLock = false): ?Model
    {
        /** @var Model|null $model */
        $model = null;
        DB::transaction(function () use (&$model, $attributes, $relationships) {
            $model = Utils::saveModelRecord(new $this->model, $attributes);
        }, $this->defaultDBRetryValue);
        return $model ? $this->findById($model->id, ['*'], $relationships, [], $useLock) : null;
    }

    /**
     * This updates an existing model by its id
     *
     * @param int $modelId
     * @param array $attributes
     * @return bool
     */
    public function updateById(int $modelId, array $attributes): bool
    {
        return DB::transaction(function () use ($attributes, $modelId) {
            $model = $this->findById($modelId);
            return $model->update($attributes);
        }, $this->defaultDBRetryValue);
    }

    /**
     * This updates an existing model by its id
     *
     * @param int $modelId
     * @param array $attributes
     * @param array $relationships
     * @param array $columns
     * @return Model
     */
    public function updateByIdAndGetBackRecord(int $modelId, array $attributes, array $relationships = [], array $columns = ['*']): Model
    {
        return DB::transaction(function () use ($modelId, $attributes, $relationships, $columns) {
            $model = $this->findById($modelId);
            // Lock the rows for update
            $this->findById($modelId)->get();
            $model->update($attributes);
            return $this->findById($modelId, $columns, $relationships);
        }, $this->defaultDBRetryValue);
    }

    /**
     *
     * @param array $queries
     * @param array $columns
     * @param array $relations
     * @param bool $useLock
     * @return Model|null
     */
    public function findSingleByWhereClause(array $queries, array $columns = ['*'], array $relations = [], bool $useLock = true): ?Model
    {
        $relations = count($relations) ? $relations : $this->relationships;
        return DB::transaction(function () use ($queries, $columns, $relations, $useLock) {
            $query = $this->getModel()->with($relations)->select($columns);
            $query = Utils::getRecordUsingWhereArrays($query, $queries);
            return $query->latest()->first();
        }, $this->defaultDBRetryValue);
    }

}
