<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedBigInteger('schedule_id');
            $table->string('name', 100);
            $table->char('color', 6);
            $table->timestampTz('start_on', $precision=0);
            $table->timestampTz('end_on', $precision=0);
            $table->unsignedbigInteger('quote', $precision = 0);
            $table->unsignedbigInteger('period', $precision = 0);
            $table->tinyInteger('efficiency');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiences');
    }
}
