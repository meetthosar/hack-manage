<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hackthon_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hackathon_id');
            $table->unsignedBigInteger('developer_id');
            $table->auditableWithDeletes();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('hackathon_id')->references('id')->on('hackthons')->onDelete('restrict');
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hackthon_participants');
    }
};
