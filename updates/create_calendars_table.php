<?php namespace Kilfedder\GoogleCalendar\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCalendarsTable extends Migration
{

    public function up()
    {
        Schema::create('kilfedder_googlecalendar_calendars', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('enabled')->default(true);
            $table->char('foreground_colour', 40);
            $table->char('background_colour', 40);
            $table->char('etag', 255);
            $table->char('calendar_id', 255);
            $table->text('summary', 512);
            $table->char('description', 255);
            $table->dateTime('synced_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kilfedder_googlecalendar_calendars');
    }

}
