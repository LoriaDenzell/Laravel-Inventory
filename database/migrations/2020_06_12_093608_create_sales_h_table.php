<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesHTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('sales_h')){
            Schema::create('sales_h', function (Blueprint $table) {
                $table->id('id');
                $table->string('invoice_no');
                $table->date('date');
                $table->integer('total')->nullable();
                $table->integer('active');
                $table->integer('user_modified');
                $table->string('shop_name');
                $table->string('information')->nullable();
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
        Schema::dropIfExists('sales_h');
    }
}
