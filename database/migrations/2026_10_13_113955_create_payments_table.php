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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Nullable so deleting a membership does not delete its payment history.
            $table->foreignId('membership_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_no')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['cash', 'card', 'gcash', 'bank_transfer']);
            $table->enum('status', ['pending', 'paid', 'refunded', 'overdue'])->default('pending')->index();
            $table->timestamp('paid_at')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
