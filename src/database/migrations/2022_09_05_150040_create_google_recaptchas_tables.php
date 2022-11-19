<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwillHttpBasicAuthsTables extends Migration
{
    public function up(): void
    {
        Schema::create('twill_ggl_captcha', function (Blueprint $table) {
            createDefaultTableFields($table);

            $table->string('site_key')->nullable();

            $table->string('private_key')->nullable();
        });

        Schema::create('twill_ggl_captcha_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'twill_ggl_captcha', 'twill_ggl_captcha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('twill_ggl_captcha_revisions');
        Schema::dropIfExists('twill_ggl_captcha');
    }
}
