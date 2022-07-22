<?php

namespace App\Services;

class MySecondService
{
    public function __construct()
    {
        dump('from second service');
    }

    public function someMethod()
    {
        return 'hello !';
    }
}