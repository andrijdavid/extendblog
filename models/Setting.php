<?php namespace Zingabory\Extendblog\Models;

use Model;

/**
 * Settings Model
 */
class Setting extends Model
{

	public $implement = ['System.Behaviors.SettingsModel'];

	public $settingsCode = 'zingabory_extendBlog_tauxDeChange_setting';

	public $settingsFields = 'fields.yaml';

}