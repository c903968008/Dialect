<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreign('dialect_id')->references('id')->on('dialects');
            $table->string('content')->comment('反馈内容');
            $table->string('translation')->comment('正确答案');
            $table->boolean('checked')->comment('是否已查看');
            $table->boolean('accepted')->comment('是否已接受该正确答案');
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
        Schema::dropIfExists('feedbacks');
    }
}
