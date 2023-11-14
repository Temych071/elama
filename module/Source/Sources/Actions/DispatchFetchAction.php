<?php

declare(strict_types=1);

namespace Module\Source\Sources\Actions;

use App\Exceptions\BusinessException;
use DateInterval;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Module\Source\Sources\Enums\FetchingDataStatus;
use Module\Source\Sources\Events\SourceUpdateFinishedEvent;
use Module\Source\Sources\Models\Source;
use Module\User\Enums\UserRole;

abstract class DispatchFetchAction
{
    protected function startFetching(Source $source, array $chain, bool $isForce, ?DateInterval $delay = null): void
    {
        $this->checkIfFetchingAvailable($source, $isForce);

        /** @var string|int $chainId */
        $chainId = Bus::chain([
            ...$chain,
            function () use ($source): void {
                $source->fetchingSuccess()->save();
                event(new SourceUpdateFinishedEvent($source));
            },
        ])->catch(static function () use ($source): void {
            $source->fetchingError()->save();
        })->onQueue($this->onQueue())->delay($delay)->dispatch();

        $source->fetchingStarted((string)$chainId)->save();
    }

    abstract protected function onQueue(): string;

    /**
     * @throws BusinessException
     */
    protected function checkIfFetchingAvailable(Source $source, bool $isForce): void
    {
        if ($source->data_status === FetchingDataStatus::fetching) {
            throw new BusinessException('Данные уже извлекаются');
        }

        $isAdmin = Auth::check() && Auth::user()->role === UserRole::admin;

        if (!$isAdmin && !$isForce) {
            $this->checkTimeLeftSinceLastUpdate($source);
        }
    }

    /**
     * @throws BusinessException
     */
    protected function checkTimeLeftSinceLastUpdate(Source $source): void
    {
        if (empty($source->data_updated_at)) {
            return;
        }

        $timeLeft = $source->data_updated_at->diffInHours(Carbon::now());

        if ($timeLeft < Source::MIN_UPDATE_INTERVAL) {
            throw new BusinessException(
                userMessage: sprintf(
                    "С последнего обновления прошло менее %s часов",
                    Source::MIN_UPDATE_INTERVAL,
                ), // todo translate
            );
        }
    }
}
