<?php

declare(strict_types=1);

namespace Module\Source\Avito\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Source\Sources\Models\Source;

final class AvitoItemStats extends Model
{
    protected $table = 'avito_items_stats';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['source_id', 'item_id', 'date'];

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $dateFormat = 'Y-m-d';

    public function item(): BelongsTo
    {
        return $this->belongsTo(AvitoItem::class, 'item_id', 'id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id', 'id');
    }


    // Для первичного ключа из нескольких полей

    /** @noinspection PhpMissingReturnTypeInspection
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        return $this->original[$keyName] ?? $this->getAttribute($keyName);
    }
}
