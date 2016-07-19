Yii2 Image validator behavior Behavior
======================================
Extension for validating image, throw EntityToFile model

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vadymsemeniuk/yii2-image-require-validator-behavior "*"
```

or add

```
"vadymsemeniuk/yii2-image-require-validator-behavior": "*"
```

to the require section of your `composer.json` file.


Usage
-----

An example of usage could be:

Your code must look like this

```php
use vadymsemenykv\imageRequireValidator\ImageRequireValidatorBehavior

/**
 * @property EntityToFile $image
 */
class Article extends ActiveRecord {
    public $titleImage;
    
    public function getImage()
    {
        return $this->hasOne(EntityToFile::className(), ['entity_model_id' => 'id'])
            ->andOnCondition(['t2.entity_model_name' => static::formName(), 't2.attribute' => EntityToFile::TYPE_ARTICLE_TITLE_IMAGE])
            ->from(['t2' => EntityToFile::tableName()])
            ->orderBy('t2.position DESC');
    }
    
    public function behaviors()
    {
        return [
            'imageValidation' => [
                'class' => ImageRequireValidatorBehavior::className(),
                'translateMessageCategory' => 'back/article',
                'errorMessage' => 'Title image cannot be blank.',
            ],
        ];
    }
}
```

