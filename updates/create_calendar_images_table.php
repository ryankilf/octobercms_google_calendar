<?php namespace Kilfedder\GoogleCalendar\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCalendarImagesTable extends Migration
{

    public function up()
    {
        Schema::create('kilfedder_googlecalendar_calendar_images', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kilfedder_googlecalendar_calendar_images');
    }

}
