<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_cardtemplate', function (Blueprint $table) {
            $table->string('border_image')->nullable();
            $table->string('border_style')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('db_cardtemplate', function (Blueprint $table) {
            $table->dropColumn('border_image');
            $table->dropColumn('border_style');
        });
    }
}
