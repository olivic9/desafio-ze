<?php

namespace App\Services\Dtos;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

/**
 * Class BaseAbstractDto
 * @package App\Services\Dtos
 */
abstract class BaseAbstractDto
{
    public function __construct(array $data)
    {
        $validator = Validator::make(
            $data,
            $this->configureValidatorRules()
        );

        if ($validator->fails()) {
            throw new InvalidArgumentException(
                'Error: ' . $validator->errors()->first()
            );
        }
        // @codeCoverageIgnoreStart
        if (!$this->map($data)) {
            throw new InvalidArgumentException('Mapping field failed.');
        }
        // @codeCoverageIgnoreEnd
    }

    abstract protected function configureValidatorRules(): array;

    abstract protected function map(array $data): bool;
}
