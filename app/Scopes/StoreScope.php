<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class StoreScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check() && !Auth::user()->isSuperAdmin()) {
            $currentStoreId = session('current_store_id');
            if ($currentStoreId) {
                $builder->where('store_id', $currentStoreId);
            }
        }
    }
}
