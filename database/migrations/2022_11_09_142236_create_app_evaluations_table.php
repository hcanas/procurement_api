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
        Schema::create('app_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\App::class, 'app_id')->constrained();
            $table->string('evaluation');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('evaluated_by_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_evaluations');
    }
};
