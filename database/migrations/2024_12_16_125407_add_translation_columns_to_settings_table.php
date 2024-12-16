<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTranslationColumnsToSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('terms_and_condition_en')->nullable();
            $table->text('about_us_en')->nullable();
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'terms_and_condition_en',
                'about_us_en',
            ]);
        });
    }
}
