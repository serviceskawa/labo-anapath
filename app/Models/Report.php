<?php

namespace App\Models;

use App\Models\Patient;
use App\Models\TestOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the order that owns the Report
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(TestOrder::class, 'test_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function signateur()
    {
        return $this->belongsTo(User::class, 'signatory1');
    }


    /**
     * Get the title that owns the Report
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function title()
    {
        return $this->belongsTo(TitleReport::class, 'title_id');
    }

    /**
     * Get the patient that owns the Report
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }


    public function appel()
    {
        return $this->hasOne(AppelByReport::class);
    }


    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'report_tags', 'report_id', 'tag_id');
    }

    public function hasTag($tagName)
    {
        return $this->tags()->where('name', $tagName)->exists();
    }
}
