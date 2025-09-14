<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class StoreScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Only apply store filtering if user is authenticated and not super admin
        if (Auth::check() && !Auth::user()->isSuperAdmin()) {
            $currentStoreId = session('current_store_id');
            
            if ($currentStoreId) {
                $builder->where($model->getTable() . '.store_id', $currentStoreId);
            }
        }
    }
}