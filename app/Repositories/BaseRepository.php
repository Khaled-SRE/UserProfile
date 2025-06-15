<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    protected array $relations = [];

    protected bool $pagination = false;

    protected bool $resource = true;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function first($columns = ['*'], $fail = true)
    {
        $query = $this->model->with($this->relations);

        return $fail ? $query->firstOrFail($columns) : $query->first($columns);
    }

    public function find($id, $columns = ['*'], $fail = true)
    {
        $query = $this->model->with($this->relations);

        return $fail ? $query->findOrFail($id, $columns) : $query->find($id, $columns);
    }

    public function findBy($attribute, $value)
    {
        return $this->model->with($this->relations)->where($attribute, $value)->firstOrFail();
    }

    public function where(array $where, $boolean = 'and')
    {
        $query = $this->model->with($this->relations);
        foreach ($where as $column => $value) {
            $query->where($column, $value, $boolean);
        }

        return $query;
    }

    public function create(array $data, $force = true, $resource = true)
    {
        $created = $force ? $this->model->forceCreate($data) : $this->model->create($data);

        return $resource ? $created->fresh() : $created;
    }

    public function update(array $data, $id = null, $force = true, $resource = true)
    {
        $model = $id ? $this->find($id, ['*'], true) : $this->model;
        $force ? $model->forceFill($data)->save() : $model->update($data);

        return $resource ? $model->fresh() : $model;
    }

    public function delete($id = null)
    {
        $model = $id ? $this->find($id, ['*'], true) : $this->model;

        return $model->delete();
    }

    public function exists()
    {
        return $this->model->exists();
    }

    public function random($qtd = 15)
    {
        return $this->model->with($this->relations)->inRandomOrder()->limit($qtd)->get();
    }

    public function with($relations)
    {
        $this->relations = is_array($relations) ? $relations : func_get_args();

        return $this;
    }

    public function all($columns = ['*'])
    {
        $query = $this->model->with($this->relations);

        return $query->get($columns);
    }

    public function get($columns = ['*'])
    {
        $query = $this->model->with($this->relations);

        return $this->pagination
            ? $query->paginate()
            : $query->get($columns);
    }

    public function setRelations($relations)
    {
        $this->relations = $relations;

        return $this;
    }

    public function setPagination($pagination)
    {
        $this->pagination = $pagination;

        return $this;
    }

    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }
}
