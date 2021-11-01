<?php

namespace Modules\Theme\Http\Actions\Modules;

use Modules\Theme\Http\Actions\AbstractAction;
use Modules\Theme\Entities\Module;
use Illuminate\Support\Str;
use Igaster\LaravelTheme\Facades\Theme as LaravelTheme;
use Modules\Theme\Entities\Theme;

class ThemeUpdateAction extends AbstractAction
{
    public function __construct($dataType, $data)
    {
        $this->dataType = $dataType;
        $this->data = $data;
        $this->isBulk = true;
        $this->isSingle = true;
    }

    public function getTitle($actionParams = ['type'=>false, 'id'=>false])
    {
        if ($actionParams['type']) {
            if (isset($actionParams['id']) && $actionParams['id']) {
                $id = $actionParams['id'];
                $theme = Theme::find($id);
            }
            return false;
        }
        return 'Check for Updates';
    }

    public function getIcon()
    {
        return 'fas fa-sync';
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
                // $theme = Theme::find($id);
            }
        } else {
            // Mass Action (all)
            $themes = Theme::all();
            foreach ($themes as $theme) {
                $url = false;
                if ($theme->url) {
                    $url = $theme->url;
                }
                if ($url) {
                    $responseGH = \Http::get("https://api.github.com/repos/{$url}/commits/master")->collect();
                    if ($responseGH->count() && $responseGH->get('sha')) {
                        $theme->current_sha = $responseGH->get('sha');
                        $theme->save();
                    }
                }
            }
        }
        return redirect($comingFrom);
    }

    private function isUrl($url){
        return preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $url);
    }
}