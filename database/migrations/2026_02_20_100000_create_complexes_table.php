<?php
declare(strict_types=1);

use App\Enums\ComplexStatusEnum;
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
        Schema::create('complexes', function (Blueprint $table): void {
            $table->comment('Комплексы');

            $table->ulid('id')->primary();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address')->nullable();

            $table->string('status', 32)
                ->default(ComplexStatusEnum::PLANNING->value);

            $table->decimal('latitude', 18, 15)->nullable();
            $table->decimal('longitude', 19, 15)->nullable();

            $table->timestamps();

            $table->index('name');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complexes');
    }
};
