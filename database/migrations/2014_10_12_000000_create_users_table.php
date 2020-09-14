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
            $table->increments('id');
            $table->string('uid', 30)->nullable();
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('email');
            $table->string('email2')->nullable();
            $table->string('password')->nullable();
            $table->string('account_type')->nullable();
            $table->string('home_school')->nullable();
            $table->string('pc_principal')->default('no');
            $table->boolean('isApprover')->default(false);
            $table->string('objectguid')->nullable();
            $table->text('groups')->nullable();
            $table->text('registeredFor')->nullable();
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
