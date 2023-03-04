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
        Schema::create('valid_codes', function (Blueprint $table) {
            $table->id();
            // $table->integer('validcodeable_id');
            // $table->string('validcodable_type');
            $table->morphs('valid_codable');
            $table->string('valid_token');
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
        Schema::dropIfExists('valid_codes');
    }
};
