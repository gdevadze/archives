<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $guarded = [];

    protected $casts = [
        'contract_date' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


    public function contractType(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }

}
