<?php

use App\Models\Form;
use App\Models\FormField;
use App\Models\Submission;
use App\Models\User;
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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Form::class)->constrained()->cascadeOnDelete();
            $table->string('reference_number')->unique(); // Unique reference for each submission
            $table->json('data')->nullable(); // Complete form data as JSON
            $table->foreignIdFor(User::class, 'submitter_id')->nullable()->constrained()->nullOnDelete(); // User who submitted the form, if applicable
            $table->string('submitter_name')->nullable();
            $table->string('submitter_email')->nullable();
            $table->string('submitter_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, archived, spam
            $table->boolean('is_read')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->text('notes')->nullable(); // Admin notes
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();
        });

        Schema::create('submission_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FormField::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Submission::class)->constrained()->cascadeOnDelete();
            $table->string('field_name'); // Backup field name in case field is deleted
            $table->string('field_type'); // Field type at time of submission
            $table->longText('value')->nullable(); // Field value (can be large for file uploads)
            $table->json('files_metadata')->nullable(); // For file uploads: original name, path, size, etc.
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_values');
        Schema::dropIfExists('submissions');
    }
};
