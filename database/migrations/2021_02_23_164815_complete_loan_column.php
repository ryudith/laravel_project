<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompleteLoanColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('name')->default('');
            $table->integer('is_member')->default('1');
            $table->integer('user_id')->default('0');
            $table->integer('status')->default('0');
            $table->decimal('nominal', 10, 2)->default('0.0');
            $table->string('letter_loan')->default('0.0');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('is_member');
            $table->dropColumn('user_id');
            $table->dropColumn('status');
            $table->dropColumn('nominal');
            $table->dropColumn('letter_loan');
            $table->dropSoftDeletes();
        });
    }
}
