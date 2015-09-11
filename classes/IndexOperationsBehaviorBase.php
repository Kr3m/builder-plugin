<?php namespace RainLab\Builder\Classes;

use Backend\Classes\ControllerBehavior;
use RainLab\Builder\Classes\PluginBaseModel;
use Backend\Behaviors\FormController;
use SystemException;
use Exception;
use Input;

/**
 * Base class for index operation behaviors
 *
 * @package rainlab\builder
 * @author Alexey Bobkov, Samuel Georges
 */
abstract class IndexOperationsBehaviorBase extends ControllerBehavior
{
    protected $baseFormConfigFile = null;

    protected function makeBaseFormWidget($modelCode)
    {
        if (!strlen($this->baseFormConfigFile)) {
            throw new ApplicationException(sprintf('Base form configuration file is not specified for %s behavior', get_class($this)));
        }
        
        $widgetConfig = $this->makeConfig($this->baseFormConfigFile);

        $widgetConfig->model = $this->loadOrCreateBaseModel($modelCode);
        $widgetConfig->alias = 'form_'.md5(get_class($this)).uniqid();

        $form = $this->makeWidget('Backend\Widgets\Form', $widgetConfig);
        $form->context = strlen($modelCode) ? FormController::CONTEXT_UPDATE : FormController::CONTEXT_CREATE;

        return $form;
    }

    protected function getPluginCode()
    {
        $vector = $this->controller->getBuilderActivePluginVector();

        if (!$vector) {
            throw new ApplicationException('Cannot determine the currently active plugin.');
        }

        return $vector->pluginCodeObj;
    }

    abstract protected function loadOrCreateBaseModel($modelCode);
}