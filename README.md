# Product Management System

A Laravel-based Product Management System that integrates with the FakeStore API to fetch, store, edit, and manage products.

## Features

- **Fetch from API**: One-click button to fetch products from FakeStore API and store them in the database
- **Duplicate Prevention**: Automatically avoids importing duplicate products
- **Product Listing**: Display all products in a paginated table with images, prices, categories, and ratings
- **Edit Products**: Edit product details including title, price, category, description, and image URL
- **Delete Products**: Remove products from the database with confirmation
- **Validation**: Basic form validation on product updates
- **Responsive UI**: Clean, Bootstrap-based interface

## Requirements

- PHP 8.2 or higher
- Composer
- SQLite (included)

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/product-management-api-task.git
cd product-management-api-task
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

The project uses SQLite by default. If you want to use a different database, update your `.env` file accordingly.

### 4. Run Migrations

Create the database and run the migrations:

```bash
touch database/database.sqlite
php artisan migrate
```

### 5. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Usage

### Fetching Products

1. Visit `http://localhost:8000`
2. Click the **"Fetch from API"** button at the top
3. Products will be fetched from FakeStore API and stored in the database
4. You'll see a summary of how many products were imported and how many duplicates were skipped

### Managing Products

- **View Products**: The main page displays all products in a table with pagination
- **Edit Product**: Click the **"Edit"** button next to any product to modify its details
- **Delete Product**: Click the **"Delete"** button to remove a product (with confirmation)

## Testing

The project includes comprehensive Pest tests covering all CRUD operations.

Run all tests:

```bash
php artisan test
```

Run only product tests:

```bash
php artisan test --filter=ProductTest
```

## Project Structure

```
app/
├── Http/
│   └── Controllers/
│       └── ProductController.php    # Handles all product operations
├── Models/
│   └── Product.php                  # Product Eloquent model
database/
├── factories/
│   └── ProductFactory.php           # Factory for testing
└── migrations/
    └── create_products_table.php    # Products table schema
resources/
└── views/
    ├── layouts/
    │   └── app.blade.php            # Main layout
    └── products/
        ├── index.blade.php          # Products list view
        └── edit.blade.php           # Edit product view
routes/
└── web.php                          # Route definitions
tests/
└── Feature/
    └── ProductTest.php              # Feature tests
```

## API Integration

The application integrates with the [FakeStore API](https://fakestoreapi.com/) to fetch product data. Each product includes:

- Title
- Price
- Description
- Category
- Image URL
- Rating (rate and count)

## Database Schema

The `products` table includes the following columns:

- `id` (Primary Key)
- `title`
- `price`
- `description` (nullable)
- `category`
- `image` (nullable)
- `rating_rate` (nullable)
- `rating_count` (nullable)
- `api_product_id` (unique, nullable) - Used to prevent duplicates
- `created_at`
- `updated_at`

## Technologies Used

- **Laravel 12** - Web application framework
- **PHP 8.2+** - Programming language
- **SQLite** - Database
- **Bootstrap 5** - CSS framework
- **Pest** - Testing framework
- **Laravel HTTP Client** - For API requests

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues or questions, please open an issue on the GitHub repository.
