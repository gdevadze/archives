<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Company extends Model
{
    protected $guarded = [];

    protected $appends = [
        'company_name'
    ];
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function companyName(): Attribute
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->where('content->company_name', '!=', null)->first();
        return new Attribute(
            get: fn() => $translation ? $translation->content['company_name'] : ''
        );
    }
}
