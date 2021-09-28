<?php

namespace Modules\Theme\Http\Actions;

use Modules\Theme\Http\Actions\AbstractAction;

class DeleteAction extends AbstractAction
{
    public function getTitle()
    {
        return __('voyager::generic.delete');
    }

    public function getIcon()
    {
        return 'voyager-trash';
    }

    public function getPolicy()
    {
        return 'delete';
    }

    public function getAttributes()
    {
        return [
            'class'   => 'ui negative button view delete right floated',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id'      => 'delete-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    public function getDefaultRoute()
    {
        return 'javascript:;';
    }
}
