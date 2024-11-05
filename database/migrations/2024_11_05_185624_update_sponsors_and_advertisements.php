<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSponsorsAndAdvertisements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update sponsors table
        Schema::table('sponsors', function (Blueprint $table) {
            // Check if 'sort' column exists and drop it if it does
            if (Schema::hasColumn('sponsors', 'sort')) {
                $table->dropColumn('sort');
            }
            // Check if 'sort_order' column exists and add it if it does not
            if (!Schema::hasColumn('sponsors', 'sort_order')) {
                $table->integer('sort_order')->nullable()->after('id'); // Adjust 'after' as needed
            }
        });

        // Update advertisements table
        Schema::table('advertisements', function (Blueprint $table) {
            // Check if 'sort' column exists and drop it if it does
            if (Schema::hasColumn('advertisements', 'sort')) {
                $table->dropColumn('sort');
            }
            // Check if 'sort_order' column exists and add it if it does not
            if (!Schema::hasColumn('advertisements', 'sort_order')) {
                $table->integer('sort_order')->nullable()->after('id'); // Adjust 'after' as needed
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverse changes for sponsors table
        Schema::table('sponsors', function (Blueprint $table) {
            // Add 'sort' column back if it doesn't exist
            if (!Schema::hasColumn('sponsors', 'sort')) {
                $table->integer('sort')->nullable()->after('id'); // Adjust 'after' as needed
            }
            // Drop 'sort_order' column if it exists
            if (Schema::hasColumn('sponsors', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });

        // Reverse changes for advertisements table
        Schema::table('advertisements', function (Blueprint $table) {
            // Add 'sort' column back if it doesn't exist
            if (!Schema::hasColumn('advertisements', 'sort')) {
                $table->integer('sort')->nullable()->after('id'); // Adjust 'after' as needed
            }
            // Drop 'sort_order' column if it exists
            if (Schema::hasColumn('advertisements', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
}
