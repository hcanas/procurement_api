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
        Schema::create('ppmps', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignIdFor(\App\Models\Wfp::class, 'wfp_id')->constrained();
            $table->foreignIdFor(\App\Models\Item::class, 'item_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->string('unit');
            $table->decimal('abc');
            $table->string('procurement_mode');
            $table->unsignedInteger('milestone_1')->nullable();
            $table->unsignedInteger('milestone_2')->nullable();
            $table->unsignedInteger('milestone_3')->nullable();
            $table->unsignedInteger('milestone_4')->nullable();
            $table->unsignedInteger('milestone_5')->nullable();
            $table->unsignedInteger('milestone_6')->nullable();
            $table->unsignedInteger('milestone_7')->nullable();
            $table->unsignedInteger('milestone_8')->nullable();
            $table->unsignedInteger('milestone_9')->nullable();
            $table->unsignedInteger('milestone_10')->nullable();
            $table->unsignedInteger('milestone_11')->nullable();
            $table->unsignedInteger('milestone_12')->nullable();
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
        Schema::dropIfExists('ppmps');
    }
};
