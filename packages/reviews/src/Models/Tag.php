<?php

declare(strict_types=1);

namespace Reviews\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

final class Tag extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $table = 'review_tags';

    public $timestamps = false;

    protected $fillable = [
        'tag',
    ];

    public function reviews(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->reviewTags(),
            (new ReviewTag())->review(),
        );
    }

    public function reviewTags(): HasMany
    {
        return $this->hasMany(ReviewTag::class, 'tag_id');
    }
}
