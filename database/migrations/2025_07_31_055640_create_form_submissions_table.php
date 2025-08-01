<?php

use App\Enums\SubmissionStatus;
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
            $table->foreignIdFor(User::class, 'submitter_id')->nullable()->constrained()->nullOnDelete(); // User who submitted the form, if applicable
            $table->string('submitter_name')->nullable();
            $table->string('submitter_email')->nullable();
            $table->string('status')->default(SubmissionStatus::Pending->value);
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Additional metadata
            $table->string('submitter_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('submission_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FormField::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Submission::class)->constrained()->cascadeOnDelete();
            $table->string('field_label'); // Backup field label in case field is deleted
            $table->string('field_type'); // Field type at time of submission
            $table->longText('value')->nullable(); // Field value (can be large for file uploads)
            $table->json('files_metadata')->nullable(); // For file uploads: original name, path, size, etc.
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['form_field_id', 'submission_id']);
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
