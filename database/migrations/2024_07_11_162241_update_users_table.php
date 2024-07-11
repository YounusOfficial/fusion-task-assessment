<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('role', ['Admin', 'Supervisor', 'Agent'])->default('Agent');
            $table->decimal('location_latitude', 10, 7)->nullable();
            $table->decimal('location_longitude', 10, 7)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('timezone')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'role', 'location_latitude', 'location_longitude', 'date_of_birth', 'timezone']);
        });
    }
}
