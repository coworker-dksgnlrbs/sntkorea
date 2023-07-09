<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQnasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qnas', function (Blueprint $table) {
            $table->id();
            $table->string("company")->nullable();
            $table->string("name")->nullable();
            $table->string("contact")->nullable();
            $table->string("email")->nullable();
            $table->string("type")->nullable();
            $table->string("service")->nullable();
            $table->string("budget")->nullable();
            $table->string("url")->nullable();
            $table->string("opened_at")->nullable();
            $table->string("memo")->nullable();
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
        Schema::dropIfExists('qnas');
    }
}
