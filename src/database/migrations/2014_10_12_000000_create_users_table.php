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
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('postal_code', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('building_name', 255)->nullable();
            $table->string('avatar_path', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps(); // create_at / update_at
    
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
