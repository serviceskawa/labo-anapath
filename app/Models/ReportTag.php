<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTag extends Model
{
    use HasFactory, BranchScopeTrait;

    protected $guarded = [];

    protected $table = 'report_tags';
}
