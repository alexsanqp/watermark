Yii2 library Watermark
=========
Creating a watermark by position in the image

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist alexsanqp/watermark "*"
```

or add

```
"alexsanqp/watermark": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
public function actionWatermark()
{
    $pathToWatermark = '@app/web/img/plusminus-watermark.png';
    $pathToImage = '@app/web/img/mountain.jpg';

    //If you need to set the image proportions
    $width = Yii::$app->request->get('width');
    $height = Yii::$app->request->get('height');

    $watermark = new Watermark($pathToImage, $pathToWatermark);
    $watermark->setPercentageRatio(0.4);
    $watermark->setPosition(Position::CENTER, Position::CENTER);
    $watermark->rotate(-40);

    if (!empty($width) && !empty($height)) {
        $watermark->setProportionImage($width, $height);
    }

    // append watermark
    $watermark->watermark();

    // Save
    if ($watermark->save()) {
        echo $watermark->getSaveImagePath();
    }
    
    // Or
    
    $rawImageWatermark = $watermark->getImageWithWatermark();

    if ($rawImageWatermark) {
        $imageWatermark = imagecreatefromstring($rawImageWatermark->get('jpg'));

        if ($imageWatermark !== false) {
            header('Content-Type: image/jpeg');

            imagejpeg($imageWatermark, null, 90);
            imagedestroy($imageWatermark);
        }
    }
}
```
<p align="center"><img src="https://user-images.githubusercontent.com/16621122/42344046-c092cb78-80a3-11e8-877e-15d485ec2416.jpg" /></p>

