<?php

use App\Models\Form;
use App\Models\FormField;
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
        Schema::create('form_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Form::class, 'form_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->json('recipients')->nullable();
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->foreignIdFor(FormField::class, 'field_key_id')->nullable(); // This is the field key ID which have email field (used for sending email to form submitter)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_notifications');
    }
};
