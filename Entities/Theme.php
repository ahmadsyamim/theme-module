<?php

namespace Modules\Theme\Entities;

use Illuminate\Database\Eloquent\Model;
use Igaster\LaravelTheme\Facades\Theme as LaravelTheme;



class Theme extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'path'
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct();
        
        // $this->updateList();
    }

    private function updateList()
    {
        
        dd(LaravelTheme::all());
        //dd((new \Igaster\LaravelTheme\Themes)->all());
        dd('test');
    }
    
}
