<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('menu_item_name',255);
            $table->longText('menu_item_desc')->nullable();
            $table->string('menu_item_image')->nullable();
            $table->float('menu_item_price');
            $table->integer('rating')->nullable();
            $table->integer('sort_order');
            $table->string('categories_id')->nullable();
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
        Schema::dropIfExists('food_menu_items');
    }
}
