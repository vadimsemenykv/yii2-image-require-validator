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
use yii\validators\Validator;

class ImageRequireValidator extends Validator
{
    public $imageRelation = 'image';
    public $errorMessage = 'Image cannot be blank.';
    public $errorNumMinMessage = 'Image cannot be blank.';
    public $errorNumMaxMessage = 'Image cannot be blank.';

    public $validateNum = false;
    public $minNumOfImages = null;
    public $maxNumOfImages = null;

    public function validateAttribute($model, $attribute)
    {
        $imageSavedBySign = EntityToFile::find()->where('temp_sign = :sign', [':sign' => $model->sign])->all();
        $imageRelation = $model->{$this->imageRelation};
        if (!$imageRelation && !$imageSavedBySign) {
            $this->addError($model, $attribute, $this->errorMessage);
        }
        if ($this->validateNum) {
            $this->validateByNum($model, $attribute, $imageRelation, $imageSavedBySign);
        }

    }

    /**
     * @param $model
     * @param $attribute
     * @param $imageRelation
     * @param $imageSavedBySign
     * @return bool
     */
    private function validateByNum($model, $attribute, $imageRelation, $imageSavedBySign)
    {
        $result = false;
        if ($this->minNumOfImages !== null && $this->validateForMinValue($imageRelation, $imageSavedBySign)) {
            $this->addError($model, $attribute, $this->errorNumMinMessage);
        }
        if ($this->maxNumOfImages !== null && $this->validateForMaxValue($imageRelation, $imageSavedBySign)) {
            $this->addError($model, $attribute, $this->errorNumMaxMessage);
        }
    }

    /**
     * @param $imageRelation
     * @param $imageSavedBySign
     * @return bool
     */
    private function validateForMinValue($imageRelation, $imageSavedBySign)
    {
        return !((count($imageRelation) >= $this->minNumOfImages) || (count($imageSavedBySign) >= $this->minNumOfImages));
    }

    /**
     * @param $imageRelation
     * @param $imageSavedBySign
     * @return bool
     */
    private function validateForMaxValue($imageRelation, $imageSavedBySign)
    {
        return (
            !(count($imageRelation) <= $this->maxNumOfImages) ||
            !(count($imageSavedBySign) <= $this->maxNumOfImages) ||
            !((count($imageRelation) + count($imageSavedBySign)) <= $this->maxNumOfImages)
        );
    }


}