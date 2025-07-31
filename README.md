# 🧩 Filament Form Builder

[![Laravel](https://img.shields.io/badge/Laravel-10+-red.svg)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v3-orange.svg)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC.svg)](https://tailwindcss.com)

A powerful and intuitive dynamic form builder built with Laravel & Filament that enables you to create, manage, and deploy custom forms through an elegant admin interface. Perfect for contact forms, surveys, registration forms, and more.

## ✨ Key Features

-   🎨 **Drag & Drop Form Builder** - Intuitive admin interface for creating forms
-   🔧 **Multiple Field Types** - Text, textarea, select, checkbox, radio, file uploads, and more
-   📧 **Smart Notifications** - Configurable email notifications with conditional logic
-   🎯 **Conditional Logic** - Show/hide fields based on user input
-   📱 **Responsive Design** - Beautiful Tailwind CSS frontend
-   🔒 **Validation Rules** - Per-field validation with custom error messages
-   📊 **Submission Management** - View and manage form submissions
-   🌐 **Multi-language Ready** - Support for internationalization

## 🚀 Tech Stack

-   **Backend:** Laravel 10+, PHP 8.2+
-   **Admin Panel:** Filament v3
-   **Frontend:** Tailwind CSS, Livewire
-   **Database:** MySQL / PostgreSQL / SQLite
-   **File Storage:** Local filesystem (configurable)
-   **Email:** Laravel Mail with queue support

## � Prerequisites

Before installing, ensure your system meets these requirements:

-   PHP 8.2 or higher
-   Composer
-   Node.js & NPM
-   MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.8+
-   Web server (Apache/Nginx) or Laravel Valet

## �📦 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/rasyidly/formbuilder.git
cd formbuilder
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install and build frontend assets
npm install
npm run build
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=formbuilder
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

### 4. Database Setup

```bash
# Run migrations
php artisan migrate

# (Optional) Seed demo data
php artisan db:seed
```

### 5. Storage Setup

```bash
# Create symbolic link for public storage
php artisan storage:link
```

### 6. File Permissions

```bash
# Set proper permissions (Unix/Linux/macOS)
chmod -R 775 storage bootstrap/cache
```

**Important:** Make sure your `.env` file is properly configured for database connection and mail settings.

## 🛠️ Features

### 🎛️ Admin Panel (Filament)

**Form Management:**

-   ✅ Drag & drop form builder interface
-   ✅ Dynamic field configuration with Builder field
-   ✅ Support for multiple field types:
    -   Text input, Textarea, Email, Number
    -   Select dropdown, Multi-select
    -   Checkbox, Radio buttons
    -   File upload (single/multiple)
    -   Date picker, Time picker
    -   Hidden fields

**Advanced Configuration:**

-   ✅ Field-level validation rules
-   ✅ Conditional field visibility
-   ✅ Custom field labels and placeholders
-   ✅ Required/optional field settings
-   ✅ Field grouping and ordering

**Notification System:**

-   ✅ Admin email notifications
-   ✅ Submitter auto-reply emails
-   ✅ Conditional email routing
-   ✅ Custom email templates
-   ✅ Email queue support

**Confirmation Settings:**

-   ✅ Custom thank you messages
-   ✅ Redirect URL configuration
-   ✅ Success page templates

### 🌐 Frontend Interface

**Form Rendering:**

-   ✅ Responsive Tailwind CSS design
-   ✅ Dynamic form generation from JSON config
-   ✅ Real-time validation feedback
-   ✅ Progressive enhancement
-   ✅ Accessibility compliant (WCAG 2.1)

**User Experience:**

-   ✅ Multi-step form support
-   ✅ File upload with progress indicators
-   ✅ Form data persistence
-   ✅ Mobile-optimized interface
-   ✅ Loading states and animations

**Security Features:**

-   ✅ CSRF protection
-   ✅ Rate limiting
-   ✅ File type validation
-   ✅ XSS protection
-   ✅ SQL injection prevention

## 📁 Project Structure

(Coming soon)

## 🚀 Usage

### Creating Your First Form

1. **Access Admin Panel**

    ```
    Navigate to /admin and login with your credentials
    ```

2. **Create New Form**

    - Go to "Forms" section
    - Click "New Form"
    - Enter form name and description
    - Configure basic settings

3. **Build Form Fields**

    - Use the visual form builder
    - Drag and drop field types
    - Configure field properties:
        - Label and placeholder text
        - Validation rules
        - Conditional logic
        - Help text

4. **Configure Notifications**

    - Set admin notification email
    - Configure auto-reply settings
    - Customize email templates
    - Set up conditional email routing

5. **Publish Form**
    - Set form status to "Published"
    - Copy the public form URL
    - Embed in your website

### Displaying Forms

**Direct URL Access:**

```
https://yoursite.com/forms/{slug}
```

**Embed in Blade Template:**

```php
@livewire('form', ['form' => $form])
```

**Include via Route:**

```php
Route::get('/contact', function () {
    $form = App\Models\Form::where('slug', 'contact')->firstOrFail();
    return view('forms.show', compact('form'));
});
```

### Managing Submissions

1. Access "Form Entries" in admin panel
2. Filter submissions by form, date, or status
3. Export data to CSV/Excel
4. View detailed submission information
5. Respond to submissions directly

## ⚙️ Configuration

### Environment Variables

```env
# Application
APP_NAME="Form Builder"
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=formbuilder

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

## 🧪 Development & Testing

### Development Server

```bash
# Start Laravel development server
php artisan serve

# Watch for asset changes
npm run dev

# Run queue worker for email processing
php artisan queue:work
```

### Code Quality

```bash
# Run PHP CS Fixer
composer run cs-fix

# Run PHPStan analysis
composer run analyse

# Run Pint formatter
./vendor/bin/pint
```

## 🔧 API Reference

(Coming soon)

## 🛠️ Upcoming Features

### Conditional Logic

Create dynamic forms that show/hide fields based on user input:

```json
{
    "field_id": "company_size",
    "conditions": [
        {
            "field": "business_type",
            "operator": "equals",
            "value": "enterprise",
            "action": "show"
        }
    ]
}
```

### Custom Validation Rules

Add custom validation for specific business logic:

```php
// In your FormSubmissionRequest
public function rules()
{
    return [
        'email' => ['required', 'email', new UniqueEmailRule()],
        'age' => ['required', 'integer', 'min:18'],
        'terms' => ['accepted']
    ];
}
```

### Multi-step Forms

Break long forms into manageable steps:

```php
// Configure in form builder
'steps' => [
    ['title' => 'Personal Info', 'fields' => ['name', 'email']],
    ['title' => 'Company Details', 'fields' => ['company', 'role']],
    ['title' => 'Preferences', 'fields' => ['newsletter', 'updates']]
]
```

### Form Analytics

Track form performance and conversion rates:

-   Submission rates by form
-   Drop-off points in multi-step forms
-   Popular form fields
-   Time-to-completion metrics

## 🚀 Deployment

### Production Checklist

-   [ ] Set `APP_ENV=production` in `.env`
-   [ ] Configure proper database credentials
-   [ ] Set up email service (SMTP/SendGrid/SES)
-   [ ] Configure file storage (S3/DigitalOcean Spaces)
-   [ ] Set up SSL certificate
-   [ ] Configure caching (Redis/Memcached)
-   [ ] Set up queue worker process
-   [ ] Configure backup strategy
-   [ ] Set up monitoring and logging

### Performance Optimization

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer autoloader
composer install --optimize-autoloader --no-dev

# Build production assets
npm run build
```

### Server Requirements

-   PHP 8.2+ with required extensions
-   Web server (Nginx/Apache) with URL rewriting
-   Database server (MySQL 8.0+)
-   Redis for caching and queues (recommended)
-   Supervisor for queue workers
-   SSL certificate for HTTPS

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the Repository**

    ```bash
    git fork https://github.com/rasyidly/formbuilder.git
    ```

2. **Create Feature Branch**

    ```bash
    git checkout -b feature/amazing-feature
    ```

3. **Make Changes**

    - Follow PSR-12 coding standards
    - Add tests for new features
    - Update documentation

4. **Run Tests**

    ```bash
    composer test
    ```

5. **Submit Pull Request**
    - Provide clear description
    - Reference related issues
    - Include screenshots if applicable

### Development Guidelines

-   Use meaningful commit messages
-   Follow Laravel and Filament conventions
-   Write comprehensive tests
-   Update documentation for new features
-   Ensure backward compatibility

## 🆘 Support & Troubleshooting

### Common Issues

**Form not displaying:**

-   Check if form is published and active
-   Verify route configuration
-   Check for JavaScript errors

**Email notifications not working:**

-   Verify mail configuration in `.env`
-   Check queue worker is running
-   Review mail logs for errors

**File uploads failing:**

-   Check file permissions on storage directory
-   Verify upload limits in PHP configuration
-   Ensure proper MIME type validation

### Getting Help

-   🐛 **Bug Reports:** [GitHub Issues](https://github.com/rasyidly/formbuilder/issues)

## 🧑‍💻 Author

**Rasyidly** - Laravel & Filament Specialist  
🌐 [Website](https://rasyidly.my.id) | 🐙 [GitHub](https://github.com/rasyidly)

_Passionate about creating elegant solutions with Laravel and modern web technologies. Specializing in admin panels, form builders, and scalable web applications._

## 📄 License

This project is licensed under a **Private License** for Fiverr contract usage.

**Terms:**

-   ✅ Client has full usage rights for purchased project
-   ✅ Modification and customization allowed
-   ❌ Redistribution or resale prohibited
-   ❌ Open source publication not permitted

For licensing inquiries or custom development needs, please [contact me](mailto:rasyid@sekeco.id)

## 🙏 Acknowledgments

-   [Laravel](https://laravel.com) - The PHP framework for web artisans
-   [Filament](https://filamentphp.com) - Beautiful admin panels for Laravel
-   [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
-   [Livewire](https://livewire.laravel.com) - Full-stack framework for Laravel
