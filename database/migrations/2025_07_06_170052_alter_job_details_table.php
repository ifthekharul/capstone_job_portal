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
        Schema::table('job_details', function (Blueprint $table) {

            $table->integer('status')->default(1)->after('company_website');
            $table->integer('isFeatured')->default(0)->after('status');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_details', function (Blueprint $table) {

            $table->dropColumn('status');
            $table->dropColumn('isFeatured');
            
        });
    }
};
