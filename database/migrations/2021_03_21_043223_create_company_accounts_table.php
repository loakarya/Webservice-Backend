<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name', 100);
            $table->string('description', 200)->nullable();
            $table->string('email', 200);
            $table->string('username', 200)->nullable();
            $table->string('password', 500);
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('intervention')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_accounts');
    }
}
