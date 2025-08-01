<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;

class FormWithBlockFieldsSeeder extends Seeder
{
    public function run(): void
    {
        // Create a form
        $form = Form::updateOrCreate([
            'slug' => 'sample-form-with-all-block-fields',
        ], [
            'name' => 'Sample Form with All Block Fields',
            'description' => 'A form containing all block field types.',
            'published_at' => now(),
        ]);

        // Prepare all fields
        $fields = [
            [
                'type' => 'text',
                'label' => 'Name',
                'is_required' => true,
                'placeholder' => 'Enter your name',
            ],
            [
                'type' => 'textarea',
                'label' => 'Bio',
                'is_required' => false,
                'placeholder' => 'Tell us about yourself',
            ],
            [
                'type' => 'email',
                'label' => 'Email',
                'is_required' => true,
                'placeholder' => 'Enter your email address',
            ],
            [
                'type' => 'number',
                'label' => 'Age',
                'is_required' => false,
                'placeholder' => 'Enter your age',
            ],
            [
                'type' => 'select',
                'label' => 'Country',
                'is_required' => true,
                'options' => [
                    ['value' => 'usa', 'label' => 'USA'],
                    ['value' => 'canada', 'label' => 'Canada'],
                    ['value' => 'uk', 'label' => 'UK'],
                    ['value' => 'other', 'label' => 'Other'],
                ],
            ],
            [
                'type' => 'radio',
                'label' => 'Gender',
                'is_required' => false,
                'options' => [
                    ['value' => 'male', 'label' => 'Male'],
                    ['value' => 'female', 'label' => 'Female'],
                    ['value' => 'other', 'label' => 'Other'],
                ],
            ],
            [
                'type' => 'checkbox',
                'label' => 'Accept Terms',
                'is_required' => true,
            ],
            [
                'type' => 'checkbox_list',
                'label' => 'Interests',
                'is_required' => false,
                'options' => [
                    ['value' => 'sports', 'label' => 'Sports'],
                    ['value' => 'music', 'label' => 'Music'],
                    ['value' => 'travel', 'label' => 'Travel'],
                    ['value' => 'reading', 'label' => 'Reading'],
                ],
            ],
            [
                'type' => 'file',
                'label' => 'Resume',
                'is_required' => false,
            ],
            [
                'type' => 'date', // Not in enum, but keeping for completeness. Consider removing if not supported.
                'label' => 'Date of Birth',
                'is_required' => false,
            ],
            [
                'type' => 'time', // Not in enum, but keeping for completeness. Consider removing if not supported.
                'label' => 'Preferred Contact Time',
                'is_required' => false,
            ],
            [
                'type' => 'hidden',
                'label' => 'Internal Reference',
                'is_required' => false,
                'settings' => ['default_value' => 'ref123'],
            ],
        ];

        foreach ($fields as $index => $field) {
            $fields[$index]['sequence'] = $index + 1;
        }

        $form->fields()->createMany($fields);
    }
}
