<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('client', function (Blueprint $table) {
            $table->uuid('signing_key_id')->nullable();

            $table->foreign('signing_key_id')->references('id')->on('signing_key');
        });

        $signingKey = \Illuminate\Support\Facades\DB::table('signing_key')->first();

        if ($signingKey) {
            \Illuminate\Support\Facades\DB::table('client')->update(['signing_key_id' => $signingKey->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client', function (Blueprint $table) {
            $table->dropForeign(['signing_key_id']);
            $table->dropColumn('signing_key_id');
        });
    }
};
