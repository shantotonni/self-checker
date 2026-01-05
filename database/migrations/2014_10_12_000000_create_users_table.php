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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('username')->unique();
            $table->unsignedBigInteger('role_id');
            $table->string('designation');
            $table->dateTime('Mobile')->nullable();
            $table->string('email');
            $table->string('password');

            $table->string('is_active')->default(1);
            $table->string('device_token')->nullable();
            $table->string('EntryDevice')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function($table) {
            //if 'users'  table  exists
            if(Schema::hasTable('roles'))
            {
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');;
            }
        });
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
