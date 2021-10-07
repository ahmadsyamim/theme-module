<?php

namespace Modules\Theme\Entities;

use Illuminate\Database\Eloquent\Model;
use Igaster\LaravelTheme\Facades\Theme as LaravelTheme;



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

        static::saving(function ($model) {
            if (preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $model->url)) {

            } else {
                // Not URL
                $response = Http::get("https://packagist.org/search.json?q={$model->url}")->collect();
                if ($response->get('total')) {
                    $results = Collect($response->get('results'))->first();
                    if ($results['name']) {
                        $responseGH = Http::get("https://raw.githubusercontent.com/{$results['name']}/master/module.json")->collect();
                        if ($responseGH->count()) {
                            // $model->title = $responseGH->get('name');
                        }
                    }
                }
            }
            // $model->slug = strtolower($model->title);
        });

        static::saved(function ($model) {
        });

    }
    
}
