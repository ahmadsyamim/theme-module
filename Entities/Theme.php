<?php

namespace Modules\Theme\Entities;

use Illuminate\Database\Eloquent\Model;
use Igaster\LaravelTheme\Facades\Theme as LaravelTheme;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


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
                        $responseGH = Http::get("https://raw.githubusercontent.com/{$results['name']}/master/composer.json")->collect();
                        if ($responseGH->count() && $responseGH->get('title')) {
                            $model->title = $responseGH->get('title');
                            File::put("storage/themes/{$responseGH->get('title')}.theme.tar.gz",file_get_contents("https://raw.githubusercontent.com/{$results['name']}/master/dist/{$responseGH->get('title')}.theme.tar.gz"));

                            // Get SHA
                            $responseGH = \Http::get("https://api.github.com/repos/{$model->url}/commits/master")->collect();
                            if ($responseGH->count() && $responseGH->get('sha')) {
                                $model->current_sha = $responseGH->get('sha');
                            }
                        }
                    }
                }
            }
        });

        static::saved(function ($model) {
        });

    }
    
}
