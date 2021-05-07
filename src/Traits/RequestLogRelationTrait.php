<?php namespace Shambou\RequestLogs\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shambou\RequestLogs\Models\RequestLogRelation;

trait RequestLogRelationTrait
{
    public function requestLogRelations(): MorphMany
    {
        return $this->MorphMany(RequestLogRelation::class, 'relatable');
    }
}
