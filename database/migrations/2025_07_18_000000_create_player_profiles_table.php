<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('player_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('age')->nullable();
            $table->string('positions')->nullable();
            $table->string('preferred_foot')->nullable();
            $table->string('current_clubs')->nullable();
            $table->string('previous_clubs')->nullable();
            $table->string('leagues_seasons')->nullable();
            $table->text('stats')->nullable();
            $table->text('achievements')->nullable();
            $table->text('about')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('player_profiles');
    }
}
