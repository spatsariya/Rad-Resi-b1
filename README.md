# Radiology Resident - Medical Education Platform

A comprehensive web application for radiology education, inspired by [Marrow.com](https://www.marrow.com/), built with PHP and Tailwind CSS.

## 🚀 Live Demo

- **Live URL**: [https://lawngreen-skunk-121174.hostingersite.com/p](https://lawngreen-skunk-121174.hostingersite.com/p)
- **Admin Panel**: Coming soon with TailAdmin template integration

## 🛠️ Tech Stack

- **Backend**: PHP 8+ with custom MVC framework
- **Frontend**: Tailwind CSS + JavaScript
- **Database**: MySQL with phpMyAdmin
- **Hosting**: Hostinger Shared Hosting
- **Templates**: 
  - Frontend: [Skilline Landing Page](https://github.com/mhaecal/skilline-landing-page)
  - Admin: [TailAdmin Dashboard](https://github.com/TailAdmin/tailadmin-free-tailwind-dashboard-template)

## 📋 Features

### Current Features
- ✅ Responsive design with Tailwind CSS
- ✅ Secure configuration management
- ✅ Database connection with PDO
- ✅ MVC architecture with routing
- ✅ User authentication system (coming soon)
- ✅ Course management system (coming soon)
- ✅ Contact form with validation
- ✅ Error handling and logging

### Planned Features
- 🔄 User registration and login
- 🔄 Course enrollment system
- 🔄 Video lessons with progress tracking
- 🔄 Quizzes and assessments
- 🔄 Certificate generation
- 🔄 Payment integration
- 🔄 Admin dashboard
- 🔄 Email notifications

## 🗂️ Project Structure

```
Rad-Resi-b1/
├── backend/
│   ├── controllers/        # Application controllers
│   ├── core/              # Core classes (Database, Router)
│   ├── models/            # Data models
│   ├── views/             # View templates
│   ├── middleware/        # Middleware classes
│   └── bootstrap.php      # Application bootstrap
├── config/
│   ├── config.php         # Production config (git-ignored)
│   └── config.example.php # Example configuration
├── database/
│   └── schema.sql         # Database schema
├── frontend/              # Static frontend assets
├── logs/                  # Application logs (git-ignored)
├── uploads/               # File uploads (git-ignored)
├── .htaccess             # URL rewriting rules
├── index.php             # Application entry point
└── README.md
```

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/spatsariya/Rad-Resi-b1.git
cd Rad-Resi-b1
```

### 2. Database Setup

1. **Create Database**: Access phpMyAdmin on your hosting panel
2. **Import Schema**: Run the SQL script from `database/schema.sql`
3. **Database Details**:
   - Database: `u722501111_rad_resi_beta`
   - Username: `u722501111_rad_resi_beta`
   - Password: `K0;3T6Of1x`

### 3. Configuration

1. **Copy config file**:
   ```bash
   cp config/config.example.php config/config.php
   ```

2. **Update config.php** with your actual values:
   - Database credentials
   - Site URLs
   - Email settings
   - Security keys

### 4. Set Permissions

Ensure the following directories are writable:
```bash
chmod 755 logs/
chmod 755 uploads/
chmod 755 temp/
chmod 755 cache/
```

### 5. Deploy to Server

Upload all files to your hosting directory (`/p/` in your case).

## 🔧 Configuration

### Environment Settings

Edit `config/config.php` to match your environment:

```php
// Site Configuration
define('SITE_URL', 'https://your-domain.com');
define('ENVIRONMENT', 'production'); // or 'development'

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Security
define('JWT_SECRET', 'your-secret-key');
define('ENCRYPTION_KEY', 'your-32-char-key');
```

### URL Rewriting

The `.htaccess` file handles URL rewriting. Make sure mod_rewrite is enabled on your server.

## 🗃️ Database Schema

The application uses the following main tables:

- **users**: User accounts and profiles
- **courses**: Course information
- **course_categories**: Course categorization
- **course_lessons**: Individual lessons
- **enrollments**: Student course enrollments
- **lesson_progress**: Learning progress tracking
- **reviews**: Course reviews and ratings
- **contact_messages**: Contact form submissions

## 🔐 Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **SQL Injection Prevention**: Prepared statements with PDO
- **XSS Protection**: Input sanitization and output escaping
- **Secure Headers**: Security headers via .htaccess
- **Configuration Security**: Sensitive files protected from direct access
- **Password Hashing**: PHP password_hash() function
- **Session Security**: Secure session configuration

## 🎨 Frontend Development

### Tailwind CSS Setup

The project uses Tailwind CSS via CDN. For custom builds:

```bash
npm install -D tailwindcss
npx tailwindcss init
```

### Template Integration

- **Frontend Template**: Based on Skilline Landing Page
- **Admin Template**: TailAdmin Dashboard (coming soon)

## 📱 API Endpoints

### Public API
- `GET /api/courses` - List all courses
- `GET /api/course/{id}` - Get course details
- `POST /api/contact` - Submit contact form

### Authentication API (Coming Soon)
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout

## 🧪 Testing

### Manual Testing Checklist

- [ ] Homepage loads correctly
- [ ] Navigation works on all devices
- [ ] Contact form validation
- [ ] Database connection
- [ ] Error pages (404, 500)
- [ ] URL routing
- [ ] Mobile responsiveness

## 🚀 Deployment

### Production Checklist

- [ ] Set `ENVIRONMENT` to 'production'
- [ ] Disable `DEBUG_MODE`
- [ ] Configure error logging
- [ ] Set up SSL certificate
- [ ] Configure email settings
- [ ] Test all functionality
- [ ] Set up backups

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is proprietary software. All rights reserved.

## 📞 Support

For support and questions:
- **Email**: info@radiologyresident.com
- **GitHub Issues**: [Create an issue](https://github.com/spatsariya/Rad-Resi-b1/issues)

## 🔄 Changelog

### Version 1.0.0 (Current)
- Initial project setup
- Basic MVC framework
- Database schema design
- Frontend template integration
- Security implementation
- Contact form functionality

---

**Built with ❤️ for the radiology community**
