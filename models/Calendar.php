<?php namespace Kilfedder\GoogleCalendar\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use October\Rain\Database\Model;

/**
 * Calendar Model
 */
class Calendar extends Model
{
    use SoftDeletes;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'kilfedder_googlecalendar_calendars';

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