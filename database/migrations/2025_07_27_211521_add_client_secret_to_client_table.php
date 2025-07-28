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
        Schema::rename('authorized_host', 'client');
        Schema::table('client', function (Blueprint $table) {
            $table->string('name')->after('id')->unique();
            $table->string('client_secret')->after('name');
            $table->renameColumn('hostname', 'redirect_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('client', 'authorized_host');
        Schema::table('authorized_host', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('client_secret');
            $table->renameColumn('redirect_url', 'hostname');
        });
    }
};
