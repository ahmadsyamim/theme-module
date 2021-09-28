<?php

namespace Modules\Theme\Http\Actions;

use Modules\Theme\Http\Actions\AbstractAction;

class EditAction extends AbstractAction
{
    public function getTitle()
    {
        return __('voyager::generic.edit');
    }

    public function getIcon()
    {
        return 'voyager-edit';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => 'ui positive button view edit right floated',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.'.$this->dataType->slug.'.edit', $this->data->{$this->data->getKeyName()});
    }
}
