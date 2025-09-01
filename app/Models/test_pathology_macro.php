<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class test_pathology_macro extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Si la clé primaire est maintenant un UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Event pour générer l'UUID avant la création
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }
    public function order()
    {
        return $this->belongsTo(TestOrder::class, 'id_test_pathology_order');
    }

    public function testOrder()
    {
        return $this->belongsTo(TestOrder::class, 'id_test_pathology_order');
    }

}
