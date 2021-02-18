<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('last_name');
                $table->string('user_name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('type', ['administrator', 'regular'])->default('regular');
                $table->rememberToken();
                $table->timestamps();
            });

            $now = \Carbon\Carbon::now();

            $data = array(
                array(
                    'name' => 'admin',
                    'last_name' => 'user',
                    'user_name' => 'adminuser',
                    'email' => 'admin@user.com',
                    'email_verified_at' => $now,
                    'password' => Hash::make('admin'),
                    'type' => 'administrator',
                    'created_at' => $now,
                    'updated_at' => $now,
                ),
                array(
                    'name' => 'regular',
                    'last_name' => 'user',
                    'user_name' => 'regularduser',
                    'email' => 'regular@user.com',
                    'email_verified_at' => $now,
                    'password' => Hash::make('regular'),
                    'type' => 'regular',
                    'created_at' => $now,
                    'updated_at' => $now,
                )
            );

            DB::table('users')->insert($data);
        } catch (\Exception $e) {
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
        Schema::dropIfExists('users');
    }
}
