<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    public $timestamps = ['created_at'];
    public $fillable = [
        'id',
        'currency_id',
        'rate',
        'created_at'
    ];

    protected $table = 'currency';

    public function type()
    {
        return $this->hasOne(CurrencyType::class, 'id', 'currency_id');
    }
}
