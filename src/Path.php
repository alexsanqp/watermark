<?php

namespace alexsanqp\watermark;

use Yii;

class Path
{
    /**
     * @var string
     */
    private $imagePath;

    /**
     * @var string
     */
    private $saveImagePath;

    /**
     * @var string
     */
    private $watermarkPath;

    public function __construct(
        $imagePath,
        $watermarkPath,
        $saveImagePath = null
    ) {
        $this->imagePath = Yii::getAlias($imagePath);
        $this->watermarkPath = Yii::getAlias($watermarkPath);
        $this->saveImagePath = Yii::getAlias($saveImagePath);
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * @return string
     */
    public function getWatermarkPath()
    {
        return $this->watermarkPath;
    }

    /**
     * @param string $watermarkPath
     */
    public function setWatermarkPath($watermarkPath)
    {
        $this->watermarkPath = $watermarkPath;
    }

    /**
     * @return string
     */
    public function getSaveImagePath()
    {
        return $this->saveImagePath;
    }

    /**
     * @param string $saveImagePath
     */
    public function setSaveImagePath($saveImagePath)
    {
        $this->saveImagePath = $saveImagePath;
    }
}
