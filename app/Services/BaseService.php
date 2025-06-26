<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService
{
    protected string $modelClass;

    public function find(int $id, array $with = []): ?Model
    {
        return $this->modelClass::with($with)->find($id);
    }

    public function delete(int $id): bool
    {
        $instance = $this->find($id);
        
        return $instance ? $instance->delete() : false;
    }
}