<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('出题人');
            $table->bigInteger('dialect_id');
            $table->text('wrong')->comment('三个错误答案');
            $table->integer('answer_right')->default(0)->comment('答题正确的人数');
            $table->integer('answer_total')->default(0)->comment('答题总人数');
            $table->integer('like')->default(0)->comment('点赞数');
            $table->integer('difficulty')->comment('难度');
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
        Schema::dropIfExists('questions');
    }
}
