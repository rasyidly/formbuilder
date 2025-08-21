<div class="space-y-6">
    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Email Preview</h3>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject:</label>
                <div class="bg-white dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700">
                    <p class="text-gray-900 dark:text-gray-100">{{ $subject }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Body:</label>
                <div class="bg-white dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700 max-h-96 overflow-y-auto">
                    <div class="prose dark:prose-invert max-w-none">
                        {!! $body !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($originalSubject !== $subject || $originalBody !== $body)
    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Original Template (with placeholders):</h4>

        <div class="space-y-3 text-sm">
            <div>
                <span class="font-medium text-blue-800 dark:text-blue-200">Subject:</span>
                <code class="ml-2 text-blue-700 dark:text-blue-300">{{ $originalSubject }}</code>
            </div>

            <div>
                <span class="font-medium text-blue-800 dark:text-blue-200">Body:</span>
                <div class="mt-1 p-2 bg-blue-100 dark:bg-blue-900/40 rounded text-blue-700 dark:text-blue-300 font-mono text-xs whitespace-pre-wrap">{{ strip_tags($originalBody) }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    <strong>Note:</strong> This preview uses sample data. Actual emails will contain real form submission values.
                </p>
            </div>
        </div>
    </div>
</div>