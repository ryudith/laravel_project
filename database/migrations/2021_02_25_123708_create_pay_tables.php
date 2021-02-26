<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pays');
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lend_id')->nullable()->constrained('lends');
            $table->foreignId('user')->nullable()->constrained('users');
            $table->integer('status')->default('1');
            $table->decimal('nominal', 10, 2)->default('0.0');
            $table->string('pay_file')->default('');
            $table->text('note')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('pays');
    }
}
