<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tiket_kerusakan', function (Blueprint $table) {
            $table->string('priority', 20)->default('medium')->after('status');
            $table->unsignedBigInteger('assigned_to')->nullable()->after('priority');
            $table->timestamp('target_selesai_at')->nullable()->after('assigned_to');
            $table->timestamp('closed_at')->nullable()->after('target_selesai_at');
            $table->text('admin_notes')->nullable()->after('closed_at');

            $table->index('priority');
            $table->index('assigned_to');
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('tiket_kerusakan_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('ticket_id');
            $table->index('user_id');
            $table->foreign('ticket_id')->references('id')->on('tiket_kerusakan')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_kerusakan_logs');

        Schema::table('tiket_kerusakan', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['assigned_to']);
            $table->dropColumn([
                'priority',
                'assigned_to',
                'target_selesai_at',
                'closed_at',
                'admin_notes',
            ]);
        });
    }
};
