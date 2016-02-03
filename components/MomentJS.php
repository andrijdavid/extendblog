<?php namespace Zingabory\Extendblog\Components;

use Cms\Classes\ComponentBase;

use System\Classes\CombineAssets;
class MomentJS extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'momentJS Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function onRun()
    {
        $this->addJs(CombineAssets::combine([
            '~/plugins/zingabory/extendblog/bower_components/moment/min/moment-with-locales.min.js'
            ]));
    }

}