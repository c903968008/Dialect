<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDialectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('dialect_id')->references('id')->on('dialects');
            $table->string('audio')->comment('音频地址');
            $table->string('recognition')->comment('音频识别');
            $table->string('translation')->comment('正确翻译');
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
        Schema::dropIfExists('dialects');
    }
}
