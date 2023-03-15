<?php

namespace App\Interfaces;

interface Mapper
{
    public function map(array $data): array;
    public function parse(array $data): array;
}
