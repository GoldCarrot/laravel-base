<?php

namespace GoldcarrotLaravel\Base\Interfaces;


interface ValidatorInterface
{
    public function onCreate(array $data): array;

    public function onUpdate(array $data, $id): array;
}
