<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title', 200);
            $table->string('slug', 210);
            $table->string('detail', 2000);
            $table->string('material', 200);
            $table->string('thumbnail_url', 200);
            $table->string('picture_url_1', 200);
            $table->string('picture_url_2', 200)->nullable();
            $table->string('picture_url_3', 200)->nullable();
            $table->string('picture_url_4', 200)->nullable();
            $table->string('picture_url_5', 200)->nullable();
            $table->integer('price')->unsigned()->default(0);
            $table->integer('discount')->unsigned()->default(0);
            $table->integer('category')->unsigned()->default(0);
            $table->string('tokopedia_order_link', 200)->nullable();
            $table->string('shopee_order_link', 200)->nullable();
            $table->string('bukalapak_order_link', 200)->nullable();
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
        Schema::dropIfExists('products');
    }
}
