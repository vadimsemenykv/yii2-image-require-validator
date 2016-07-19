<?php
/**
 * Created by PhpStorm.
 * User: Vadym Semeniuk
 * Date: 7/16/16
 * Time: 6:20 PM
 */

namespace vadymsemenykv\imageRequireValidator;

use common\models\EntityToFile;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class ImageRequireValidatorBehavior extends Behavior
{
    public $imageRelation = 'image';
    public $attribute = 'titleImage';
    public $translateMessageCategory = 'app';
    public $errorMessage = 'Image cannot be blank.';
    
    private function validateImage()
    {
        /* @var ActiveRecord $this->owner*/
        $imageSavedBySign = EntityToFile::find()->where('temp_sign = :sign', [':sign' => $this->owner->sign])->one();
        if (!$this->owner->{$this->imageRelation} && !$imageSavedBySign) {
            $this->owner->addError($this->attribute, Yii::t($this->translateMessageCategory, $this->errorMessage));
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeValidate()
    {
        $this->validateImage();
    }
}