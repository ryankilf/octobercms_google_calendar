<?php namespace Kilfedder\GoogleCalendar\Console;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Kilfedder\GoogleCalendar\Models\Calendar;
use Kilfedder\GoogleCalendar\Models\Event;

class UpdateEvents extends UpdateBase
{
    protected $name = 'kilfeddercalendar:updateevents';
    protected $description = 'Updates Calendars (N.B. not the events in each calendar)';
    const DEFAULT_YEARS_FORWARD = 5;
    const DEFAULT_YEARS_BACK = 5;


    public function __construct()
    {

        parent::__construct();
    }

    public function fire()
    {


        $years_forward = $this->settings->years_forward;
        if (!is_numeric($years_forward) || $years_forward <= 0) {
            $years_forward = self::DEFAULT_YEARS_FORWARD;
        }
        $years_back = $this->settings->years_back;
        if (!is_numeric($years_back) || $years_back <= 0) {
            $years_back = self::DEFAULT_YEARS_BACK;
        }

        $timeMax = new \DateTime('+' . $years_forward . ' years');
        $timeMin = new \DateTime('-' . $years_back . ' years');

        if (boolval($this->settings->delete_old_events)) {
            $this->info('N.B. At the end of this script, all events with a start date before ' . $timeMin->format('Y-m-d H:i:s') . ' will be HARD deleted');
            $this->comment('They will still exist on your google calendar, but they will not be on the website any more');
            $this->comment('If that\'s not something you want, press Ctrl+C *now*');
        }

        //Order by rand sometimes in case we get a crash in one calendar somehow..
        if (rand(1, 8) == 2) {
            $calendars = Calendar::all()->random()->get();
        } else {
            $calendars = Calendar::orderBy('last_sync_date', 'ASC')->get();
        }

        foreach ($calendars as $calendar) {
            $this->info('processing ' . $calendar->calendar_id);
            $this->updateEventsForCalendar($calendar, $timeMin, $timeMax);
            $calendar->last_sync_date = new \DateTime('now');
            $calendar->save();
        }

        if (boolval($this->settings->delete_old_events)) {
            $this->info('Deleting events before ' . $timeMin->format('Y-m-d H:i:s'));
            Event::where('start_date', '<', $timeMin)->delete();
        }
    }

    private function updateEventsForCalendar(Calendar $calendar, \DateTime $timeMin, \DateTime $timeMax)
    {

        $params = [
            'singleEvents' => true,
            'showDeleted' => true,
            'timeMax' => $timeMax->format(\DateTime::RFC3339),
            'timeMin' => $timeMin->format(\DateTime::RFC3339),
        ];

        $this->info('Making first request with calendar');
        $eventList = $this->service->events->listEvents($calendar->calendar_id, $params);
        while (true) {
            foreach ($eventList->getItems() as $event) {
                $this->processOneEvent($event, $calendar);
            }
            $pageToken = $eventList->getNextPageToken();
            if (!$pageToken) {
                break;
            }
            $this->info('Making request with refresh token');
            $params['pageToken'] = $pageToken;
            $eventList = $this->service->events->listEvents($calendar->calendar_id, $params);
        }

    }

    private function processOneEvent(\Google_Service_Calendar_Event $googleServiceCalendarEvent, Calendar $calendar)
    {
        $allDay = false;
        if ($googleServiceCalendarEvent->getStart()->getDateTime()) {
            $startTime = new \DateTime($googleServiceCalendarEvent->getStart()->getDateTime());
            $endTime = new \DateTime($googleServiceCalendarEvent->getEnd()->getDateTime());

        } else {
            $startTime = new \DateTime($googleServiceCalendarEvent->getStart()->getDate());
            $endTime = new \DateTime($googleServiceCalendarEvent->getEnd()->getDate());
            $allDay = true;
        }


        $uid = $googleServiceCalendarEvent->getId();


        $this->comment('Event found - ' . $googleServiceCalendarEvent->getSummary() . ' - on ' . $startTime->format('Y-m-d H:i') . ' - ' . $googleServiceCalendarEvent->getId());
        try {
            $event = Event::where('event_id', $googleServiceCalendarEvent->getId())->firstOrFail();

        } catch (ModelNotFoundException $e) {
            if ($googleServiceCalendarEvent->getStatus() == 'cancelled') {
                return;
            }
            $this->comment('New Event!');
            $event = new Event();
        }

        if ($googleServiceCalendarEvent->getStatus() == 'cancelled') {
            $event->delete();
            return;
        }

        $event->calendar = $calendar->id;
        $event->event_id = $uid;
        $event->ical_uid = $googleServiceCalendarEvent->getICalUID();

        $event->start_date = $startTime;
        $event->all_day_event = $allDay;
        $event->end_date = $endTime;
        $event->summary = (string)$googleServiceCalendarEvent->getSummary();
        $event->description = (string)$googleServiceCalendarEvent->getDescription();
        $event->location = (string)$googleServiceCalendarEvent->getLocation();
        $event->save();
    }
}