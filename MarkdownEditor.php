<?php
/**
 * Created by PhpStorm.
 * User: Nghia
 * Date: 9/30/2014
 * Time: 4:26 AM
 */
namespace yii\markdown;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\widgets\InputWidget;
use yii\helpers\Html;

class MarkdownEditor extends InputWidget
{
    public $clientOptions;
    private $_assetBundle;

    public function init()
    {
        if ($this->hasModel()) {
            $this->options['id'] = Html::getInputId($this->model, $this->attribute);
        } else {
            $this->options['id'] = $this->getId();
        }
        $this->registerAssetBundle();
        $this->registerLocate();
        $this->registerScript();
        $this->registerEvent();
    }

    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
    }

    public function registerAssetBundle()
    {
        $this->_assetBundle = MarkdownEditorAsset::register($this->getView());
    }

    public function registerLocate()
    {
        $locate = ArrayHelper::getValue($this->clientOptions, 'language', false);
        if ($locate) {
            $locateAsset = 'locale/bootstrap-markdown.' . $locate . '.js';
            if (file_exists(Yii::getAlias($this->assetBundle->sourcePath . '/' . $locateAsset))) {
                $this->assetBundle->js[] = $locateAsset;
            } else {
                ArrayHelper::remove($this->clientOptions, 'language');
            }
        }

    }

    public function registerScript()
    {
        $configure = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
        $js = "jQuery('#{$this->options['id']}').markdown({$configure});";
        $this->getView()->registerJs($js);
    }

    public function registerEvent()
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handle) {
                $js[] = "jQuery('#{$this->options['id']}').on('{$event}',{$handle});";
            }
            $this->getView()->registerJs(implode(PHP_EOL, $js));
        }
    }

    public function getAssetBundle()
    {
        if (!($this->_assetBundle instanceof AssetBundle)) {
            $this->registerAssetBundle();
        }
        return $this->_assetBundle;
    }
}

