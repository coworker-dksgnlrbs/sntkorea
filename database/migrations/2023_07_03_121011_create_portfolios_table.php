<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string("category")->nullable();
            $table->string("company")->nullable(); // 회사명
            $table->string("subtitle")->nullable(); // 부제
            $table->string("title")->nullable(); // 프로젝트명
            $table->text("description")->nullable(); // 프로젝트 설명
            $table->string("url")->nullable();
            $table->date("started_at")->nullable();
            $table->date("finished_at")->nullable();
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
        Schema::dropIfExists('portfolios');
    }
}
