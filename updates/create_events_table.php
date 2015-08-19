<?php namespace Kilfedder\GoogleCalendar\Updates;

use Illuminate\Support\Facades\DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateEventsTable extends Migration
{

    public function up()
    {
        Schema::create('kilfedder_googlecalendar_events', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('calendar')->unsigned();
            $table->char('event_id', 255)->unique();
            $table->char('ical_uid', 255);
            $table->char('summary', 255);
            $table->text('description');
            $table->text('location');
            $table->boolean('all_day_event')->default(false);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->char('slug', 255);
            $table->timestamps();
        });

        //Match Against will be used to actually do the matching at cron time.
        DB::statement('ALTER TABLE kilfedder_googlecalendar_events ADD FULLTEXT summary_description(summary, description)');
        DB::statement('ALTER TABLE kilfedder_googlecalendar_events ADD FULLTEXT summary(summary)');
        DB::statement('ALTER TABLE kilfedder_googlecalendar_events ADD FULLTEXT description(description)');
    }

    public function down()
    {
        Schema::dropIfExists('kilfedder_googlecalendar_events');
    }

}
