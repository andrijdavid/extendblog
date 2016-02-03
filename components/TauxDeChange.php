<?php namespace Zingabory\Extendblog\Components;

use Cms\Classes\ComponentBase;
use System\Classes\CombineAssets;
use Zingabory\Extendblog\Models\Setting;

class TauxDeChange extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Taux De Change',
            'description' => 'Widget pour afficher le taux de change actuel'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun(){
        $this->addJs(CombineAssets::combine([
            '~/plugins/zingabory/extendblog/bower_components/accounting.js/accounting.js'
            ]));
        $this->addJs(CombineAssets::combine([
            '~/plugins/zingabory/extendblog/bower_components/money.js/money.js'
            ]));
    }

    public function latest(){
        return Setting::get('data');
    }

    public function currencies(){
        return Setting::get('currencies');
    }

    public function base(){
        return Setting::get('base');
    }
    
    public function symbols(){
        return Setting::get('symbols');
    }
    
    public function appId(){
        return Setting::get('appId');
    }
}