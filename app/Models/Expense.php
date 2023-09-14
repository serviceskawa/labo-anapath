<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function expensecategorie()
    {
        return $this->belongsTo(ExpenseCategorie::class,'expense_categorie_id');
    }

    public function cashticket()
    {
        return $this->belongsTo(CashboxTicket::class,'cashbox_ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class,'item_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
}