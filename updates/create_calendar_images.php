<?php namespace Kilfedder\GoogleCalendar\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCalendarImagesTable extends Migration
{

    public function up()
    {
        Schema::create('kilfedder_googlecalendar_calendar_images', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('keywords');
            $table->char('label', 255);
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE kilfedder_googlecalendar_calendar_images ADD FULLTEXT full(keywords)');


    }

    public function down()
    {
        Schema::dropIfExists('kilfedder_googlecalendar_calendar_images');
    }

}
