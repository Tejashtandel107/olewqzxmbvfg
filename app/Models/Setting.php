<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Helper;

class Setting extends Model
{
    use HasFactory;
    
    protected $fillable = ['option_name', 'option_value'];
    
    public function saveSetting($key, $value){
        Setting::updateOrCreate(['option_name' => $key], ['option_value' => $value]);
    }
    public function saveSettings($settings=[]){
        if(empty($settings))
            return;

        foreach($settings as $key=>$value){
            $this->saveSetting($key,$value);
        }
        Cache::forget('settings');
        Helper::getSettings();// Refresh the cache
    }
}
