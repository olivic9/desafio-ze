<?php

namespace App\Services;

use App\Services\Dtos\BaseAbstractDto;

/**
 * Interface ServiceInterface
 * @package App\Services
 */
interface ServiceInterface
{
    public static function make(BaseAbstractDto $dto): ServiceInterface;
}
