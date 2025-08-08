<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            color: #333;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .content {
            color: #555;
        }

        .submission-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .field-row {
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .field-label {
            font-weight: bold;
            color: #333;
        }

        .field-value {
            color: #666;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #888;
        }

        .thank-you-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Thank You for Your Submission!</h1>
        </div>

        <div class="content">
            <p>Dear {{ $submission->submitter_name ?? 'Valued User' }},</p>

            <div class="thank-you-message">
                <h3>✅ Your submission has been received successfully!</h3>
            </div>

            <p>Thank you for taking the time to submit the form <strong>"{{ $submission->form->title }}"</strong>. We have successfully received your submission and appreciate your participation.</p>

            <div class="submission-details">
                <h3>📋 Submission Summary:</h3>
                <p><strong>Form:</strong> {{ $submission->form->title }}</p>
                <p><strong>Submitted at:</strong> {{ $submission->created_at->format('F j, Y \a\t g:i A') }}</p>
                @if($submission->submitter_name)
                <p><strong>Your Name:</strong> {{ $submission->submitter_name }}</p>
                @endif
                @if($submission->submitter_email)
                <p><strong>Your Email:</strong> {{ $submission->submitter_email }}</p>
                @endif

                @if($submission->values->count() > 0)
                <h4>📝 Your Responses:</h4>
                @foreach($submission->values as $value)
                <div class="field-row">
                    <div class="field-label">{{ $value->field_label }}:</div>
                    <div class="field-value">
                        @if(is_array(json_decode($value->value, true)))
                        {{ implode(', ', json_decode($value->value, true)) }}
                        @else
                        {{ $value->value }}
                        @endif
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            <p><strong>What happens next?</strong></p>
            <ul>
                <li>We will review your submission carefully</li>
                <li>If we need any additional information, we will contact you</li>
                <li>You can expect to hear from us within the next few business days</li>
            </ul>

            <p>If you have any questions or need to make changes to your submission, please don't hesitate to contact us.</p>

            <p>Thank you once again for your time and participation!</p>

            <p>Best regards,<br>
                <strong>The Team</strong>
            </p>
        </div>

        <div class="footer">
            <p><small>This is an automated confirmation email. Please keep this for your records.</small></p>
        </div>
    </div>
</body>

</html>