<?php namespace Kilfedder\Googlecalendar\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Kilfedder\Googlecalendar\Models\Calendar;

class Calendars extends ComponentBase {

    /**
     * @Calendar
     */
    public $calendars;
    public $calendarsPage;


    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name'        => 'kilfedder.googlecalendar::lang.settings.calendars_component_title',
            'description' => 'kilfedder.googlecalendar::lang.settings.calendars_component_description'
        ];
    }


    public function defineProperties()
    {
        return [

            'calendarsPage' => [
                'title'       => 'kilfedder.googlecalendar::lang.settings.calendars_page',
                'description' => 'kilfedder.googlecalendar::lang.settings.calendars_page_description',
                'type'        => 'dropdown',
                'default'     => 'calendar/calendars',
                'group'       => 'Links',
            ]
        ];
    }

    public function getCalendarsPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->calendars = $this->page['calendars'] = $this->loadCalendars();
        $this->calendarsPage = $this->page['calendarsPage'] = $this->property('calendarsPage');
    }

    public function loadCalendars()
    {

        $calendars = Calendar::all();
        foreach($calendars as $calendar) {
            $url = $calendar->setUrl($this->property('calendarsPage'), $this->controller);
        }
        return $calendars;
    }

}