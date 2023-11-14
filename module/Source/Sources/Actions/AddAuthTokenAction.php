<?php

declare(strict_types=1);

namespace Module\Source\Sources\Actions;

use Carbon\Carbon;
use Module\Source\Sources\Dto\AddAuthTokenDto;
use Module\Source\Sources\Models\SourceAuthToken;

final class AddAuthTokenAction
{
    public function execute(AddAuthTokenDto $dto): SourceAuthToken
    {
        return SourceAuthToken::create([
            'user_id' => $dto->getUserId(),
            'driver' => $dto->getDriver(),
            'token' => $dto->getToken(),
            'refresh_token' => $dto->getRefreshToken(),
            'expires_in' => $dto->getExpiresIn(),
            'token_created_at' => Carbon::now(),
            'nickname' => $dto->getName(),
            'scopes' => $dto->getScopes(),
        ]);

//        $token = SourceAuthToken::where('user_id', $dto->getUserId())
//            ->where('driver', $dto->getDriver())
//            ->firstOr(fn() => SourceAuthToken::make([
//                'user_id' => $dto->getUserId(),
//                'driver' => $dto->getDriver(),
//            ]));
//
//        $token->fill([
//            'token' => $dto->getToken(),
//            'refresh_token' => $dto->getRefreshToken(),
//            'expires_in' => $dto->getExpiresIn(),
//            'token_created_at' => Carbon::now(),
//            'nickname' => $dto->getName(),
//            'scopes' => $dto->getScopes(),
//        ]);
//
//        if (!empty($dto->getScopes())) {
//            $token->scopes = array_merge($dto->getScopes(), $token->scopes ?? []);
//        }
//
//        $token->save();
//        return $token;
    }
}
