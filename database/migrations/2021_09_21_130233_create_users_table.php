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
            $table->id();

            $table->string("name")->nullable(); // 이름
            $table->string("contact")->nullable(); // 연락처
            $table->string("order_contact")->nullable(); // 주문자 연락처
            $table->string("order_name")->nullable(); // 주문자 이름
            $table->string("order_message")->nullable(); // 주문시 남기고 싶은말
            $table->integer("point")->default(0); // 적립금

            $table->timestamp('verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string("social_id")->nullable();
            $table->string("social_platform")->nullable();
            $table->unique(["social_id", "social_platform"]);

            $table->boolean("agree_ad")->default(false);

            $table->text("reason_leave_out")->nullable(); // 탈퇴사유

            $table->string("account")->nullable(); // 환불계좌 계좌번호
            $table->string("bank")->nullable(); // 환불계좌 은행명
            $table->string("owner")->nullable(); // 환불계좌 예금주

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
