<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->store !== null;
    }

    public function create(User $user): bool
    {
        return $user->store !== null;
    }

    public function update(User $user, Category $category): bool
    {
        return $user->store?->id === $category->tenant_id;
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->store?->id === $category->tenant_id;
    }
}
