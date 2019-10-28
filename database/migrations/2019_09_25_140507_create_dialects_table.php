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
            $table->bigInteger('user_id')->comment('发布者');
            $table->bigInteger('district_id')->comment('所属地区');
            $table->string('audio')->comment('音频地址');
            $table->string('recognition')->comment('音频识别');
            $table->string('translation')->comment('正确翻译');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('0:未审核,1:审核未通过,2:审核通过');
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
