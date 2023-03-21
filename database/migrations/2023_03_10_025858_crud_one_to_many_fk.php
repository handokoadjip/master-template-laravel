<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudOneToManyFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crud_one_to_many_fk', function (Blueprint $table) {
            $table->uuid('crud_one_to_many_fk_id')->primary();
            $table->uuid('crud_one_to_many_fk_crud_one_to_many_pk_id');
            $table->string('crud_one_to_many_fk_telp');
            $table->timestamp('crud_one_to_many_fk_dibuat_pada', 0)->nullable();
            $table->timestamp('crud_one_to_many_fk_diubah_pada', 0)->nullable();

            $table->foreign('crud_one_to_many_fk_crud_one_to_many_pk_id')->references('crud_one_to_many_pk_id')
                ->on('crud_one_to_many_pk')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crud_one_to_many_fk');
    }
}
