<?php

declare(strict_types=1);

namespace Module\Campaign\Checks\Checks;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;
use Module\Campaign\Checks\Contracts\CanBeChecked;
use Module\Campaign\Checks\DTO\CheckObject;
use Module\Campaign\Checks\DTO\CheckResult;
use Module\Campaign\Checks\DTO\RuleData;
use Spatie\LaravelData\DataCollection;

abstract class CheckRule implements Arrayable
{
    protected string $title = '';
    protected string $desc = '';
    protected string $message = '';
    protected bool $textFromLang = false;
    protected string $langPrefix = '';
    protected array $additionalLangParams = [];

    public static function new(): static
    {
        return new static();
    }

    protected function getTitle(): string
    {
        if (empty($this->title)) {
            return '';
        }

        return $this->textFromLang
            ? trans($this->langPrefix . $this->title, $this->additionalLangParams)
            : $this->title;
    }

    protected function getDesc(): string
    {
        if (empty($this->desc)) {
            return '';
        }

        return $this->textFromLang
            ? trans($this->langPrefix . $this->desc, $this->additionalLangParams)
            : $this->desc;
    }

    protected function getMessage(int $failedObjectsCount, int $totalObjectsCount): string
    {
        $percents = (int)($failedObjectsCount / $totalObjectsCount * 100);
        return $this->textFromLang
            ? trans_choice(
                $this->langPrefix . $this->message,
                $failedObjectsCount,
                array_merge($this->additionalLangParams, [
                    'percents' => $percents < 1 ? '<1' : $percents,
                ])
            )
            : $this->message;
    }

    /**
     * @return array{title: string, desc: string}
     */
    #[ArrayShape(['title' => "string", 'desc' => "string"])]
    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'desc' => $this->getDesc(),
        ];
    }

    public function getData(array $override = []): RuleData
    {
        return RuleData::from(array_merge($this->toArray(), $override));
    }

    protected function checkObjects(array|ArrayAccess $objects): ?CheckResult
    {
        $res = [
            'rule' => $this->getData(),
            'failedObjects' => [],
            'totalObjectsCount' => 0,
        ];

        /** @var CanBeChecked $object */
        foreach ($objects as $object) {
            if (!$this->canApplyRule($object)) {
                continue;
            }

            $res['totalObjectsCount']++;
            if (!$this->check($object)) {
                $res['failedObjects'][] = $object->getCheckObject();
            }
        }

//        if (!$res['totalObjectsCount']) {
//            return null;
//        }

        $res['failedObjects'] = new DataCollection(CheckObject::class, $res['failedObjects']);
        if ($res['failedObjects']->count() !== 0) {
            $res['message'] = $this->getMessage($res['failedObjects']->count(), $res['totalObjectsCount']);
        }

        return CheckResult::from($res);
    }

    /**
     * @param ArrayAccess|CanBeChecked[] $objects
     * @param ArrayAccess|string[] $rules
     * @return DataCollection|null
     */
    public static function getResultsForObjects(array|ArrayAccess $objects, array|ArrayAccess $rules): ?DataCollection
    {
        $res = [];
        foreach ($rules as $rule) {
            $ruleInstance = new $rule();
            if ($ruleInstance instanceof static) {
                $checkResult = $ruleInstance->checkObjects($objects);
                if (!is_null($checkResult)) {
                    $res[] = $checkResult;
                }
            }
        }

        if ($res === []) {
            return null;
        }

        return new DataCollection(CheckResult::class, $res);
    }

    protected function check(CanBeChecked $object): bool
    {
        return true;
    }

    protected function canApplyRule(CanBeChecked $object): bool
    {
        return true;
    }
}
