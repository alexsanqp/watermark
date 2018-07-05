<?php

namespace alexsanqp\watermark;

use Imagine\Image\BoxInterface;
use Imagine\Image\Point;

class Position
{
    const TOP = 'top';
    const LEFT = 'left';
    const RIGHT = 'right';
    const BOTTOM = 'bottom';
    const CENTER = 'center';
    const CUSTOM = 'custom';

    const WATERMARK_MARGIN_TOP = 10;
    const WATERMARK_MARGIN_LEFT = 10;
    const WATERMARK_MARGIN_RIGHT = 10;
    const WATERMARK_MARGIN_BOTTOM = 10;

    /**
     * Get the position of the watermark on the parent image
     *
     * @param BoxInterface $imageSize
     * @param BoxInterface $watermarkSize
     * @param Integer $posX
     * @param Integer $posY
     * @return array
     */
    public function getPosition($imageSize, $watermarkSize, $posX, $posY)
    {
        return [
            $this->getPositionX($imageSize, $watermarkSize, $posX),
            $this->getPositionY($imageSize, $watermarkSize, $posY)
        ];
    }

    /**
     * Get the position of the watermark on the parent image
     *
     * @param $imageSize
     * @param $watermarkSize
     * @param Integer $posX
     * @param Integer $posY
     * @return Point
     */
    public function getPositionPoint($imageSize, $watermarkSize, $posX, $posY)
    {
        return new Point(
            $this->getPositionX($imageSize, $watermarkSize, $posX),
            $this->getPositionY($imageSize, $watermarkSize, $posY)
        );
    }

    /**
     * Calculates a position on the X axis by type $posX
     *
     * @param BoxInterface $imageSize
     * @param BoxInterface $watermarkSize
     * @param string $posX
     * @return float|int
     */
    protected function getPositionX($imageSize, $watermarkSize, $posX)
    {
        $positionX = static::WATERMARK_MARGIN_LEFT;

        if ($posX === static::RIGHT) {
            $positionX = ($imageSize->getWidth() - $watermarkSize->getWidth()) - static::WATERMARK_MARGIN_RIGHT;
        } elseif ($posX === static::CENTER) {
            $positionX = ($imageSize->getWidth() / 2) - ($watermarkSize->getWidth() / 2);
        }

        return $positionX;
    }

    /**
     * Calculates a position on the Y axis by type $posY
     *
     * @param BoxInterface $imageSize
     * @param BoxInterface $watermarkSize
     * @param string $posY
     * @return float|int
     */
    protected function getPositionY($imageSize, $watermarkSize, $posY)
    {
        $positionY = static::WATERMARK_MARGIN_TOP;

        if ($posY === static::CENTER) {
            $positionY = ($imageSize->getHeight() / 2) - ($watermarkSize->getHeight() / 2);
        } elseif ($posY === static::BOTTOM) {
            $positionY = $imageSize->getHeight() - $watermarkSize->getHeight() - static::WATERMARK_MARGIN_BOTTOM;
        }

        return $positionY;
    }
}
