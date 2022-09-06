<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('url');
            $table->text('title');
            // $table->enum('title_type', ['user', 'system']);
            $table->integer('protocol_id');
            $table->integer('visits')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // I hate key constraints in a DB, but if we need 'em, here they are
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('protocol_id')->references('id')->on('protocols');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urls');
    }
};
