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
        Schema::create('devis_recus', function (Blueprint $table) {
            $table->id();
            $table->date('date');
       
       
            $table->string('devis_number');
            $table->foreignId('companyinfo_id')->constrained('companyinfos');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis_recus');
    }
};
