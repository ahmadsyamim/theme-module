<?php

namespace Modules\Theme\Http\Actions;

abstract class AbstractAction implements ActionInterface
{
    protected $dataType;
    protected $data;
    protected $isBulk;
    protected $isSingle;

    public function __construct($dataType, $data)
    {
        $this->dataType = $dataType;
        $this->data = $data;
        $this->isBulk=true;
        $this->isSingle=true;
    }

    public function isBulk()
    {
        return $this->isBulk;
    }

    public function isSingle()
    {
        return $this->isSingle;
    }

    public function getDataType()
    {
    }

    public function getPolicy()
    {
    }

    public function getRoute($key)
    {
        if (method_exists($this, $method = 'get'.ucfirst($key).'Route')) {
            return $this->$method();
        } else {
            return $this->getDefaultRoute();
        }
    }

    public function getAttributes()
    {
        return [];
    }

    public function convertAttributesToHtml($actionParams = ['type'=>false])
    {
        $result = '';

        foreach ($this->getAttributes($actionParams) as $key => $attribute) {
            $result .= $key.'="'.$attribute.'"';
        }

        return $result;
    }

    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->name === $this->getDataType() || $this->getDataType() === null;
    }
}
