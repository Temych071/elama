<?php

namespace Module\Source\Sources\Contracts;

use Module\Source\Sources\Collections\SourceDataCollection;
use Module\Source\Sources\Models\Source;

interface SourceService
{
    public function __construct(Source $source);

    public function prepareData($filters = null): void;


    /**
     * @return SourceDataCollection[]
     */
    public function getAll(): array;

    public function getCpc(): ?SourceDataCollection;

    public function getCpl(): ?SourceDataCollection;

    public function getClicks(): ?SourceDataCollection;

    public function getRequests(): ?SourceDataCollection;

    public function getConversionRate(): ?SourceDataCollection;

    public function getIncome(): ?SourceDataCollection;

    public function getDrr(): ?SourceDataCollection;
}
