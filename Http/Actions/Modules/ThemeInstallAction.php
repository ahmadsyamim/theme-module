<?php

namespace Modules\Theme\Http\Actions\Modules;

use Modules\Theme\Http\Actions\AbstractAction;
use Modules\Theme\Entities\Module;
use Illuminate\Support\Str;

class ThemeInstallAction extends AbstractAction
{
    public function __construct($dataType, $data)
    {
        $this->dataType = $dataType;
        $this->data = $data;
        $this->data = $data;
        $this->isBulk = false;
        $this->isSingle = true;
    }

    public function getTitle($actionParams = ['type'=>false, 'id'=>false])
    {
        if ($actionParams['type']) {
            if (isset($actionParams['id']) && $actionParams['id']) {
                $id = $actionParams['id'];
                // $module = Module::find($id);
                // $moduleInfo = \Module::find($module->slug);
                // if ($moduleInfo && $moduleInfo->isStatus(true)) {
                //     return 'Disable';
                // } else if ($moduleInfo && !$moduleInfo->isStatus(true)) {
                //     return 'Enable';        
                // }
            }
            return 'Install';
        }
        return 'Bulk Install';
    }

    public function getIcon()
    {
        return 'fas fa-plug';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes($actionParams = ['type'=>false])
    {
        $type = $actionParams['type'] ?? ['type'=>false];
        if ($type == 'single') {
            return [
                'class' => 'ui primary button right floated'
            ];
        } else if ($type == 'widget') {
            return [
                'class' => 'ui button item'
            ];
        }
        return [
            'class' => 'btn btn-primary',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.themes.index');
    }


    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'themes';
    }

    public function massAction($ids, $comingFrom)
    {
        if (is_array($ids) && $ids[0]) {
            foreach ($ids as $id) {
               
            }
        }
        return redirect($comingFrom);
    }

    private function isUrl($url){
        return preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $url);
    }
}