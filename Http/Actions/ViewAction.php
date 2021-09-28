<?php

namespace Modules\Theme\Http\Actions;

use Modules\Theme\Http\Actions\AbstractAction;

class ViewAction extends AbstractAction
{
    public function getTitle()
    {
        return __('voyager::generic.view');
    }

    public function getIcon()
    {
        return 'voyager-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes($actionParams = ['type'=>false])
    {
        if ($actionParams['type'] == 'widget') {
            return [
                'class' => 'ui primary button item'
            ];
         }
        return [
            'class' => 'ui primary button view right floated',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.'.$this->dataType->slug.'.show', $this->data->{$this->data->getKeyName()});
    }
}
