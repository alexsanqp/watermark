<?php

namespace alexsanqp\watermark;

use yii\base\Exception;
use yii\imagine\Image;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

class Watermark
{
    const DEFAULT_PERCENTAGE_RATIO = 0.3;

    /**
     * @var ImageInterface
     */
    public $image;

    /**
     * @var ImageInterface
     */
    public $imageWatermark;


    /**
     * @var Path
     */
    private $path;

    /**
     * @var Position
     */
    private $position;

    /**
     * @var BoxInterface
     */
    private $imageSize;

    /**
     * @var BoxInterface
     */
    private $imageWaterSize;

    /**
     * @var float|int
     */
    private $watermarkRatio;

    /**
     * @var float|int
     */
    private $watermarkNewWidth;

    /**
     * @var float|int
     */
    private $watermarkNewHeight;

    /**
     * @var float|int
     */
    private $percentageRatio;

    /**
     * @var float|int
     */
    private $rotate;

    /**
     * @var string
     */
    private $posX;

    /**
     * @var string
     */
    private $posY;

    /**
     * @var boolean
     */
    private $isWatermark;


    /**
     * Watermark constructor.
     * @param string $imagePath - Path to parent image
     * @param string $watermarkPath - Path to watermark
     */
    public function __construct($imagePath, $watermarkPath)
    {
        $this->path = new Path($imagePath, $watermarkPath, null);
        $this->position = new Position();

        $this->percentageRatio = static::DEFAULT_PERCENTAGE_RATIO;
        $this->posX = Position::TOP;
        $this->posY = Position::LEFT;
        $this->isWatermark = false;

        $this->init();
    }

    public function __destruct()
    {
        $this->image = null;
        $this->imageWatermark = null;
    }

    /**
     * Method for init data
     * @return void
     * @throws Exception
     */
    protected function init()
    {
        try {
            $this->image = Image::getImagine()->open($this->path->getImagePath());
            $this->imageWatermark = Image::getImagine()->open($this->path->getWatermarkPath());

            $this->imageSize = $this->image->getSize();
            $this->imageWaterSize = $this->imageWatermark->getSize();

            // Get ratio watermark image
            $this->watermarkRatio = $this->imageWaterSize->getWidth() / $this->imageWaterSize->getHeight();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Method to get an image type
     *
     * @param String $filePath
     * @return null|string
     */
    public static function getTypeFile($filePath)
    {
        if (file_exists($filePath)) {
            $imageInfo = getimagesize($filePath);

            switch ($imageInfo[2]) {
                case IMAGETYPE_JPEG:
                    return 'image/jpeg';
                case IMAGETYPE_GIF:
                    return 'image/gif';
                case IMAGETYPE_PNG:
                    return 'image/png';
            }
        }

        return null;
    }

    /**
     * Method to get the watermark coordinates in the parent image
     *
     * @return array
     */
    public function getPosition()
    {
        return $this->position->getPosition(
            $this->imageSize,
            $this->imageWatermark->getSize(),
            $this->posX,
            $this->posY
        );
    }

    /**
     * Method to get the watermark coordinates in the parent image
     *
     * @return Point
     */
    public function getPositionPoint()
    {
        return $this->position->getPositionPoint(
            $this->imageSize,
            $this->imageWatermark->getSize(),
            $this->posX,
            $this->posY
        );
    }

    /**
     * Setting the position of the watermark in the image
     *
     * @param string $posX
     * @param string $posY = Position::LEFT
     */
    public function setPosition($posX, $posY = Position::LEFT)
    {
        $this->posX = $posX;
        $this->posY = $posY;
    }

    /**
     * Method to get the path to save a new image
     *
     * @return string Image path to save
     */
    public function getSaveImagePath()
    {
        return $this->path->getSaveImagePath()
            ? $this->path->getSaveImagePath()
            : $this->path->getImagePath();
    }

    /**
     * Method to set the path for saving a new image with a watermark
     *
     * @param string $path Default value is a current image path
     * @return void
     */
    public function setSaveImagePath($path)
    {
        $this->path->setSaveImagePath($path);
    }

    /**
     * @return Integer
     */
    public function getWatermarkRatio()
    {
        return $this->watermarkRatio;
    }

    /**
     * @param Integer $ratio
     */
    public function setWatermarkRatio($ratio)
    {
        $this->watermarkRatio = $ratio;
    }

    /**
     * @return float
     */
    public function getPercentageRatio()
    {
        return $this->percentageRatio;
    }

    /**
     * @param float $percentageRatio
     */
    public function setPercentageRatio($percentageRatio)
    {
        $this->percentageRatio = $percentageRatio;
    }


    /**
     * Method for creating a new image with a watermark
     */
    public function watermark()
    {
        $this->isWatermark = true;
        $this->prepareWatermark();
    }

    /**
     * Save the origin image before append a watermark
     *
     * @param string $pref ='original_'
     */
    public function saveOrigin($pref = 'original_')
    {
        $this->image->save($pref . $this->getSaveImagePath());
    }

    /**
     * @return ImageInterface
     * @throws Exception
     */
    public function getImageWithWatermark()
    {
        if ($this->isWatermark) {
            $this->image->paste($this->imageWatermark, $this->getPositionPoint());

            return $this->image;
        }

        throw new Exception('Watermark is not init');
    }

    /**
     * @return ImageInterface|null
     * @throws Exception
     */
    public function save()
    {
        if ($this->isWatermark) {
            $this->image->paste($this->imageWatermark, $this->getPositionPoint());
        }

        if ($this->image instanceof ImageInterface) {
            return $this->image->save($this->getSaveImagePath());
        }

        throw new Exception('Image is not init');
    }

    /**
     * @param Integer $angle
     */
    public function rotate($angle)
    {
        $this->rotate = $angle;
    }

    /**
     * @return BoxInterface
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * @param BoxInterface $imageSize
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;
    }

    /**
     * @return BoxInterface
     */
    public function getImageWaterSize()
    {
        return $this->imageWaterSize;
    }

    /**
     * @param BoxInterface $imageWaterSize
     */
    public function setImageWaterSize($imageWaterSize)
    {
        $this->imageWaterSize = $imageWaterSize;
    }

    public function setProportionImage($width, $height, $mode = ManipulatorInterface::THUMBNAIL_INSET)
    {
        $this->image = $this->image->thumbnail(new Box($width, $height), $mode);
        $this->imageSize = $this->image->getSize();
    }


    /**
     * Prepare the watermark before to adding in the parent image
     */
    protected function prepareWatermark()
    {
        $this->scaleWatermarkProportion();

        $this->imageWatermark->resize(new Box(
            $this->watermarkNewWidth,
            $this->watermarkNewHeight
        ));

        if (!empty($this->rotate)) {
            $palette = new RGB();
            $color = $palette->color(Image::$thumbnailBackgroundColor, 0);
            $this->imageWatermark->rotate($this->rotate, $color);
        }
    }

    /**
     * Method to get new proportions to the watermark image
     */
    protected function scaleWatermarkProportion()
    {
        $this->watermarkNewWidth = $this->imageSize->getWidth() * $this->getPercentageRatio();
        $this->watermarkNewHeight = $this->watermarkNewWidth / $this->getWatermarkRatio();
    }
}
