<?php namespace Shambou\RequestLogs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RequestLogRelation extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function requestLog(): BelongsTo
    {
        return $this->belongsTo(RequestLog::class);
    }

    public function relatable(): MorphTo
    {
        return $this->morphTo();
    }

    public function foreignKeys(): array
    {
        return ['request_logs'];
    }
    
    public static function getSubjectTypes(): array
    {
        return self::select('relatable_type')
            ->distinct()
            ->get()
            ->pluck('relatable_type')
            ->toArray();
    }
}
