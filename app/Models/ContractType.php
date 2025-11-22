<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ContractType extends Model
{
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }
}
