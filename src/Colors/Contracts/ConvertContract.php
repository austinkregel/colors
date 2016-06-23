<?php

namespace Kregel\Colors\Contracts;

abstract class ConvertContract
{
    public abstract function toHex();
    public abstract function toRgb();
    public abstract function toHsl();

    public abstract function toArray();

    public abstract function lighten($percent);
    public abstract function darken($percent);

    public abstract function saturate($percent);
    public abstract function desaturate($percent);
}