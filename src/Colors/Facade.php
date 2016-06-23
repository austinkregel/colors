<?php
namespace Kregel\Colors;
use Illuminate\Support\Facades\Facade;
class Color extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'color';
    }
}