<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesDTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('sales_d')){
            Schema::create('sales_d', function (Blueprint $table) {
                $table->id('id');
                $table->integer('id_sales');
                $table->integer('id_product');
                $table->date('date_order');
                $table->integer('total');
                $table->double('price', 15, 2);
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
        Schema::dropIfExists('sales_d');
    }
}
