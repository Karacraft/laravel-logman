<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventLoggerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_logger', function (Blueprint $table) {
            $table->id();                           //  Id of Log
            $table->text('action');                           //  CRUD By User
            $table->text('table');                       //  Table acted upon
            $table->text('rowid');
            $table->text('description');                       //  Row Data before change
            $table->text('original');                       //  Row Data after change
            $table->text('changes');                       //  Row Data after change
            $table->smallInteger('user_id');                         //  User
            $table->text('user_name');                         //  User
            $table->text('ipaddress');                         //  User
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
        Schema::dropIfExists('event_logger');
    }
}
