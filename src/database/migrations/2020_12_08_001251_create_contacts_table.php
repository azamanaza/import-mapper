<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('team_id');
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->integer('sticky_phone_number_id')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE contacts AUTO_INCREMENT = 256;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
