<?php

namespace Kregel\Colors\Types;

use Illuminate\Contracts\Support\Jsonable;
use Kregel\Colors\Contracts\ConvertContract;
use InvalidArgumentException;
class Rgb extends ConvertContract implements Jsonable
{

    /**
     * @var array|ConvertContract
     */
    public $rgb = [];
    /**
     * @var
     */
    private $red;
    /**
     * @var
     */
    private $green;
    /**
     * @var
     */
    private $blue;

    /**
     * Rgb constructor.
     * @param $red
     * @param null $green
     * @param null $blue
     */
    public function __construct($red, $green = null, $blue = null)
    {

        if ((isset($green) && isset($blue) && isset($red)) && !(is_array($red) || is_object($red))) {
            $this->rgb = [$red, $green, $blue];

            list($this->red, $this->green, $this->blue) = $this->rgb;
        } elseif (!empty($red) && (is_array($red) || (is_object($red) && $red instanceof ConvertContract))) {
            if ($red instanceof ConvertContract)
                $this->rgb = $red->toHsl()->toArray();
            else
                $this->rgb = $red;
        } else {

            throw new InvalidArgumentException();
        }
        list($this->red, $this->green, $this->blue) = $this->rgb;
    }

    /**
     * @return Hex
     */
    public function toHex()
    {

        $hex = str_pad(dechex($this->red), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($this->green), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($this->blue), 2, "0", STR_PAD_LEFT);
        return new Hex($hex); // returns the hex value including the number sign (#)

    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toHex();
    }

    /**
     * @return $this
     */
    public function toRgb()
    {
        return $this;
    }

    /**
     * @param $percent
     * @return Rgb
     */
    public function lighten($percent)
    {
        return $this->toHsl()->lighten($percent)->toRgb();
    }

    /**
     * @return Hsl
     */
    public function toHsl()
    {
        $h=null;
        list($r, $g, $b) = $this->rgb;
        $r /= 255;
        $g /= 255;
        $b /= 255;
        $max = max( $r, $g, $b );
        $min = min( $r, $g, $b );

        $l = ( $max + $min ) / 2;
        $d = $max - $min;
        if( $d == 0 ){
            $h = $s = 0; // achromatic
        } else {
            $s = $d / ( 1 - abs( 2 * $l - 1 ) );
            switch( $max ){
                case $r:
                    $h = 60 * fmod( ( ( $g - $b ) / $d ), 6 );
                    if ($b > $g) {
                        $h += 360;
                    }
                    break;
                case $g:
                    $h = 60 * ( ( $b - $r ) / $d + 2 );
                    break;
                case $b:
                    $h = 60 * ( ( $r - $g ) / $d + 4 );
                    break;
            }
        }
        // Return HSL Color as array
        return new Hsl(round( $h, 2 ), round( $s, 2 )*100, round( $l, 2 ) *100);

    }

    /**
     * @param $percent
     * @return Rgb
     */
    public function darken($percent)
    {
        return $this->lighten($percent);
    }

    /**
     * @param int $options
     * @return mixed
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    /**
     * @return array|ConvertContract
     */
    public function toArray()
    {
        return $this->rgb;
    }

    /**
     * @param $percent
     * @return mixed
     */
    public function saturate($percent)
    {
        return $this->toHsl()->saturation($percent)->toRgb();
    }

    /**
     * @param $percent
     * @return mixed
     */
    public function desaturate($percent)
    {
        return $this->desaturation($percent);
    }
}