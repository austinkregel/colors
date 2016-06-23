<?php
namespace Kregel\Colors\Types;

use Illuminate\Contracts\Support\Jsonable;
use Kregel\Colors\Contracts\ConvertContract;

class Hsl extends ConvertContract implements Jsonable
{
    /**
     * @var
     */
    public $hue;

    /**
     * @var
     */
    public $saturation;

    /**
     * @var
     */
    public $lightness;

    /**
     * @var array|ConvertContract
     */
    public $hsl = [];

    /**
     * Hsl constructor.
     * @param $hsl
     * @param null $stat
     * @param null $light
     */
    public function __construct($hsl, $stat = null, $light = null)
    {
        if ((isset($stat) && isset($light) && isset($hsl)) && !(is_array($hsl) && is_object($hsl))) {
            $this->hsl = [$hsl, $stat, $light];

            list($this->hue, $this->saturation, $this->lightness) = $this->hsl;

        } elseif (isset($hsl) && (is_array($hsl) || (is_object($hsl) && $hsl instanceof ConvertContract))) {
            if ($hsl instanceof ConvertContract)
                $this->hsl = $hsl->toHsl()->toArray();
            else
                $this->hsl = $hsl;
        } else {
            dd($this, func_get_args());
        }
    }

    /**
     * @return $this
     */
    public function toHsl()
    {
        return $this;
    }

    /**
     * @param $percent
     * @return $this
     */
    public function lighten($percent)
    {
        $this->lightness *= $percent;
        $this->hsl[2] = $this->lightness;
        return $this;
    }

    /**
     * @param $percent
     * @return $this
     */
    public function darken($percent)
    {
        return $this->lighten($percent);
    }

    /**
     * @return array|ConvertContract
     */
    public function toArray()
    {
        return $this->hsl;
    }

    /**
     * @param int $option
     * @return mixed
     */
    public function toJson($option = 0)
    {
        return json_decode($this->hsl);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toHex();
    }

    /**
     * @return Hex
     */
    public function toHex()
    {
        return $this->toRgb()->toHex();
    }

    /**
     * @return Rgb
     */
    public function toRgb()
    {
        $rgb = [];
        // Fill variables $h, $s, $l by array given.
        list($h, $s, $l) = $this->hsl;
        $h /= 360;
        $s /=100;
        $l /=100;
        // If saturation is 0, the given color is grey and only
        // lightness is relevant.
        if ($s == 0) {
            $rgb = array($l, $l, $l);
        }

        // Else calculate r, g, b according to hue.
        // Check http://en.wikipedia.org/wiki/HSL_and_HSV#From_HSL for details
        else {
            $chroma = (1 - abs(2 * $l - 1)) * $s;
            $h_ = $h * 6;
            $x = $chroma * (1 - abs((fmod($h_, 2)) - 1)); // Note: fmod because % (modulo) returns int value!!
            $m = $l - round($chroma / 2, 10); // Bugfix for strange float behaviour (e.g. $l=0.17 and $s=1)

            if ($h_ >= 0 && $h_ < 1) $rgb = array(($chroma + $m), ($x + $m), $m);
            else if ($h_ >= 1 && $h_ < 2) $rgb = array(($x + $m), ($chroma + $m), $m);
            else if ($h_ >= 2 && $h_ < 3) $rgb = array($m, ($chroma + $m), ($x + $m));
            else if ($h_ >= 3 && $h_ < 4) $rgb = array($m, ($x + $m), ($chroma + $m));
            else if ($h_ >= 4 && $h_ < 5) $rgb = array(($x + $m), $m, ($chroma + $m));
            else if ($h_ >= 5 && $h_ < 6) $rgb = array(($chroma + $m), $m, ($x + $m));
        }

        foreach($rgb as $k => $v)
            $rgb[$k] *= 255;
//        dd($rgb);
        return new Rgb($rgb);
    }

    /**
     * @param $percent
     * @return $this
     */
    public
    function saturate($percent)
    {
        $this->saturation *= $percent;
        $this->hsl[1] = $this->saturation;
        return $this;
    }

    /**
     * @param $percent
     * @return mixed
     */
    public
    function desaturate($percent)
    {
        return $this->saturation($percent);
    }

    public
    function getHue()
    {
        return $this->hue;
    }

    public
    function setHue($hue)
    {

        $this->hue = $hue;
        $this->hsl[0] = $this->hue;
        return $this;
    }
}