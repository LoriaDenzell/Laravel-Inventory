<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('cms')){
            Schema::create('cms', function (Blueprint $table) {
                $table->id();
                $table->string('org_name');
                $table->string('org_contact');
                $table->string('org_email');
                $table->string('org_address');
                $table->integer('high_pct');
                $table->integer('ave_pct');
                $table->integer('low_pct');
                $table->integer('tax_pct');
                $table->integer('max_activities');
                $table->integer('user_modified');
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
        Schema::dropIfExists('cms');
    }
}
