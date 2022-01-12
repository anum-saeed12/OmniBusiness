<?php

use App\Models\Setting;

if (!function_exists('fetchSetting'))
{
    function fetchSetting($setting_name)
    {
        $setting = Setting::where('setting', $setting_name)->first();
        if (!$setting) return false;
        return $setting->value;
    }
}

if (!function_exists('setSetting'))
{
    function setSetting($setting_name, $setting_value)
    {
        $setting_updated = Setting::where('setting', $setting_name)
            ->update(['value' => $setting_value]);
        return $setting_updated;
    }
}

if (!function_exists('newSetting'))
{
    function newSetting($setting_name, $setting_value)
    {
        $new_setting = new Setting();
        $new_setting->setting = $setting_name;
        $new_setting->value = $setting_value;
        $saved = $new_setting->save();

        if (!$saved) return false;

        return $new_setting;
    }
}
