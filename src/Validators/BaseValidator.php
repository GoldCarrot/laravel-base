<?php
/**
 * Created date 29.07.2020
 * @author Sergey Tyrgola <ts@GoldcarrotLaravel\Base.ru>
 */

namespace GoldcarrotLaravel\Base\Validators;


use GoldcarrotLaravel\Base\Interfaces\ValidatorInterface;
use Illuminate\Support\Facades\Validator;

abstract class BaseValidator implements ValidatorInterface
{
    private function make(array $rules, array $data, array $messages = [], array $customAttributes = []): array
    {
        return Validator::validate($data, $rules, $messages, array_merge($this->labels(), $customAttributes));
    }

    abstract protected function createRules(): array;

    protected function updateRules($id): array
    {
        return $this->createRules();
    }

    protected function labels(): array
    {
        return [];
    }

    public function onCreate(array $data): array
    {
        return $this->make($this->createRules(), $data);
    }

    public function onUpdate(array $data, $id): array
    {
        return $this->make($this->updateRules($id), $data);
    }
}
