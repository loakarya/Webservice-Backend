<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('acl')->unsigned()->default(0);
            $table->bigInteger('employee_code')->unique();
            $table->string('private_email', 100)->unique();
            $table->string('company_email_password', 500);
            $table->bigInteger('bank_account_number');
            $table->string('bank_account_provider', 100);
            $table->string('status', 50);
            $table->bigInteger('phone');
            $table->string('role', 100);
            $table->string('level', 50);
            $table->string('chapter', 100);
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
        Schema::dropIfExists('employees');
    }
}
