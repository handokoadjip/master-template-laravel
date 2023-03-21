<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Crud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crud', function (Blueprint $table) {
            $table->uuid('crud_id')->primary();
            $table->string('crud_nama');
            $table->string('crud_foto')->default('default.jpg');
            $table->timestamp('crud_dibuat_pada', 0)->nullable();
            $table->timestamp('crud_diubah_pada', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crud');
    }
}
