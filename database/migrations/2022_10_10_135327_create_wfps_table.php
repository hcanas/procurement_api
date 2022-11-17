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
        Schema::create('wfps', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('function_type');
            $table->string('deliverables');
            $table->string('activities');
            $table->date('timeframe_from');
            $table->date('timeframe_to');
            $table->string('target_q1');
            $table->string('target_q2');
            $table->string('target_q3');
            $table->string('target_q4');
            $table->string('item');
            $table->decimal('cost', 15, 2);
            $table->foreignIdFor(\App\Models\FundSource::class, 'fund_source_id')->constrained();
            $table->unsignedBigInteger('responsible_person_id');
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
        Schema::dropIfExists('wfps');
    }
};
