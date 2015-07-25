<?php namespace Kilfedder\GoogleCalendar\Models;

use October\Rain\Database\Model;

/**
 * Google Analytics settings model
 *
 * @package system
 * @author Alexey Bobkov, Samuel Georges
 *
 */
class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'kilfedder_googlecalendar_settings';

    public $settingsFields = 'fields.yaml';

    public $attachOne = [
        'gapi_key' => ['System\Models\File', 'public' => false]
    ];

    /**
     * Validation rules
     */
    public $rules = [
        'project_name' => 'required',
        'client_id' => 'required',
        'app_email' => 'required|email',
        'years_back' => 'required|numeric',
        'years_forward' => 'required|numeric',

    ];
}