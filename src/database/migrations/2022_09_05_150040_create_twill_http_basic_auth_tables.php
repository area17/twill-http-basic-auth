<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwillHttpBasicAuthTables extends Migration
{
    public function up(): void
    {
        Schema::create('twill_basic_auth', function (Blueprint $table) {
            createDefaultTableFields($table);

            $table->string('domain')->nullable();

            $table->string('username')->nullable();

            $table->string('password')->nullable();

            $table->boolean('allow_laravel_login')->default(false);

            $table->boolean('allow_twill_login')->default(false);
        });

        Schema::create('twill_basic_auth_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'twill_basic_auth', 'twill_basic_auth');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('twill_basic_auth_revisions');
        Schema::dropIfExists('twill_basic_auth');
    }
}
