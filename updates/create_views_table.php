<?php namespace Zingabory\ExtendBlog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use System\Classes\PluginManager;

class CreateViewsTable extends Migration
{

    public function up()
    {
        if(PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            Schema::table('rainlab_blog_posts', function($table)
            {
                $table->integer('view')->unsigned()->default(0);
            });
        }
    }

    public function down()
    {
        if(PluginManager::instance()->hasPlugin('RainLab.Blog'))
    {
        Schema::table('rainlab_blog_posts', function($table)
        {
            $table->dropColumn('view');
        });
    }
    }

}
