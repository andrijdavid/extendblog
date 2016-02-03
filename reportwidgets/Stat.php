<?php namespace Zingabory\Extendblog\ReportWidgets;

use Backend\Classes\ReportWidgetBase;

/**
 * stat Form Widget
 */
class Stat extends ReportWidgetBase
{

    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'zingabory_extendblog_stat';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('stat');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addCss('css/stat.css', 'zingabory.extendblog');
        $this->addJs('js/stat.js', 'zingabory.extendblog');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }

}
