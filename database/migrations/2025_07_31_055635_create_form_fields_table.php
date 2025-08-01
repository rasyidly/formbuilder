<?php

use App\Models\Form;
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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Form::class)->constrained()->onDelete('cascade');
            $table->string('name'); // Field identifier/name
            $table->integer('sequence')->default(0);
            $table->string('label'); // Display label
            $table->string('type'); // text, textarea, email, number, select, checkbox, radio, file, etc.
            $table->text('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('options')->nullable(); // For select, radio, checkbox options
            $table->json('validation_rules')->nullable(); // Validation rules
            $table->json('conditional_logic')->nullable(); // Show/hide conditions
            $table->json('settings')->nullable(); // Additional field settings
            $table->boolean('is_required')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['form_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
