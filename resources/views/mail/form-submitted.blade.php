<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission Confirmation</title>
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
            border-bottom: 2px solid #007bff;
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
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <h1>📩 New Form Submission Received</h1>
        </div>

        <div class="content">
            <p>Hello,</p>

            <p>Someone has just submitted the form <strong>"{{ $submission->form->title }}"</strong> on your website. Below are the details of the submission:</p>

            <div class="submission-details">
                <h3>📋 Submission Details:</h3>
                <p><strong>Form:</strong> {{ $submission->form->title }}</p>
                <p><strong>Submitted at:</strong> {{ $submission->created_at->format('F j, Y \a\t g:i A') }}</p>
                @if($submission->submitter_name)
                <p><strong>Submitter Name:</strong> {{ $submission->submitter_name }}</p>
                @endif
                @if($submission->submitter_email)
                <p><strong>Submitter Email:</strong> {{ $submission->submitter_email }}</p>
                @endif

                @if($submission->values->count() > 0)
                <h4>📝 Submitted Answers:</h4>
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

            <p>Please review this submission as soon as possible. If you need to follow up, you can contact the submitter using the information above (if provided).</p>

            <p>Best regards,<br>
                <strong>Your Website Notification System</strong>
            </p>
        </div>

        <div class="footer">
            <p><small>This is an automated email. Please do not reply to this email address.</small></p>
        </div>
    </div>
</body>

</html>