<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'reference',
        'customer_category_id',
        'start_date',
        'description'
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(CustomerCategory::class, 'customer_category_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
