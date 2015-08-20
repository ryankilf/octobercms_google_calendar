<?php namespace Kilfedder\GoogleCalendar\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use October\Rain\Database\Model;
use October\Rain\Database\Traits\Sluggable;

/**
 * Calendar Model
 */
class Calendar extends Model
{
    use SoftDeletes;
    use Sluggable;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'kilfedder_googlecalendar_calendars';

    protected $slugs = ['slug' => ['summary', 'description', 'etag']];
    protected $dates = ['synced_at', 'created_at', 'updated_at'];

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

    /**
     * Sets the "url" attribute with a URL to this object
     * @param string $pageName
     * @param Cms\Classes\Controller $controller
     */
    public function setUrl($pageName, $controller)
    {

        $params = [
            'calendarSlug' => $this->slug,
        ];

        $this->url = $controller->pageUrl($pageName, $params);
        return $this->url;
    }

}