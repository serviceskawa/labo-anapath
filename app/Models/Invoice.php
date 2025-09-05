<?php

namespace App\Models;

use App\Models\TestOrder;
use App\Models\InvoiceDetail;
use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory, BranchScopeTrait;
    protected $guarded = [];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the details for the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    /**
     * Get the order that owns the Report
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(TestOrder::class, 'test_order_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }


    public function contrat()
    {
        return $this->belongsTo(Contrat::class, 'contrat_id');
    }

}
