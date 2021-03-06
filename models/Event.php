<?php namespace Kilfedder\GoogleCalendar\Models;

use Model;
use October\Rain\Database\Traits\Sluggable;

/**
 * Event Model
 */
class Event extends Model
{
    use Sluggable;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'kilfedder_googlecalendar_events';

    protected $slugs = [ 'slug' => ['summary', 'summary.start_date', 'start_date']];
    protected $dates = [ 'start_date', 'end_date', 'created_at', 'updated_at'];

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
    public $attachOne = [];
    public $attachMany = [];



}