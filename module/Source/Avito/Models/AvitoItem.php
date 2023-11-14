<?php

declare(strict_types=1);

namespace Module\Source\Avito\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Source\Avito\Enums\ItemStatus;
use Module\Source\Sources\Models\Source;

final class AvitoItem extends Model
{
    protected $table = 'avito_items';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['id', 'source_id'];

    protected $casts = [
        'status' => ItemStatus::class,
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id');
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
