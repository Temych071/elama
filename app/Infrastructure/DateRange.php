<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Stringable;

final class DateRange implements Arrayable, Stringable
{
    private const TO_STR_FORMAT = 'Y-m-d';
    private const STR_DELIMITER = ':';

    public function __construct(
        private readonly Carbon $from = new Carbon(),
        private readonly Carbon $to = new Carbon(),
        public readonly ?string $fromAlias = null,
    ) {
        if ($this->from->isAfter($to) && !$this->from->isSameDay($to)) {
            throw new RuntimeException("$from must be lower then $to");
        }
    }

    #[Pure]
    public function getFrom(): Carbon
    {
        return $this->from->clone();
    }

    #[Pure]
    public function getTo(): Carbon
    {
        return $this->to->clone();
    }


    public function getLength(): int
    {
        return $this->getTo()->diffInDays($this->from) + 1;
    }

    public function getPrev(): self
    {
        return new self(
            $this->getFrom()->subDays($this->getLength()),
            $this->getTo()->subDays($this->getLength()),
        );
    }

    /**
     * @return array|Carbon[]
     */
    public function getDays(?Closure $callback = null): array
    {
        $day = $this->getFrom()->startOfDay();
        $to = $this->getTo();

        if ($to->isSameDay($this->getFrom())) {
            return is_null($callback) ? [$day] : [$callback($day)];
        }

        $days = [];
        do {
            $days[] = $day->clone();
            $day->addDay();
        } while (!$day->isSameDay($to));
        $days[] = $day;

        return is_null($callback) ? $days : array_map($callback, $days);
    }

    /**
     * @return array|string[]
     */
    public function getDaysWithFormat(string $format = 'Y-m-d'): array
    {
        return $this->getDays(static fn (Carbon $day): string => $day->format($format));
    }

    /**
     * @return self[]
     */
    public function chunk(int $days): array
    {
        if ($days >= $this->getLength()) {
            return [clone $this];
        }

        // Так-то вроде можно было по быстрее это всё сделать, но так проще :)
        return array_map(static fn($chunk): \App\Infrastructure\DateRange => self::fromArray([
            Arr::first($chunk),
            Arr::last($chunk),
        ]), array_chunk($this->getDays(), $days));
    }

    public static function make(array|string|null|Carbon|self $period = null): self
    {
        if ($period instanceof self) {
            return $period;
        }

        if (is_array($period)) {
            return self::fromArray($period);
        }

        if (is_string($period)) {
            return self::parse($period);
        }

        if ($period instanceof Carbon) {
            return new self($period, $period);
        }

        return new self(Carbon::now(), Carbon::now());
    }

    public function format(
        string $formatDate = 'Y-m-d',
        string $formatPeriod = 'f->t',
        string $formatDateForSameMonth = null
    ): string {
        $from = $this->getFrom();
        $to = $this->getTo();

        $fromString = $from
            ->translatedFormat($from->isSameMonth($to) ? ($formatDateForSameMonth ?? $formatDate) : $formatDate);

        return Str::replace(
            ['f', 't'],
            [$fromString, $to->translatedFormat($formatDate)],
            $formatPeriod,
        );
    }

    private const FORMAT_PRESETS = [
        'default' => [
            'date' => 'Y-m-d',
            'dateSameMonth' => null,
            'period' => 'f:t',
        ],

        'checks' => [
            'date' => 'd.m',
            'dateSameMonth' => null,
            'period' => 'f-t',
        ],
    ];

    public function formatByPreset(string $preset = 'default'): string
    {
        if (empty(self::FORMAT_PRESETS[$preset])) {
            throw new RuntimeException("Undefined DateRange format preset `$preset`.");
        }

        $presetValues = array_merge(self::FORMAT_PRESETS['default'], self::FORMAT_PRESETS[$preset]);

        return $this->format(
            $presetValues['date'],
            $presetValues['period'],
            $presetValues['dateSameMonth'],
        );
    }

    public static function fromOffset(int $fromOffset, int $daysNum): self
    {
        if ($daysNum === 0) {
            throw new RuntimeException("\$daysNum must be != 0");
        }

        if ($daysNum > 0) {
            return new self(
                Carbon::now()->addDays($fromOffset),
                Carbon::now()->addDays($fromOffset + $daysNum - 1),
            );
        }

        return new self(
            Carbon::now()->addDays($fromOffset + $daysNum + 1),
            Carbon::now()->addDays($fromOffset),
        );
    }

    public static function fromArray(array $period): self
    {
        if (array_is_list($period)) {
            return new self(
                Carbon::make($period[0]),
                Carbon::make(last($period)),
            );
        }

        return new self(
            Carbon::make($period['from']),
            Carbon::make($period['to']),
        );
    }

    public static function parse(string $period): self
    {
        if (str_contains($period, self::STR_DELIMITER)) {
            return new self(
                Carbon::parse(Str::before($period, self::STR_DELIMITER))->startOfDay(),
                Carbon::parse(Str::after($period, self::STR_DELIMITER))->endOfDay(),
            );
        }

        return self::parseFromAlias($period);
    }

    public static function parseFromAlias(string $period): self
    {
        switch ($period) {
            case 'today':
                return new self(
                    Carbon::now(),
                    Carbon::now(),
                    $period,
                );
            case 'yesterday':
                return new self(
                    Carbon::yesterday(),
                    Carbon::yesterday(),
                    $period,
                );
            case 'week':
                return new self(
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                    $period,
                );
            case 'last-week':
                return new self(
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek(),
                    $period,
                );
            case 'month':
                return new self(
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                    $period,
                );
            case 'last-month':
                return new self(
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth(),
                    $period,
                );
            case 'year':
                return new self(
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear(),
                    $period,
                );
            case 'last-year':
                return new self(
                    Carbon::now()->subYear()->startOfYear(),
                    Carbon::now()->subYear()->endOfYear(),
                    $period,
                );
            case '7days':
                return new self(
                    Carbon::now()->subDays(7),
                    Carbon::now()->subDay(),
                    $period,
                );
            case '30days':
                return new self(
                    Carbon::now()->subDays(30),
                    Carbon::now()->subDay(),
                    $period,
                );
            case '90days':
                return new self(
                    Carbon::now()->subDays(90),
                    Carbon::now()->subDays(),
                    $period,
                );
            case '180days':
                return new self(
                    Carbon::now()->subDays(180),
                    Carbon::now()->subDays(),
                    $period,
                );
            case '365days':
                return new self(
                    Carbon::now()->subDays(365),
                    Carbon::now()->subDays(),
                    $period,
                );
            default:
                if (!is_null($d = Carbon::make($period))) {
                    return new self($d, $d);
                }
        }

        throw new InvalidArgumentException('Unexpected date range alias');
    }

    public function isAfter(Carbon $date): bool
    {
        return $date->isBefore($this->from);
    }

    public function isBefore(Carbon $date): bool
    {
        return $date->isAfter($this->to);
    }

    public function includes(Carbon $date): bool
    {
        return (
            $date->isAfter($this->from)
            && $date->isBefore($this->to)
        );
    }

    /**
     * @return array{from: \Illuminate\Support\Carbon, to: \Illuminate\Support\Carbon}
     */
    #[Pure]
    #[ArrayShape(['from' => Carbon::class, 'to' => Carbon::class])]
    public function toArray(): array
    {
        return [
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
        ];
    }

    public function __toString(): string
    {
        return $this->getFrom()->format(self::TO_STR_FORMAT)
            . self::STR_DELIMITER
            . $this->getTo()->format(self::TO_STR_FORMAT);
    }
}
