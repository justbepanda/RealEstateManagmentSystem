<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('premise_history', function (Blueprint $table) {
            $table->comment('История изменений помещений');

            $table->ulid('id')->primary();

            $table->foreignUlid('premise_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('type');

            $table->string('old_value')->nullable();
            $table->string('new_value');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premise_history');
    }
};
