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
        Schema::create('phase_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phase_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email', 300)->unique();
            $table->longText('description')->nullable();
            $table->auditableWithDeletes();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('phase_id')->references('id')->on('hackthon_phases')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phase_participants');
    }
};
