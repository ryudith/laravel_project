<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteLoansCreateLendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::dropIfExists('loans');
        Schema::create('lends', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
            $table->integer('is_member')->default('0');
            $table->integer('user')->default('0');
            $table->integer('status')->default('1');
            $table->decimal('nominal', 10, 2)->default('0.0');
            $table->string('lend_file')->default('');
            $table->text('description');
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
        schema::dropIfExists('lends');
    }
}
