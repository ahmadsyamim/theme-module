<?php

namespace Modules\Theme\Http\Actions\Modules;

use Modules\Theme\Http\Actions\AbstractAction;
use Modules\Theme\Entities\Module;
use Illuminate\Support\Str;
use Igaster\LaravelTheme\Facades\Theme as LaravelTheme;
use Modules\Theme\Entities\Theme;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
                if ($theme->sha && $theme->sha != $theme->current_sha) {                
                    return 'Update available';
                }
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
                $theme = Theme::find($id);
                if ($theme->url) {
                    $updatePackage = $this->updatePackage($theme);
                    $output = $this->checkOutdatedPackages();
                    $success = true;
                    foreach ($output as $o) {
                        if ($o->name == $theme->url) {
                            $success = false;
                            break;
                        } 
                    }
                    // Get Composer info
                    $json = Collect(json_decode(file_get_contents(base_path("vendor/{$theme->url}/composer.json")), true));
                    if ($success && $json->get('title')) {
                        \File::delete("storage/themes/{$json->get('title')}.theme.tar.gz");
                        \File::put("storage/themes/{$json->get('title')}.theme.tar.gz", file_get_contents(base_path("vendor/{$theme->url}/dist/{$json->get('title')}.theme.tar.gz")));

                        \Artisan::call("theme:remove {$json->get('title')} --force");
                        \Artisan::call("theme:install", ['package' => $json->get('title')]);
                        
                        if ($theme->default) {
                            LaravelTheme::set($theme->title);
                        }

                        $theme->current_sha = $theme->sha;
                        $theme->last_update_at = \Carbon\Carbon::now();
                        $theme->save();

                    }
                } 
            }
        } else {
            // Mass Action (all)
            $themes = Theme::all();

            // Mass Action (all)
            $output = $this->checkOutdatedPackages();
            $themes = Theme::all();
            foreach ($themes as $theme) {
                if ($theme->url) {
                    foreach ($output as $o) {
                        if ($o->name == $theme->url) {
                            $theme->sha = $o->latest;
                            $theme->save();   
                        } 
                    }
                }
            }
        }
        return redirect($comingFrom);
    }

    private function isUrl($url){
        return preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $url);
    }

    private function checkOutdatedPackages(){
        $process = Process::fromShellCommandline(sprintf(
            'cd %s && composer outdated --format=json',
            base_path()
        ), null, ['COMPOSER_HOME' => getenv('COMPOSER_HOME')]);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output = json_decode($process->getOutput());    
        return $output->installed;
    }

    private function updatePackage($theme){
        $process = Process::fromShellCommandline(sprintf(
            'cd %s && composer update %s',
            base_path(),
            $theme->url
        ), null, ['COMPOSER_HOME' => getenv('COMPOSER_HOME')]);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output = json_decode($process->getOutput());    
        return $output;
    }
}