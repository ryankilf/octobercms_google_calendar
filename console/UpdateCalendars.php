<?php namespace Kilfedder\GoogleCalendar\Console;

use Google_Service_Calendar_CalendarListEntry;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Kilfedder\GoogleCalendar\Models\Calendar;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCalendars extends UpdateBase
{
    protected $name = 'kilfeddercalendar:updatecalendars';
    protected $description = 'Updates Calendars (N.B. not the events in each calendar)';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $params = ['showDeleted' => true];
        $calendarList = $this->service->calendarList->listCalendarList($params);
        if (is_null($calendarList)) {
            $this->error('No calendars found! Exiting');
        }
        while (true) {
            foreach ($calendarList->getItems() as $calendarListEntry) {
                $this->processOneCalendar($calendarListEntry);
            }
            $pageToken = $calendarList->getNextPageToken();
            if ($pageToken) {
                $params['pageToken'] = $pageToken;
                $calendarList = $this->service->calendarList->listCalendarList($params);
            } else {
                break;
            }
        }
        $this->info('all done');
    }

    private function processOneCalendar(Google_Service_Calendar_CalendarListEntry $calendarListEntry)
    {
        $this->info('processing this calendar:: ' . $calendarListEntry->getId() . ' ' . $calendarListEntry->getSummary());
        //if calendar is deleted, softDelete else just save the
        try {
            $calendar = Calendar::where('calendar_id', $calendarListEntry->getId())->firstOrFail();
            $this->comment('Calendar found');
        } catch (ModelNotFoundException $e) {
            if ($calendarListEntry->getDeleted()) {

                $this->comment('Calendar doesn\`t exist anyway' . print_r($calendarListEntry->getDeleted(), true));
                return;
            }
            $this->comment('New Calendar!');
            $calendar = new Calendar();
        }

        if ($calendarListEntry->getDeleted()) {
            $this->comment('Deleting Calendar');
            $calendar->delete();
            return;
        }

        $calendar->foreground_colour = (string) $calendarListEntry->getForegroundColor();
        $calendar->background_colour = (string) $calendarListEntry->getBackgroundColor();
        $calendar->etag = $calendarListEntry->getEtag();
        $calendar->calendar_id = $calendarListEntry->getId();
        $calendar->summary = (string) $calendarListEntry->getSummary();
        $calendar->description = (string) $calendarListEntry->getDescription();
        $calendar->save();
        $this->comment('Calendar Saved');
    }
}