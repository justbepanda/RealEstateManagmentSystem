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
        Schema::create('premises', function (Blueprint $table) {
            $table->comment('Помещения (квартиры, офисы)');

            $table->ulid('id')->primary();

            $table->foreignUlid('floor_id')->constrained()->cascadeOnDelete();

            $table->string('number');
            $table->string('type')->nullable();
            $table->string('status')->nullable();

            $table->integer('rooms')->default(1);

            $table->decimal('area_total', 8, 2)->nullable();
            $table->decimal('area_living', 8, 2)->nullable();
            $table->decimal('area_kitchen', 8, 2)->nullable();

            $table->unsignedBigInteger('price_base')->nullable();
            $table->unsignedBigInteger('price_discount')->nullable();
            $table->unsignedBigInteger('price_per_m2')->nullable();

            $table->jsonb('features')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premises');
    }
};
