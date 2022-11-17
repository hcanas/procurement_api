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
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignIdFor(\App\Models\Ppmp::class, 'ppmp_id')->constrained();
            $table->string('end_user');
            $table->unsignedTinyInteger('early_procurement');
            $table->string('procurement_mode');
            $table->date('advertisement_date');
            $table->date('opening_date');
            $table->date('noa_date');
            $table->date('signing_date');
            $table->string('status');
            $table->unsignedBigInteger('last_modified_by_id');
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
        Schema::dropIfExists('apps');
    }
};
