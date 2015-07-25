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
            $table->integer('image')->unsigned();
            $table->text('keywords');
            $table->softDeletes();
            $table->timestamps();
        });

        //Match Against will be used to actually do the matching at cron time.
        DB::statement('ALTER TABLE kilfedder_googlecalendar_calendar_images ADD FULLTEXT full(keywords)');
        DB::statement('ALTER TABLE kilfedder_googlecalendar_events ADD FULLTEXT full(summary, description)');
        DB::statement('ALTER TABLE kilfedder_googlecalendar_events ADD FULLTEXT full(summary)');
        DB::statement('ALTER TABLE kilfedder_googlecalendar_events ADD FULLTEXT full(description)');
    }

    public function down()
    {
        Schema::dropIfExists('kilfedder_googlecalendar_calendar_images');
    }

}
