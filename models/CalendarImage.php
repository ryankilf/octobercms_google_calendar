<?php namespace Kilfedder\GoogleCalendar\Models;

use Model;

/**
 * CalendarImage Model
 */
class CalendarImage extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'kilfedder_googlecalendar_calendar_images';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = ['image' => ['System\Models\File']];
    public $attachMany = [];

}