<?php

declare(strict_types=1);

namespace Reviews\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Reviews\Enums\AnswerFilter;
use Reviews\Enums\CommentFilter;
use Reviews\Enums\RatingFilter;
use Reviews\Enums\ReviewSource;
use Reviews\Enums\ReviewStatus;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

final class Review extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $table = 'review_reviews';

    protected $fillable = [
        'name',
        'comment',
        'contact',
        'stars',
    ];

    protected $casts = [
        'status' => ReviewStatus::class,
        'source' => ReviewSource::class,
    ];

    protected $attributes = [
        'status' => ReviewStatus::NOT_MODERATED,
    ];

    protected static function booted(): void
    {
        self::deleting(static function (Review $review): void {
            $review->comments->each(fn (ReviewComment $comment): ?bool => $comment->delete());
        });
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ReviewComment::class, 'review_id');
    }

    public function reviewForm(): BelongsTo
    {
        return $this->belongsTo(ReviewForm::class, 'review_form_id');
    }

    public function shouldNotice(): bool
    {
        return $this->stars <= $this->reviewForm->max_stars_for_notification;
    }

    public function shouldPublish(): bool
    {
        return $this->stars >= $this->reviewForm->min_stars_for_publish;
    }

    public function scopeAccepted($q): void
    {
        $q->where('status', ReviewStatus::ACCEPTED->value);
    }

    public function scopeExternal($q): void
    {
        $q->where('source', '!=', ReviewSource::DAILY_GROW->value);
    }

    public function scopeInternal($q): void
    {
        $q->where('source', ReviewSource::DAILY_GROW->value);
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when(($filters['rating'] ?? null) == RatingFilter::new->value || $filters['rating'] == null, function ($query) use ($filters): void {
            $query->latest();
        })->when(($filters['rating'] ?? null) == RatingFilter::bad_reviews->value, function ($query): void {
            $query->orderBy('stars', 'asc');
        })->when(($filters['rating'] ?? null) == RatingFilter::good_reviews->value, function ($query): void {
            $query->orderBy('stars', 'desc');
        })->when(($filters['comments'] ?? null) == CommentFilter::has_comments->value, function ($query): void {
            $query->whereHas('comments');
        })->when(($filters['comments'] ?? null) == CommentFilter::no_comments->value, function ($query): void {
            $query->whereDoesntHave('comments');
        })->when(($filters['answer'] ?? null) == AnswerFilter::has_answer->value, function ($query): void {
            $query->whereHas('answer');
        })->when(($filters['answer'] ?? null) == AnswerFilter::no_answer->value, function ($query): void {
            $query->whereDoesntHave('answer');
        });
    }

    public function tags(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->reviewTags(),
            (new ReviewTag())->tag(),
        );
    }

    public function reviewTags(): HasMany
    {
        return $this->hasMany(ReviewTag::class, 'review_id');
    }

    public function addTag(string $tag): bool
    {
        $tag = str_replace(' ', '_', $tag);

        /** @var Tag $newTag */
        $newTag = Tag::query()->firstOrNew(['tag' => $tag]);
        $newTag->save();

        return (bool) $this->reviewTags()->insertOrIgnore([
            'tag_id' => $newTag->id,
            'review_id' => $this->id,
        ]);
    }

    public function removeTag(string $tag): void
    {
        $this->reviewTags()
            ->whereIn('tag_id', $this
                ->tags()
                ->where('tag', $tag)
                ->select('id'))
            ->delete();
    }

    public function scopeTag(Builder $q, string $tag): Builder
    {
        return $q->whereHas('tags', static fn($q) => $q->where('tag', $tag));
    }

    public function answer(): HasOne
    {
        return $this->hasOne(ReviewAnswer::class, 'review_id');
    }
}
