[![Total Downloads](https://poser.pugx.org/vadymsemeniuk/yii2-image-require-validator/d/total.png)](https://packagist.org/packages/vadymsemeniuk/yii2-image-require-validator)
[![Latest Stable Version](https://poser.pugx.org/vadymsemeniuk/yii2-image-require-validator/v/stable.png)](https://packagist.org/packages/vadymsemeniuk/yii2-image-require-validator)
[![Dependency Status](https://www.versioneye.com/php/vadymsemeniuk:yii2-image-require-validator/dev-master/badge?style=flat)](https://www.versioneye.com/php/vadymsemeniuk:yii2-image-require-validator)

Yii2 Image require validator 
============================
Extension for validating image, throw EntityToFile model

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vadymsemeniuk/yii2-image-require-validator "*"
```

or add

```
"vadymsemeniuk/yii2-image-require-validator": "*"
```

to the require section of your `composer.json` file.


Usage
-----

An example of usage could be:

Your code must look like this

```php
use vadymsemenykv\imageRequireValidator\ImageRequireValidator

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
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titleImage'],
                ImageRequireValidator::className(),
                'errorMessage' => 'Title image cannot be blank.',
                'imageRelation' => 'image',
                'skipOnEmpty' => false
            ],
        ];
    }   
}
```

If you save in relation many images, and also want to validate min or|and max num of images your code must look like this:

```php
use vadymsemenykv\imageRequireValidator\ImageRequireValidator

/**
 * @property EntityToFile $images
 */
class Article extends ActiveRecord {
    public $titleImages;
    
    public function getImages()
    {
        return $this->hasMany(EntityToFile::className(), ['entity_model_id' => 'id'])
            ->andOnCondition(['t2.entity_model_name' => static::formName(), 't2.attribute' => EntityToFile::TYPE_ARTICLE_TITLE_IMAGE])
            ->from(['t2' => EntityToFile::tableName()])
            ->orderBy('t2.position DESC');
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titleImages'],
                ImageRequireValidator::className(),
                'errorMessage' => 'Title images cannot be blank.',
                'validateNum' => true,
                'errorNumMinMessage' => 'Title images count should not be less than 3',
                'errorNumMaxMessage' => 'Title images count should not be more than 6',
                'minNumOfImages' => 3,
                'maxNumOfImages' => 6,
                'imageRelation' => 'images',
                'skipOnEmpty' => false
            ],
        ];
    }   
}
```
