<?php namespace RainLab\Builder\Behaviors;

use RainLab\Builder\Classes\IndexOperationsBehaviorBase;
use RainLab\Builder\Classes\ModelModel;
use Backend\Behaviors\FormController;
use ApplicationException;
use Exception;
use Request;
use Input;

/**
 * Model management functionality for the Builder index controller
 *
 * @package rainlab\builder
 * @author Alexey Bobkov, Samuel Georges
 */
class IndexModelOperations extends IndexOperationsBehaviorBase
{
    protected $baseFormConfigFile = '~/plugins/rainlab/builder/classes/modelmodel/fields.yaml';

    public function onModelLoadPopup()
    {
        $pluginCodeObj = $this->getPluginCode();

        try {
            $widget = $this->makeBaseFormWidget(null);
            $this->vars['form'] = $widget;
            $widget->model->setPluginCodeObj($pluginCodeObj);
            $this->vars['pluginCode'] = $pluginCodeObj->toCode();
        }
        catch (ApplicationException $ex) {
            $this->vars['errorMessage'] = $ex->getMessage();
        }

        return $this->makePartial('model-popup-form');
    }

    public function onModelSave()
    {
        $pluginCode = Request::input('plugin_code');

        $model = $this->loadOrCreateBaseModel(null);
        $model->setPluginCode($pluginCode);

        $model->fill($_POST);
        $model->save();

        return $this->controller->widget->modelList->updateList();
    }

    protected function loadOrCreateBaseModel($className)
    {
        // Editing model is not supported, always return
        // a new object.

        return new ModelModel();
    }
}