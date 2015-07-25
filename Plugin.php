<?php namespace Kilfedder\GoogleCalendar;

use System\Classes\PluginBase;

/**
 * GoogleCalendar Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'GoogleCalendar',
            'description' => 'Get events from selected google calendars',
            'author' => 'Kilfedder',
            'icon' => 'icon-leaf'
        ];
    }


    /**
     * Registers scheduled tasks that are executed on a regular basis.
     */
    public function registerSchedule($schedule)
    {
        parent::registerSchedule($schedule);

        $schedule->command('kilfeddercalendar:updatecalendars')->daily();
        $schedule->command('kilfeddercalendar:updateevents')->hourly();
    }

    /*
     * These two commands should also be run when the plugin is first configured...
     * They sync the events and the calendars to a local database
     */
    public function register()
    {
        parent::register();
        $this->registerConsoleCommand('kilfeddercalendar.updateCalendars', 'Kilfedder\GoogleCalendar\Console\UpdateCalendars');
        $this->registerConsoleCommand('kilfeddercalendar.updateEvents', 'Kilfedder\GoogleCalendar\Console\UpdateEvents');
    }


    public function registerSettings()
    {
        return [
            'config' => [
                'label' => 'Google Calendar',
                'icon' => 'icon-calendar',
                'description' => 'Configure Google Calendar API options.',
                'class' => 'Kilfedder\GoogleCalendar\Models\Settings',
                'order' => 600
            ]
        ];
    }

}
