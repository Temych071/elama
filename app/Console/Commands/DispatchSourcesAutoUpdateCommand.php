<?php

declare(strict_types=1);

namespace App\Console\Commands;

use DateInterval;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Module\Source\Sources\Actions\DispatchSourceFetchAction;
use Module\Source\Sources\Enums\FetchingDataStatus;
use Module\Source\Sources\Models\Source;

class DispatchSourcesAutoUpdateCommand extends Command
{
    protected $signature = 'source:auto-update {--retry-error} {--force} {--without-delay} {--type=} {--campaign=}';

    protected $description = 'Запускает обновление всех источников, у которых прошло более 6 часов с момента последнего обновления';

    public function handle(): void
    {
        $query = Source::query();

        if ($this->option('retry-error')) {
            $query->where('data_status', FetchingDataStatus::error);
        } elseif (!$this->option('force')) {
            $query->where(static fn($q) => $q->orWhereDate(
                column: DB::raw('DATE_ADD(data_updated_at, INTERVAL 6 HOUR)'),
                operator: '<',
                value: Carbon::now(),
            )->orWhereNull('data_updated_at'));
        }

        if ($type = $this->option('type')) {
            $query->where('settings_type', $type);
        }

        if ($campaignId = $this->option('campaign')) {
            $query->where('campaign_id', $campaignId);
        }

        $query = $query->ready()
            ->where(static fn(Builder $q): Builder => $q->orWhere('data_status', '!=', FetchingDataStatus::fetching)
                ->orWhereNull('data_status'))
            ->has('campaign');

        $sources = $query->get();

        $sources->each(function (Source $source): void {
            // Добавляет рандомную задержку исполнения, чтобы не перегрузить сервер
            if ($this->option('without-delay')) {
                $delay = DateInterval::createFromDateString('0 seconds');
            } else {
                $delay = DateInterval::createFromDateString(random_int(5, 600) . ' seconds');
            }

            app(DispatchSourceFetchAction::class)->execute(
                source: $source,
                delay: $delay,
                isForce: true,
            );
        });

        $this->info('Sources dispatched: ' . $sources->count());
    }
}
