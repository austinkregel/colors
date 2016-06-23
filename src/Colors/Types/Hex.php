<?php

namespace Kregel\Colors\Types;

use Illuminate\Contracts\Support\Jsonable;
use Kregel\Colors\Color;
use Kregel\Colors\Contracts\ConvertContract;

class Hex extends ConvertContract implements Jsonable
{
    public $hex;

    /**
     * Hex constructor.
     * @param $hex
     */
    public function __construct($hex)
    {

        if(is_object($hex))
            $this->hex = $hex->__toString();
        else
            $this->hex = substr($hex,0,6);
    }

    /**
     * @return $this
     */
    public function toHex()
    {
        return $this;
    }

    /**
     * @param $percent
     * @return Hex
     */
    public function lighten($percent)
    {
        return $this->toHsl()->lighten($percent)->toHex();
    }

    /**
     * @return Hsl
     */
    public function toHsl()
    {
        return $this->toRgb()->toHsl();
    }

    /**
     * @return bool|Rgb
     */
    public function toRgb()
    {
        $hex = $this->__toString();
        if (strlen($hex) == 6) {
            list($r, $g, $b) = [substr($hex, 0, 2), substr($hex, 2, 2), substr($hex, 4, 2)];
        } elseif (strlen($hex) == 3) {
            list($r, $g, $b) = [substr($hex, 0, 1), substr($hex, 1, 1), substr($hex, 2, 1)];
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return new Rgb($r, $g, $b);
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->hex;
    }

    /**
     * @param $percent
     * @return Hex
     */
    public function darken($percent)
    {
        return $this->toHsl()->darken($percent)->toHex();
    }

    /**
     * @param int $options
     * @return mixed
     */
    public function toJson($options = 0)
    {
        return json_decode($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['hex' => $this->hex];
    }

    /**
     * @param $percent
     * @return mixed
     */
    public function desaturate($percent)
    {
        return $this->saturate($percent);
    }

    /**
     * @param $percent
     * @return mixed
     */
    public function saturate($percent)
    {
        return $this->toHsl()->saturation($percent)->toHex();
    }
}