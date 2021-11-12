<?php

namespace Modules\Theme\Entities;

use Illuminate\Database\Eloquent\Model;
use Igaster\LaravelTheme\Facades\Theme as LaravelTheme;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class Theme extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'path',
        'url',
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $model->url)) {

            } else if ($model->url) {
                // Not URL
                $response = Http::get("https://packagist.org/search.json?q={$model->url}")->collect();
                if ($response->get('total')) {
                    $results = Collect($response->get('results'))->first();
                    if ($results['name']) {
                        $process = Process::fromShellCommandline(sprintf(
                            'cd %s && composer require %s',
                            base_path(),
                            $results['name'],
                        ), null, ['COMPOSER_HOME' => getenv('COMPOSER_HOME')]);
                        $process->run();
                        // executes after the command finishes
                        if (!$process->isSuccessful()) {
                            throw new ProcessFailedException($process);
                        }
                        $output = json_decode($process->getOutput());  

                        // Verify success
                        $process = Process::fromShellCommandline(sprintf(
                            'cd %s && composer info --self --format=json',
                            base_path("vendor/{$results['name']}"),
                        ), null, ['COMPOSER_HOME' => getenv('COMPOSER_HOME')]);
                        $process->run();
                        // executes after the command finishes
                        if (!$process->isSuccessful()) {
                            throw new ProcessFailedException($process);
                        }
                        $output = json_decode($process->getOutput()); 
                        if ($output->type != 'laravel-module-theme') {
                            throw new \Exception('Package type is not laravel-module-theme');
                        }

                        // Get Composer info
                        $json = Collect(json_decode(file_get_contents(base_path("vendor/{$results['name']}/composer.json")), true));
                        if ($json->get('title')) {
                            File::put("storage/themes/{$json->get('title')}.theme.tar.gz", file_get_contents(base_path("vendor/{$results['name']}/dist/{$json->get('title')}.theme.tar.gz")));
                            $model->title = $json->get('title');
                            $model->current_sha = $model->sha;

                            \Artisan::call("theme:install", ['package' => $json->get('title')]);
                        }                       
                    }
                } else {
                    throw new \Exception('Unable to find package.');
                }
            }
        });
    }
    
    private function installPackage($name){
        $process = Process::fromShellCommandline(sprintf(
            'cd %s && composer require %s',
            base_path(),
            $name,
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
