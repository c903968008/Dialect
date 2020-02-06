<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('question_id');
            $table->bigInteger('dialect_id');
            $table->string('content')->comment('反馈内容');
            $table->string('translation')->comment('正确答案');
            $table->boolean('checked')->default(false)->comment('是否已查看');
            $table->boolean('accepted')->default(false)->comment('是否已接受该正确答案');
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
