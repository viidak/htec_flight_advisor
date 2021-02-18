<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('cities', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('country');
                $table->text('description');
                $table->timestamps();
            });

            Schema::create('comments', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned(); 
                $table->index('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('city_id')->unsigned();
                $table->index('city_id');
                $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
                $table->text('description');
                $table->timestamps();
            });

            Schema::create('airports', function (Blueprint $table) {
                $table->integer('airport_id')->unsigned()->unique();
                $table->string('name');
                $table->string('city');
                $table->string('country');
                $table->char('iata', 3);
                $table->char('icao', 4);
                $table->decimal('latitude', 10, 6);
                $table->decimal('longitude', 11, 6);
                $table->integer('altitude');
                $table->integer('timezone')->nullable();
                $table->enum('DST', ['E', 'A', 'S', 'O', 'Z', 'N', 'U'])->nullable();
                $table->string('tz')->nullable();
                $table->string('type');
                $table->string('source');
                $table->timestamps();
            });

            Schema::create('routes', function (Blueprint $table) {
                $table->increments('route_id');
                $table->char('airline', 3);
                $table->integer('airline_id')->nullable();
                $table->char('source_airport', 4);
                $table->integer('source_airport_id')->unsigned();
                $table->index('source_airport_id');
                $table->foreign('source_airport_id')->references('airport_id')->on('airports')->onDelete('cascade');
                $table->char('destination_airport', 4);
                $table->integer('destination_airport_id')->unsigned();
                $table->index('destination_airport_id');
                $table->foreign('destination_airport_id')->references('airport_id')->on('airports')->onDelete('cascade');
                $table->char('codeshare', 1)->nullable();
                $table->integer('stops');
                $table->string('equipment');
                $table->decimal('price', 5, 2);
                $table->timestamps();
            });
        } catch (\Exception $e) {
            $e->getMessage();
            $this->down();
            return $e->getMessage();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_user_id_foreign');
            $table->dropIndex('comments_user_id_index');
            $table->dropColumn('user_id');
            $table->dropForeign('comments_city_id_foreign');
            $table->dropIndex('comments_city_id_index');
            $table->dropColumn('city_id');
        });
        Schema::table('routes', function (Blueprint $table) {
            $table->dropForeign('routes_source_airport_id_foreign');
            $table->dropIndex('routes_source_airport_id_index');
            $table->dropColumn('source_airport_id');
            $table->dropForeign('routes_destination_airport_id_foreign');
            $table->dropIndex('routes_destination_airport_id_index');
            $table->dropColumn('destination_airport_id');
        });
        
        
        Schema::dropIfExists('cities', 'airports', 'comments', 'routes');
    }
}
