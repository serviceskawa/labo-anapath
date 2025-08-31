<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashboxTicket extends Model
{
    use HasFactory, BranchScopeTrait;
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function categorie()
    {
        return $this->belongsTo(ExpenseCategorie::class,'expense_category_id');
    }

    public function details()
    {
        return $this->hasMany(CashboxTicketDetail::class,'cashbox_ticket_id');
    }

    public function expensive()
    {
        return $this->hasMany(Expense::class);
    }
}
