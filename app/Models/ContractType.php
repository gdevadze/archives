<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ContractType extends Model
{
    protected $guarded = [];

    protected $appends = [
        'contract_type_name'
    ];

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('receive_report')
            ->withTimestamps();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function contractTypeName(): Attribute
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->where('content->contract_type_name', '!=', null)->first();
        return new Attribute(
            get: fn() => $translation ? $translation->content['contract_type_name'] : ''
        );
    }
}
