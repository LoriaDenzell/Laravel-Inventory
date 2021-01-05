<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('vendors')){
            Schema::create('vendors', function (Blueprint $table) {
                $table->bigIncrements('vendor_id');
                $table->string('vendor_first_name');
                $table->string('vendor_last_name');
                $table->text('vendor_address');
                $table->string('vendor_contact');
                $table->integer('active')->comment('0 - Inactive, 1 - Active, 2 - Deleted'); //0 - Inactive, 1 - Active, 2 - Deleted
                $table->integer('vendor_modified');
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
        Schema::dropIfExists('vendors');
    }
}
