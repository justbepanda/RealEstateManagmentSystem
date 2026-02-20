<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('floors', function (Blueprint $table) {
            $table->comment('Этажи');

            $table->ulid('id')->primary();
            $table->foreignUlid('building_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUlid('section_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('number');
            $table->integer('premises_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
};
