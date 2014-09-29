<?php
/**
 * Created by PhpStorm.
 * User: Nghia
 * Date: 9/30/2014
 * Time: 4:28 AM
 */

namespace yii\markdown;


use yii\web\AssetBundle;

class MarkdownEditorAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-markdown';
    public $js = ['js/bootstrap-markdown.js'];
    public $css = ['css/bootstrap-markdown.min.css'];
} 