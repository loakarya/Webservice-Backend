<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title', 100);
            $table->string('subtitle', 100);
            $table->string('slug', 100);
            $table->string('thumbnail_url', 200);
            $table->integer('category')->unsigned()->default(0);
            $table->longtext('content');
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
        Schema::dropIfExists('articles');
    }
}
