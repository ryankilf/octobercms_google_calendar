<?php namespace Kilfedder\GoogleCalendar\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCalendarImageLabelField extends Migration
{

    public function up()
    {
        Schema::table('kilfedder_googlecalendar_calendars', function ($table) {
            $table->char('slug', 255);
        });

        Schema::table('kilfedder_googlecalendar_events', function ($table) {
            $table->char('slug', 255);
        });
    }


}