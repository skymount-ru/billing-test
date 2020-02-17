<?php

namespace frontend\components\ui;

use common\storage\FileRepositoryInterface;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ActiveField extends \yii\bootstrap4\ActiveField
{
    /**
     * @param array $options
     * @return \yii\widgets\ActiveField
     */
    public function switch($options = [])
    {
        $this->checkTemplate = "<div class=\"custom-control custom-switch custom-checkbox\">\n{input}\n{label}\n{error}\n{hint}\n</div>";
        return parent::checkbox($options);
    }

    /**
     * @param array $options
     * @return \yii\widgets\ActiveField
     * @throws \yii\db\Exception
     */
    public function fileInputWithPreview($options = [])
    {
        if ($file = ArrayHelper::remove($options, 'file')) {
            if (! $file instanceof FileRepositoryInterface) {
                throw new \yii\db\Exception('File exists, but is not of a FileRepository type.');
            }
            /**
             * @var FileRepositoryInterface $file
             */
            $deleteLink = Url::to(['file-delete', 'type' => $file->getType(), 'uuid' => $this->model->uuid, 'file_uuid' => $file->getUUID()]);
            $innerHtml = <<<HTML
                <div class="file-input-preview _file-exists" style="background-image: url('{$file->getUrl()}');">
                    <a href="{$deleteLink}" class="file-input-preview__delete-link" data-method="post" data-confirm="Delete the file?">Delete</a>
                </div>
            HTML;
        } else {
            $this->hintOptions['hint'] = 'Click to add image';
            $this->hintOptions['class'][] = 'input-hint';
            $this->parts['{hint}'] = Html::activeHint($this->model, $this->attribute, $this->hintOptions);
            $innerHtml = <<<HTML
                {beginLabel}
                <div class="file-input-preview" data-file-preview>
                    {input}
                    {hint}
                    {error}
                </div>
                {endLabel}
            HTML;
        }
        $this->template = <<<HTML
            <div class="form-group">
                {label}
                {$innerHtml}
            </div>
        HTML;
        Html::addCssClass($options, ['widget' => 'form-control-file']);
        return parent::fileInput($options);
    }
}
