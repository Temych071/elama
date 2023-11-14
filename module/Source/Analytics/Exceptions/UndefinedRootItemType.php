<?php

declare(strict_types=1);

namespace Module\Source\Analytics\Exceptions;

final class UndefinedRootItemType extends \App\Exceptions\BusinessException
{
    public function __construct(string $rootPath, ?string $redirectTo = null)
    {
        parent::__construct("Неизвестный корневой элемент `$rootPath`.", $redirectTo);
    }
}
