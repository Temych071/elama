<?php

declare(strict_types=1);

namespace Module\Source\Sources\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $nickname
 * @property string $token
 * @property string $refresh_token
 * @property int $expires_in
 * @property Carbon $token_created_at
 * @property bool $invalid
 * @property string $user_id
 */
final class SourceAuthToken extends Model implements \Stringable
{
    protected $table = 'source_oauth_tokens';

    protected $fillable = [
        'driver',
        'user_id',
        'nickname',
        'token',
        'refresh_token',
        'expires_in',
        'token_created_at',
        'scopes'
    ];

    protected $casts = [
        'scopes' => 'array',
        'invalid' => 'bool',
        'expires_at' => 'datetime',
        'token_created_at' => 'datetime',
    ];

    public function __toString(): string
    {
        return $this->token;
    }

    public function getExpireTime(string $unit = 'second'): Carbon
    {
        return $this->token_created_at
            ->addUnit($unit, $this->expires_in, true);
    }

    public function isExpired(string $unit = 'second', Carbon $now = new Carbon()): bool
    {
        return $this->getExpireTime($unit)
            ->isBefore($now);
    }

    public function makeInvalid(): void
    {
        $this->invalid = true;
        $this->save();
        // TODO: sendNotify()
    }

    public function refreshToken(string $token, string $refreshToken, int $expires_in): void
    {
        $this->token = $token;
        $this->refresh_token = $refreshToken;
        $this->expires_in = $expires_in;
        $this->token_created_at = now();
        $this->invalid = false;

        $this->save();
    }
}
