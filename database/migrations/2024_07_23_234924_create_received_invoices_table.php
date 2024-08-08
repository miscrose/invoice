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
        Schema::create('received_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('invoice_number');
            $table->foreignId('companyinfo_id')->constrained('companyinfos');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('received_invoices');
    }
};
