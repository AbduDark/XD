# replit.md

## Overview

This is a Laravel-based store management system designed for electronics and accessories retail businesses. The application provides comprehensive inventory management, sales processing, repair tracking, and financial management capabilities. It features a modern interface with dark mode support and is specifically tailored for Arabic-speaking markets, with RTL (right-to-left) text direction and Arabic language support.

The system handles multiple product categories including headphones, chargers, mice, microphones, LED accessories, auto accessories, phone cases, and cables. It supports multi-store operations with role-based access control for different user types.

## User Preferences

Preferred communication style: Simple, everyday language.

## System Architecture

### Backend Architecture
- **Framework**: Laravel 11 with PHP 8.2+ requirement
- **Database**: MySQL with Eloquent ORM for data modeling
- **Authentication**: Laravel Breeze for user authentication and authorization
- **Architecture Pattern**: MVC (Model-View-Controller) with service layer
- **Database Migrations**: Schema versioning with timestamped migrations
- **Seeders**: Database seeding for initial data setup

### Frontend Architecture
- **CSS Framework**: Tailwind CSS with custom Cairo font integration
- **JavaScript**: Vanilla JavaScript with Chart.js for data visualization
- **Build System**: Vite for asset compilation and hot module replacement
- **Styling**: Modern card-based UI with dark mode support
- **Components**: Reusable CSS components for buttons, cards, and navigation
- **Internationalization**: Arabic RTL support with Cairo font family

### Core Data Models
- **Users**: Authentication and role management (admin, employee)
- **Stores**: Multi-store support with ownership and settings
- **Products**: Inventory items with categories and store-specific tracking
- **Invoices**: Sales transactions with line items
- **Repairs**: Device repair tracking and management
- **Returns**: Product return processing
- **Cash Transfers**: Financial transaction management

### Key Features
- **Inventory Management**: Product categorization, stock tracking, and store-specific inventory
- **Sales Processing**: Invoice generation with line items and tax calculations
- **Repair Services**: Complete repair workflow management
- **Financial Tracking**: Cash transfers, revenue tracking, and reporting
- **Multi-Store Support**: Store-specific operations with role-based access
- **Dashboard Analytics**: Real-time statistics and chart visualization
- **Backup System**: Data backup and restoration capabilities

### Security and Access Control
- **Role-based Access**: Admin and employee roles with different permissions
- **Store-based Isolation**: Users can only access their assigned store's data
- **CSRF Protection**: Built-in Laravel CSRF token validation
- **Input Validation**: Server-side validation for all user inputs

## External Dependencies

### PHP Dependencies
- **Laravel Framework**: Core web application framework (^11.0)
- **Laravel Breeze**: Authentication scaffolding for login/registration
- **Laravel Tinker**: Interactive PHP REPL for debugging
- **Faker**: Test data generation for development and testing

### JavaScript Dependencies
- **Chart.js**: Data visualization and dashboard charts (^4.5.0)
- **Tailwind CSS**: Utility-first CSS framework (^3.4.0)
- **@tailwindcss/forms**: Enhanced form styling components
- **Vite**: Modern build tool for asset compilation
- **Axios**: HTTP client for API requests

### Development Tools
- **Laravel Pint**: Code formatting and style checking
- **Laravel Sail**: Docker development environment
- **PHPUnit**: Unit and feature testing framework
- **Mockery**: Mocking framework for tests

### Database
- **MySQL**: Primary database for data storage
- **Database Migrations**: Version-controlled schema management
- **Eloquent ORM**: Laravel's built-in object-relational mapping

The application is designed to be scalable and maintainable, with clear separation of concerns between the frontend presentation layer, backend business logic, and data persistence layer.