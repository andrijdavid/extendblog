<?php namespace Zingabory\ExtendBlog;

use Backend;
use RainLab\Blog\Models\Post;
use System\Classes\PluginBase;
use Zingabory\Extendblog\Components\BlogTagSearch;
use Zingabory\Extendblog\Components\ByCategory;
use Zingabory\ExtendBlog\Components\LatestPost;
use Zingabory\ExtendBlog\Components\MostViewedPost;
use Zingabory\ExtendBlog\Components\RelatedPost;
use Zingabory\ExtendBlog\Components\TauxDeChange;
use Zingabory\ExtendBlog\Components\MomentJS;
use Zingabory\Extendblog\Models\Setting;
use Zingabory\Extendblog\ReportWidgets\Stat;

/**
 * extendBlog Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * @var array   Require the RainLab.Blog plugin
     */
    public $require = ['RainLab.Blog'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'extendBlog',
            'description' => 'provide some component to extend bloog plugin',
            'author'      => 'zingabory',
            'icon'        => 'icon-pencil'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            RelatedPost::class => 'relatedPost',
            MostViewedPost::class => 'mostViewedPost',
            LatestPost::class => 'latestPost',
            BlogTagSearch::class => 'blogTagSearch',
            ByCategory::class => 'byCategory',
            TauxDeChange::class => 'tauxDeChange',
            MomentJS::class => 'momentJS'

        ];
    }

    public function registerSettings(){
        return [
            'tauxDeChange' => [
                'label' => 'Taux de change',
                'description' => 'Gérer les taux de change',
                'order' => 500,
                'icon' => 'icon-money',
                'category' => 'Divers',
                'class' => 'Zingabory\extendBlog\Models\Setting',
                //'url' => Backend::url('zingabory/company/company'),
                'keywords' => 'taux de change, taux, change, money'
            ]
        ];
    }

    public function boot()
    {
        Post::extend(function($model){
            $model->bindEvent('model.afterFetch', function() use ($model) {
                $model->view+=1;
                $model->save();
            });
        });
    }

    public function registerReportWidgets(){
        return [
            Stat::class => [
                'label' => 'Statistique',
                'code' => 'statistique',
                'context' => 'dashboard'
            ]
        ];
    }

    public function registerSchedule($schedule)
    {
        $schedule->call(function(){
            $appId = Setting::get('appId');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1, 
                CURLOPT_URL => 'http://openexchangerates.org/api/latest.json?app_id='.$appId,
                CURLOPT_USERAGENT => 'OEPABOT'
                ));
            $data = curl_exec($curl);
            curl_close($curl);
            if(!empty($data) || $data != "false" || $data != false)
                Setting::set('data', $data);
        })->hourly();

        /*$schedule->call(function(){
            $appId = Setting::get('appId');
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1, 
                CURLOPT_URL => 'http://openexchangerates.org/api/currencies.json',
                CURLOPT_USERAGENT => 'OEPABOT'
            ));

            $data = curl_exec($curl);
            curl_close($curl);
            Setting::set('currencies', $data);
        })->weekly();*/

    }
}
