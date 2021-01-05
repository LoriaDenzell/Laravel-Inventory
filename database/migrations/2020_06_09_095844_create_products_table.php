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
        if(!Schema::hasTable('products')){
            Schema::create('products', function (Blueprint $table) {
                $table->bigIncrements('product_id');
                $table->string('product_code');
                $table->string('product_name');
                $table->string('product_type');
                $table->string('product_brand');
                $table->double('purchase_price', 15, 2);
                $table->double('product_selling_price', 15, 2);
                $table->text('product_information')->nullable();
                $table->integer('product_active');
                $table->string('product_image')->nullable();
                $table->integer('user_modified');
                $table->integer('stock_available')->default(0);
                $table->integer('stock_total')->default(0);
                $table->timestamps();
            });
        }
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
