<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('stock')){
            Schema::create('stock', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('id_product');
                $table->text('information')->nullable();
                $table->integer('total')->nullable();
                $table->enum('type', ['buy', 'sell', 'correction'])->nullable();
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
        Schema::dropIfExists('stock');
    }
}
