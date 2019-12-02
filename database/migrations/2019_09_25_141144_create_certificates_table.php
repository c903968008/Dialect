<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('district_id')->comment('所属地区');
            $table->string('name')->comment('证书名称');
            $table->string('image')->default('certificate/default.png')->comment('图片');
            $table->integer('rank')->unsigned()->comment('级别');
            $table->text('description')->comment('证书描述');
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
        Schema::dropIfExists('certificates');
    }
}
