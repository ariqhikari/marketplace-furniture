# PROMPT SKPL: FURNITURE MARKETPLACE - E-COMMERCE APPLICATION

Buatkan aplikasi **Furniture Marketplace** berbasis Laravel dengan spesifikasi lengkap berikut. Implementasikan semua fitur secara complete dan production-ready.

---

## 1. PROJECT OVERVIEW

**Nama Aplikasi:** FurniShop - Furniture Marketplace  
**Jenis:** E-Commerce Multi-Vendor untuk Furniture  
**Framework:** Laravel 11 (PHP 8.2+)  
**Database:** MySQL  
**Tujuan:** Platform marketplace furniture dimana seller bisa menjual produk furniture mereka dan buyer bisa membeli dengan sistem order langsung (tanpa payment gateway online).

**KUK (Kriteria Unjuk Kerja) yang Harus Diterapkan:**

- ✅ J.620100.003.01 - Menggunakan Library/Framework
- ✅ J.620100.017.02 - Pemrograman Terstruktur (MVC)
- ✅ J.620100.018.02 - Pemrograman Berorientasi Objek (OOP)
- ✅ J.620100.019.02 - Library PHP Built-in
- ✅ J.620100.020.02 - Menggunakan SQL
- ✅ J.620100.021.02 - Akses Database dengan Eloquent ORM
- ✅ J.620100.022.01 - Algoritma (search, sort, pagination, kalkulasi)
- ✅ J.620100.024.02 - Migrasi Database
- ✅ J.620100.030.02 - Multimedia (Upload File)
- ✅ J.620100.032.02 - Code Review & Best Practices
- ✅ J.620100.036.01 - Pengujian (PHPUnit Testing)
- ✅ J.620100.044.01 - Alert/Notifikasi (Flash Messages)
- ✅ J.620100.045.01 - Monitoring (System Info & Logs)
- ✅ J.620100.047.01 - Pembaruan Program Logic

---

## 2. TECHNOLOGY STACK

### Backend:

- Laravel 11.x (Framework MVC) - **KUK J.620100.003.01, J.620100.017.02**
- PHP 8.2+
- MySQL/MariaDB
- Eloquent ORM - **KUK J.620100.021.02**
- **Manual Authentication** (Auth facade, tanpa Breeze/Jetstream) - **KUK J.620100.027.02**
- Session-based authentication dengan Hash password - **KUK J.620100.026.02**

### Frontend:

- Bootstrap 5 (via CDN) - **KUK J.620100.003.01**
- FontAwesome 6 (icons via CDN) - **KUK J.620100.003.01**
- jQuery (via CDN)
- Vite (Laravel default)
- Blade Templating Engine

### Additional Packages (via Composer):

- `yajra/laravel-datatables` v11 - **KUK J.620100.003.01**
- `intervention/image` v3 untuk image optimization - **KUK J.620100.030.02**

### Testing:

- PHPUnit 11 (included in Laravel 11) - **KUK J.620100.003.01, J.620100.036.01**

### PHP Built-in Functions (harus digunakan):

- `file()` - untuk read log files - **KUK J.620100.019.02**
- `json_decode()` - untuk parse composer.json - **KUK J.620100.019.02**
- `memory_get_usage()`, `memory_get_peak_usage()` - monitoring - **KUK J.620100.019.02**
- `microtime()` - measure execution time - **KUK J.620100.019.02**
- `RecursiveDirectoryIterator` - calculate folder size - **KUK J.620100.019.02**
- `number_format()` - format currency - **KUK J.620100.019.02**

### Skip (Tidak perlu):

- Payment gateway (Midtrans, Stripe, dll)
- Real-time notification
- Email verification (optional)
- REST API endpoints (fokus web only)

---

## 3. USER ROLES & PERMISSIONS

### Admin (ADMIN)

- Full access ke admin panel (`/admin`)
- Manage categories (CRUD)
- Manage all products from all sellers (CRUD)
- Manage users (CRUD) - bisa promote/demote role
- Manage orders (view, update status)
- View dashboard statistics
- **Access System Monitor** (`/monitor`) - **KUK J.620100.045.01**
- **Access Application Logs** (`/logs`) - **KUK J.620100.045.01**
- **Access About/Info** (`/about`) - **KUK J.620100.003.01, J.620100.019.02**

### Seller (SELLER)

- Register sebagai seller dengan store name
- Dashboard seller (`/dashboard`)
- Manage own products only (CRUD)
- Upload product images (multiple galleries)
- View own orders/sales
- Update order status (Processing, Shipped, Delivered)
- View sales statistics

### Buyer (USER)

- Register as regular user
- Browse products by category
- View product details
- Add to cart, update quantity, remove from cart
- Checkout and place order (tanpa payment)
- View order history and status
- View profile and edit profile

---

## 4. DATABASE SCHEMA

### Table: users

```sql
id - bigint primary key
name - varchar(255)
email - varchar(255) unique
password - varchar(255)
avatar - varchar(255) nullable
phone - varchar(20) nullable
role - enum('USER', 'SELLER', 'ADMIN') default 'USER'

-- Seller specific fields (nullable)
store_name - varchar(255) nullable
store_description - text nullable
store_logo - varchar(255) nullable

-- Address fields
address - text nullable
city - varchar(100) nullable
province - varchar(100) nullable
postal_code - varchar(10) nullable

timestamps
```

### Table: categories

```sql
id - bigint primary key
name - varchar(255) unique
slug - varchar(255) unique
description - text nullable
image - varchar(255) nullable
is_active - boolean default true
timestamps
soft_deletes
```

### Table: products

```sql
id - bigint primary key
user_id - bigint foreign key (users)
category_id - bigint foreign key (categories)
name - varchar(255)
slug - varchar(255) unique
description - text
price - decimal(15,2)
stock - integer default 0

-- Furniture specific fields
material - varchar(100) nullable (e.g., "Kayu Jati", "Metal", "Rotan")
color - varchar(50) nullable
dimensions - varchar(100) nullable (e.g., "200x100x80 cm")
weight - decimal(8,2) nullable (in kg)
warranty_period - integer nullable (in months)

-- SEO & Status
is_featured - boolean default false
is_active - boolean default true
view_count - integer default 0

timestamps
soft_deletes
```

### Table: product_images

```sql
id - bigint primary key
product_id - bigint foreign key (products) on delete cascade
image_path - varchar(255)
is_primary - boolean default false
sort_order - integer default 0
timestamps
```

### Table: carts

```sql
id - bigint primary key
user_id - bigint foreign key (users) on delete cascade
product_id - bigint foreign key (products) on delete cascade
quantity - integer default 1
timestamps

unique(user_id, product_id)
```

### Table: orders

```sql
id - bigint primary key
order_number - varchar(50) unique (e.g., "ORD-20260223-001")
user_id - bigint foreign key (users)
total_price - decimal(15,2)
status - enum('PENDING', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED') default 'PENDING'

-- Shipping info (snapshot dari user address saat order)
shipping_name - varchar(255)
shipping_phone - varchar(20)
shipping_address - text
shipping_city - varchar(100)
shipping_province - varchar(100)
shipping_postal_code - varchar(10)

notes - text nullable
timestamps
soft_deletes
```

### Table: order_items

```sql
id - bigint primary key
order_id - bigint foreign key (orders) on delete cascade
product_id - bigint foreign key (products)
seller_id - bigint foreign key (users) -- penjual produk ini
quantity - integer
price - decimal(15,2) -- snapshot harga saat order
subtotal - decimal(15,2)

-- Item specific status & tracking
status - enum('PENDING', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED') default 'PENDING'
tracking_number - varchar(100) nullable
notes - text nullable

timestamps
```

---

## 5. MODELS & RELATIONSHIPS

### User Model

```php
// Relationships
- hasMany: products
- hasMany: orders
- hasMany: carts
- hasMany: order_items (as seller)

// Methods
- isSeller(): bool
- isAdmin(): bool
- getFullAddress(): string
```

### Category Model

```php
// Relationships
- hasMany: products

// Attributes
- fillable: name, slug, description, image, is_active
- with SoftDeletes
```

### Product Model - **KUK J.620100.018.02 (OOP), J.620100.021.02 (Eloquent)**

```php
class Product extends Model  // INHERITANCE — KUK J.620100.018.02
{
    use SoftDeletes;  // Laravel Trait

    // ENCAPSULATION: Protected fillable — KUK J.620100.018.02
    protected $fillable = [
        'user_id', 'category_id', 'name', 'slug', 'description',
        'price', 'stock', 'material', 'color', 'dimensions',
        'weight', 'warranty_period', 'is_featured', 'is_active', 'view_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // RELATIONSHIPS — KUK J.620100.021.02
    public function user()  // seller
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // QUERY SCOPES — KUK J.620100.021.02
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%")
                     ->orWhere('description', 'like', "%{$keyword}%")
                     ->orWhere('material', 'like', "%{$keyword}%");
    }

    // ACCESSORS — KUK J.620100.018.02 (Encapsulation getter)
    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->productImages()->where('is_primary', true)->first()
               ?? $this->productImages()->first();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->primary_image) {
            return asset('storage/' . $this->primary_image->image_path);
        }
        return 'https://placehold.co/400x400/e9ecef/495057?text=No+Image';
    }

    public function getFormattedPriceAttribute(): string
    {
        // PHP built-in function — KUK J.620100.019.02
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // METHODS — KUK J.620100.018.02
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function isAvailable(int $requestedQty = 1): bool
    {
        return $this->is_active && $this->stock >= $requestedQty;
    }
}
```

### ProductImage Model

```php
// Relationships
- belongsTo: product
```

### Cart Model

```php
// Relationships
- belongsTo: user
- belongsTo: product

// Methods
- getSubtotal(): decimal
```

### Order Model - **KUK J.620100.021.02, J.620100.022.01**

```php
class Order extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';  // atau custom 'id_order'

    protected $fillable = [
        'order_number', 'user_id', 'total_price', 'status',
        'shipping_name', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_province', 'shipping_postal_code', 'notes'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // RELATIONSHIPS — KUK J.620100.021.02
    public function user()  // buyer
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // SCOPES
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForSeller($query, $sellerId)
    {
        return $query->whereHas('orderItems', function($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        });
    }

    // ACCESSOR — ALGORITHM: Aggregation — KUK J.620100.022.01
    public function getGrandTotalAttribute(): float
    {
        return $this->orderItems->sum(function ($item) {
            return ($item->price ?? 0) * ($item->quantity ?? 0);
        });
    }

    public function getFormattedTotalAttribute(): string
    {
        return format_rupiah($this->total_price);  // helper function
    }

    // STATUS BADGE COLOR
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'PENDING' => 'warning',
            'PROCESSING' => 'info',
            'SHIPPED' => 'primary',
            'DELIVERED' => 'success',
            'CANCELLED' => 'danger',
            default => 'secondary',
        };
    }
}
```

### OrderItem Model - **KUK J.620100.021.02**

```php
class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'seller_id', 'quantity',
        'price', 'subtotal', 'status', 'tracking_number', 'notes'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // RELATIONSHIPS — KUK J.620100.021.02
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // ACCESSOR — KUK J.620100.022.01
    public function getFormattedSubtotalAttribute(): string
    {
        return format_rupiah($this->subtotal);
    }
}
```

---

## 6. ROUTES STRUCTURE

### Public Routes (Guest)

```php
GET  /                              → HomeController@index (homepage dengan featured products)
GET  /products                      → ProductController@index (all products dengan filter)
GET  /products/{slug}               → ProductController@show (product detail)
GET  /category/{slug}               → CategoryController@show (products by category)
GET  /search                        → ProductController@search (search products)
```

### Auth Routes - **KUK J.620100.027.02, J.620100.026.02, J.620100.023.02**

```php
// Manual Authentication (tidak pakai Breeze)
// routes/web.php

// Guest routes (belum login)
Route::middleware('guest')->group(function () {
    GET  /login                      → AuthController@showLoginForm
    POST /login                      → AuthController@login
    GET  /register                   → AuthController@showRegisterForm
    POST /register                   → AuthController@register
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    POST /logout                     → AuthController@logout
});
```

### Buyer Routes (Middleware: auth)

```php
GET    /cart                        → CartController@index
POST   /cart/add/{product}          → CartController@add
PATCH  /cart/{cart}                 → CartController@update (quantity)
DELETE /cart/{cart}                 → CartController@destroy

GET    /checkout                    → CheckoutController@index
POST   /checkout                    → CheckoutController@store

GET    /orders                      → OrderController@index (user's orders)
GET    /orders/{order}              → OrderController@show (order detail)

GET    /profile                     → ProfileController@edit
PATCH  /profile                     → ProfileController@update
```

### Seller Routes (Middleware: auth, role:SELLER|ADMIN)

```php
// Prefix: /dashboard
GET    /dashboard                       → Dashboard\DashboardController@index
GET    /dashboard/products              → Dashboard\ProductController@index
GET    /dashboard/products/create       → Dashboard\ProductController@create
POST   /dashboard/products              → Dashboard\ProductController@store
GET    /dashboard/products/{id}/edit    → Dashboard\ProductController@edit
PATCH  /dashboard/products/{id}         → Dashboard\ProductController@update
DELETE /dashboard/products/{id}         → Dashboard\ProductController@destroy

GET    /dashboard/orders                → Dashboard\OrderController@index (seller's orders)
GET    /dashboard/orders/{order}        → Dashboard\OrderController@show
PATCH  /dashboard/orders/{orderItem}    → Dashboard\OrderController@updateStatus

GET    /dashboard/settings              → Dashboard\SettingController@edit (store settings)
PATCH  /dashboard/settings              → Dashboard\SettingController@update
```

### Admin Routes (Middleware: auth, role:ADMIN)

```php
// Prefix: /admin
GET    /admin                           → Admin\DashboardController@index

// Categories
GET    /admin/categories                → Admin\CategoryController@index (with DataTables)
GET    /admin/categories/create         → Admin\CategoryController@create
POST   /admin/categories                → Admin\CategoryController@store
GET    /admin/categories/{id}/edit      → Admin\CategoryController@edit
PATCH  /admin/categories/{id}           → Admin\CategoryController@update
DELETE /admin/categories/{id}           → Admin\CategoryController@destroy

// Users
GET    /admin/users                     → Admin\UserController@index
GET    /admin/users/create              → Admin\UserController@create
POST   /admin/users                     → Admin\UserController@store
GET    /admin/users/{id}/edit           → Admin\UserController@edit
PATCH  /admin/users/{id}                → Admin\UserController@update
DELETE /admin/users/{id}                → Admin\UserController@destroy

// Products (all sellers)
GET    /admin/products                  → Admin\ProductController@index
GET    /admin/products/{id}/edit        → Admin\ProductController@edit
PATCH  /admin/products/{id}             → Admin\ProductController@update
DELETE /admin/products/{id}             → Admin\ProductController@destroy

// Orders (all)
GET    /admin/orders                    → Admin\OrderController@index
GET    /admin/orders/{order}            → Admin\OrderController@show
PATCH  /admin/orders/{order}            → Admin\OrderController@update

// System Monitor & Logs — KUK J.620100.045.01
GET    /monitor                         → MonitorController@index (system info, memory, storage)
GET    /logs                            → LogController@index (view laravel.log dengan filter)
GET    /about                           → AboutController@index (app info, dependencies dari composer.json)
```

---

## 7. CONTROLLERS LOGIC

### AuthController - **KUK J.620100.027.02, J.620100.026.02, J.620100.023.02**

**MANUAL AUTHENTICATION (Tanpa Breeze) - WAJIB IMPLEMENT!**

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     * KUK J.620100.017.02 (MVC: Controller → View)
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Process login
     * KUK J.620100.027.02 (Auth::attempt)
     * KUK J.620100.026.02 (Hash::check via Auth)
     * KUK J.620100.023.02 (Validation)
     */
    public function login(Request $request)
    {
        // VALIDATION — KUK J.620100.023.02
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // AUTH::ATTEMPT — KUK J.620100.027.02
        // Di balik layar: SELECT * FROM users WHERE email = ?
        // Kemudian: Hash::check($password, $user->password)
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();  // Get logged-in user

            // Session regenerate — prevent session fixation attack
            $request->session()->regenerate();

            // Flash message — KUK J.620100.044.01
            $message = 'Selamat datang, ' . $user->name . '!';

            // Redirect based on role
            if ($user->role === 'ADMIN') {
                return redirect()->route('admin.dashboard')
                                 ->with('success', $message);
            } elseif ($user->role === 'SELLER') {
                return redirect()->route('dashboard.index')
                                 ->with('success', $message);
            } else {
                return redirect()->route('home')
                                 ->with('success', $message);
            }
        }

        // Login failed
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Show register form
     * KUK J.620100.017.02 (MVC)
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Process registration
     * KUK J.620100.023.02 (Validation)
     * KUK J.620100.026.02 (Password Hashing)
     * KUK J.620100.021.02 (Eloquent Create)
     */
    public function register(Request $request)
    {
        // VALIDATION — KUK J.620100.023.02
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:USER,SELLER',
            'password' => 'required|string|min:6|confirmed',

            // Optional: Seller-specific fields
            'store_name'        => 'required_if:role,SELLER|nullable|string|max:255',
            'store_description' => 'nullable|string',
        ]);

        // PASSWORD HASHING — KUK J.620100.026.02
        // Gunakan cast 'hashed' di model atau Hash::make() manual
        $validated['password'] = Hash::make($validated['password']);

        // CREATE USER — KUK J.620100.021.02
        $user = User::create($validated);

        // Auto-login after register
        Auth::login($user);
        $request->session()->regenerate();

        // Flash success — KUK J.620100.044.01
        $message = 'Registrasi berhasil! Selamat datang, ' . $user->name;

        if ($user->role === 'SELLER') {
            return redirect()->route('dashboard.index')
                             ->with('success', $message);
        }

        return redirect()->route('home')
                         ->with('success', $message);
    }

    /**
     * Logout
     * KUK J.620100.027.02 (Auth facade)
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('success', 'Anda telah logout.');
    }
}
```

**User Model - Password Hashing Cast**

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar',
        'role', 'store_name', 'store_description', 'store_logo',
        'address', 'city', 'province', 'postal_code',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * PASSWORD HASHING CAST — KUK J.620100.026.02
     * Automatic hashing saat di-assign:
     * $user->password = 'plain_password'; → auto-hashed
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Helper methods — KUK J.620100.018.02 (Encapsulation)
    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isSeller(): bool
    {
        return $this->role === 'SELLER';
    }

    public function isUser(): bool
    {
        return $this->role === 'USER';
    }
}
```

---

### HomeController

```php
index():
- Get 8 featured products
- Get all active categories
- Show jumbotron/banner slider
- Return view: pages.home
```

### ProductController

```php
index():
- Get all active products dengan pagination
- Filter by category (optional)
- Filter by price range (optional)
- Sort by: newest, price_asc, price_desc, popular
- Return view: pages.products.index

show($slug):
- Find product by slug dengan images
- Increment view_count
- Get related products (same category, limit 4)
- Return view: pages.products.show

search(Request $request):
- Search by product name, description, material
- Return view: pages.products.search
```

### CartController

```php
index():
- Get user's cart items dengan product & images
- Calculate total
- Return view: pages.cart

add(Product $product):
- Validate stock availability
- Check if already in cart → update quantity
- Else create new cart item
- Redirect back with success message

update(Cart $cart, Request $request):
- Validate quantity
- Check stock
- Update cart quantity
- Return JSON response

destroy(Cart $cart):
- Delete cart item
- Redirect back with success
```

### CheckoutController - **KUK J.620100.022.01 (Algoritma Kalkulasi)**

```php
index():
- Get cart items with eager loading (with validation)
- If cart empty → redirect to products
- Show shipping form (prefill from user profile)
- ALGORITHM: Calculate cart total — KUK J.620100.022.01
  foreach($carts as $cart) {
      $subtotal = $cart->product->price * $cart->quantity;
      $grandTotal += $subtotal;
  }
- Return view: pages.checkout

store(CheckoutRequest $request):
- Validate shipping info
- Start DB transaction — KUK J.620100.020.02
  try {
      // Generate unique order number — KUK J.620100.022.01
      $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

      // Create order
      $order = Order::create([
          'order_number' => $orderNumber,
          'user_id' => auth()->id(),
          'total_price' => $grandTotal,
          'status' => 'PENDING',
          /* shipping info snapshot */
      ]);

      // Create order_items from cart — KUK J.620100.021.02 (Relasi)
      foreach ($carts as $cart) {
          // ALGORITHM: Calculate subtotal — KUK J.620100.022.01
          $subtotal = $cart->product->price * $cart->quantity;

          $order->orderItems()->create([
              'product_id' => $cart->product_id,
              'seller_id' => $cart->product->user_id,
              'quantity' => $cart->quantity,
              'price' => $cart->product->price,  // snapshot harga
              'subtotal' => $subtotal,
              'status' => 'PENDING',
          ]);

          // ALGORITHM: Decrease stock — KUK J.620100.022.01, J.620100.047.01
          $product = $cart->product;
          $product->decrement('stock', $cart->quantity);
          // or: $product->stock -= $cart->quantity; $product->save();
      }

      // Clear cart
      Cart::where('user_id', auth()->id())->delete();

      DB::commit();

      // Flash success — KUK J.620100.044.01
      return redirect()->route('order.success', $order)
                       ->with('success', 'Order berhasil dibuat!');
  } catch (\Exception $e) {
      DB::rollback();
      return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
  }
```

### Dashboard\ProductController (Seller) - **KUK J.620100.018.02 (OOP), J.620100.022.01 (Algoritma)**

**IMPORTANT: Gunakan Service Pattern untuk business logic!**

```php
// Dependency Injection — KUK J.620100.018.02
class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    index(Request $request):
    - Get auth user's products only
    - ALGORITHM: Search (WHERE LIKE) — KUK J.620100.022.01
    - ALGORITHM: Sort by column — KUK J.620100.022.01
    - ALGORITHM: Pagination (LIMIT/OFFSET) — KUK J.620100.022.01
    - Return view: dashboard.products.index

    create():
    - Get active categories
    - Return view: dashboard.products.create

    store(ProductRequest $request):
    - Validate input (via Form Request)
    - Call ProductService->store() dengan file upload
    - ProductService handles business logic & file storage
    - Flash message success — KUK J.620100.044.01
    - Redirect with success

    edit($id):
    - Find product (check ownership: user_id == auth()->id())
    - Abort 403 if not owner
    - Get categories
    - Return view: dashboard.products.edit

    update(ProductRequest $request, $id):
    - Find product (check ownership)
    - Validate input
    - Call ProductService->update() untuk handle file upload
    - Flash message success — KUK J.620100.044.01
    - Redirect with success

    destroy($id):
    - Find product (check ownership)
    - SoftDelete product
    - ProductService->delete() untuk delete images dari storage
    - Flash message success — KUK J.620100.044.01
    - Redirect with success
}

// SERVICE CLASS — KUK J.620100.018.02 (Separation of Concerns)
class ProductService
{
    public function store(array $data, ?UploadedFile $image = null): Product
    {
        // Handle file upload — KUK J.620100.030.02
        if ($image) {
            $data['image'] = $image->store('products', 'public');
        }
        // ALGORITHM: Calculate data — KUK J.620100.022.01
        $data['slug'] = Str::slug($data['name']);
        return Product::create($data);
    }

    public function update(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        // Delete old image if new uploaded — KUK J.620100.030.02
        if ($image) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $image->store('products', 'public');
        }
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): void
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
    }
}
```

### Admin\CategoryController

```php
index():
- If Ajax request → return DataTables
- Else return view: admin.categories.index

create/store/edit/update/destroy:
- Standard CRUD operations
- Image upload untuk category
- Slug auto-generation dari name
```

### Admin\UserController

```php
index():
- Get all users dengan role filter
- DataTables
- Return view: admin.users.index

create/store:
- Create user dengan role selection
- Password hashing

edit/update:
- Update user info
- Allow role change
- Password optional (only if filled)

destroy:
- Soft delete atau hard delete
```

### MonitorController - **KUK J.620100.045.01, J.620100.019.02**

```php
index():
- Get PHP version (PHP_VERSION constant)
- Get memory usage (memory_get_usage(), memory_get_peak_usage())
- Get PHP ini settings (ini_get('memory_limit'), ini_get('max_execution_time'))
- Calculate storage size (RecursiveDirectoryIterator)
- Get database size (DB::select("SELECT SUM(data_length + index_length)..."))
- Measure query execution time (microtime())
- Return view: monitor.index
```

### LogController - **KUK J.620100.045.01, J.620100.019.02**

```php
index(Request $request):
- Read laravel.log dengan file() function
- Filter by keyword dan log level (ERROR, WARNING, INFO)
- Paginate manual (array_slice)
- Algorithm: loop + string search (stripos)
- Return view: logs.index
```

### AboutController - **KUK J.620100.003.01, J.620100.019.02**

```php
index():
- Get Laravel version (app()->version())
- Parse composer.json dengan json_decode(file_get_contents())
- List all dependencies dari require & require-dev
- Show PHP extensions (get_loaded_extensions())
- Return view: about.index
```

---

## 8. VALIDATION RULES - **KUK J.620100.023.02**

Laravel validation menggunakan `$request->validate()` atau Form Request classes. Jika validation gagal, Laravel otomatis redirect back dengan `$errors` bag.

### Login Validation

```php
// In AuthController::login() - inline validation
$credentials = $request->validate([
    'email'    => 'required|email',
    'password' => 'required|string|min:6',
]);
```

### Register Validation (RegisterRequest)

```php
<?php
// app/Http/Requests/RegisterRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;  // Allow all guests to register
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'nullable|string|max:20',
            'role'              => 'required|in:USER,SELLER',
            'password'          => 'required|string|min:6|confirmed',

            // Seller-specific rules (required_if)
            'store_name'        => 'required_if:role,SELLER|nullable|string|max:255',
            'store_description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'Nama lengkap wajib diisi.',
            'email.required'             => 'Email wajib diisi.',
            'email.email'                => 'Format email tidak valid.',
            'email.unique'               => 'Email sudah terdaftar.',
            'role.required'              => 'Pilih role terlebih dahulu.',
            'role.in'                    => 'Role tidak valid.',
            'password.required'          => 'Password wajib diisi.',
            'password.min'               => 'Password minimal 6 karakter.',
            'password.confirmed'         => 'Konfirmasi password tidak cocok.',
            'store_name.required_if'     => 'Nama toko wajib diisi untuk seller.',
        ];
    }
}
```

### Product Validation (ProductRequest)

```php
'name' => 'required|string|max:255|unique:products,name,'.$id,
'category_id' => 'required|exists:categories,id',
'description' => 'required|string|min:50',
'price' => 'required|numeric|min:0',
'stock' => 'required|integer|min:0',
'material' => 'nullable|string|max:100',
'color' => 'nullable|string|max:50',
'dimensions' => 'nullable|string|max:100',
'weight' => 'nullable|numeric|min:0',
'warranty_period' => 'nullable|integer|min:0',
'images' => 'nullable|array|max:5',
'images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
```

**Validation Rules yang Umum Digunakan:**

```php
// KUK J.620100.023.02 - Macam-macam validation rules Laravel

'required'             // Tidak boleh kosong
'required_if:field,value'  // Wajib jika field lain = value
'nullable'             // Boleh null/kosong
'email'                // Format email valid
'unique:table,column'  // Unique di database
'unique:table,column,'.$id  // Unique kecuali record ini (untuk update)
'exists:table,column'  // Harus ada di database (foreign key check)
'min:6'                // Minimal 6 (string = chars, numeric = value)
'max:255'              // Maksimal 255
'confirmed'            // Harus ada field `{nama}_confirmation` yang sama
'in:value1,value2'     // Nilai harus salah satu dari list
'numeric'              // Harus angka
'integer'              // Harus integer
'string'               // Harus string
'date'                 // Format tanggal valid
'image'                // Harus file image
'mimes:jpg,png'        // Tipe file tertentu
'array'                // Harus array
```

---

### Category Validation (CategoryRequest)

```php
'name' => 'required|string|max:255|unique:categories,name,'.$id,
'description' => 'nullable|string',
'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
'is_active' => 'boolean',
```

### Checkout Validation (CheckoutRequest)

```php
'shipping_name' => 'required|string|max:255',
'shipping_phone' => 'required|string|max:20',
'shipping_address' => 'required|string',
'shipping_city' => 'required|string|max:100',
'shipping_province' => 'required|string|max:100',
'shipping_postal_code' => 'required|string|max:10',
'notes' => 'nullable|string|max:500',

// Additional views untuk KUK J.620100.045.01
├── monitor.blade.php            (system info, memory, storage, PHP version)
├── logs.blade.php               (laravel.log viewer dengan filter)
└── about.blade.php              (app info, dependencies dari composer.json)
```

---

## 9. MIDDLEWARE - **KUK J.620100.017.02 (Pemrograman Terstruktur)**

### Create Custom Middleware: CheckRole

Middleware melindu ngi route dari unauthorized access. Gunakan untuk enforce role-based access control.

```php
<?php
// app/Http/Middleware/CheckRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check user role middleware
 * KUK J.620100.017.02 (Middleware dalam MVC)
 * KUK J.620100.027.02 (Auth check)
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  (variadic parameter - multiple roles allowed)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // AUTH CHECK — KUK J.620100.027.02
        if (!auth()->check()) {
            return redirect()->route('login')
                             ->with('error', 'Silakan login terlebih dahulu.');
        }

        // ROLE CHECK — KUK J.620100.022.01 (Algoritma: cek apakah role ada dalam array)
        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
```

**Register Middleware di bootstrap/app.php (Laravel 11)**

```php
<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware alias
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Redirect guests to login (untuk middleware 'auth')
        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

**Penggunaan Middleware di Routes**

```php
// routes/web.php

// Seller routes - hanya SELLER atau ADMIN yang bisa akses
Route::middleware(['auth', 'role:SELLER,ADMIN'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::resource('products', ProductController::class);
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });

// Admin routes - hanya ADMIN
Route::middleware(['auth', 'role:ADMIN'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);
        Route::resource('products', AdminProductController::class);

        // System monitoring - KUK J.620100.045.01
        Route::get('/monitor', [MonitorController::class, 'index'])->name('monitor');
        Route::get('/logs', [LogController::class, 'index'])->name('logs');
        Route::get('/about', [AboutController::class, 'index'])->name('about');
    });
```

---

## 10. VIEWS STRUCTURE

```
resources/views/
├── layouts/
│   ├── app.blade.php           (main layout dengan navbar, footer)
│   ├── admin.blade.php         (admin layout dengan sidebar)
│   ├── dashboard.blade.php     (seller dashboard layout)
│   └── guest.blade.php         (layout untuk login/register)
│
├── components/
│   ├── navbar.blade.php
│   ├── footer.blade.php
│   ├── product-card.blade.php  (reusable product card)
│   ├── sidebar-admin.blade.php
│   └── alert.blade.php         (flash messages - KUK J.620100.044.01)
│
├── auth/                        (manual auth views - KUK J.620100.027.02)
│   ├── login.blade.php         (login form)
│   └── register.blade.php      (register form)
│
├── pages/
│   ├── home.blade.php
│   ├── products/
│   │   ├── index.blade.php     (product listing)
│   │   ├── show.blade.php      (product detail)
│   │   └── search.blade.php
│   ├── cart.blade.php
│   ├── checkout.blade.php
│   ├── order-success.blade.php
│   ├── orders/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   └── profile.blade.php
│
├── dashboard/                   (seller views)
│   ├── index.blade.php
│   ├── products/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── orders/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   └── settings.blade.php
│
└── admin/
    ├── dashboard.blade.php
    ├── categories/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── users/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── products/
    │   ├── index.blade.php
    │   └── edit.blade.php
    └── orders/
        ├── index.blade.php
        └── show.blade.php
```

---

## 10A. AUTH VIEWS IMPLEMENTATION - **KUK J.620100.027.02, J.620100.023.02, J.620100.044.01**

### Login Form (resources/views/auth/login.blade.php)

```blade
@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mt-5">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4">Login</h3>

                    {{-- Flash Messages - KUK J.620100.044.01 --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Validation Errors - KUK J.620100.023.02 --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat Saya</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </button>

                        <div class="text-center">
                            <p class="text-muted">Belum punya akun?
                                <a href="{{ route('register') }}" class="text-decoration-none">Register</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Register Form (resources/views/auth/register.blade.php)

Includes role selection (USER/SELLER) dan conditional seller fields.

**CATATAN:** Implementasi lengkap login/register form ada di file akhir. Form harus include:

- CSRF token (`@csrf`) - **KUK J.620100.023.02**
- Validation error display (`@error`) - **KUK J.620100.023.02**
- Flash messages (`session('success')`) - **KUK J.620100.044.01**
- Bootstrap 5 styling - **KUK J.620100.003.01**
- FontAwesome icons - **KUK J.620100.003.01**

---

## 11. SEEDERS

### DatabaseSeeder

```php
$this->call([
    CategorySeeder::class,
    UserSeeder::class,
    ProductSeeder::class,
]);
```

### CategorySeeder

Buat 8 categories:

1. Living Room (Sofa, Coffee Table, TV Stand, Rug)
2. Bedroom (Bed, Wardrobe, Nightstand, Dresser)
3. Dining Room (Dining Table, Dining Chair, Buffet, Bar Cart)
4. Office (Desk, Office Chair, Bookshelf, Filing Cabinet)
5. Outdoor (Patio Set, Garden Bench, Outdoor Table)
6. Storage (Cabinet, Shelving Unit, Chest)
7. Kids Room (Kids Bed, Study Desk, Toy Storage)
8. Lighting (Floor Lamp, Table Lamp, Chandelier)

Each dengan slug, description, dan placeholder image path.

### UserSeeder

Buat users:

````php
// Admin
- name: Administrator
- email: admin@furnishop.com
- password: password
- role: ADMIN

// Seller 1
- name: Jepara Furniture
- email: seller1@furnishop.com
- password: password - **KUK J.620100.019.02**

Create `app/Helpers/helpers.php`:

```php
<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// PHP Built-in function: number_format() — KUK J.620100.019.02
if (!function_exists('format_rupiah')) {
    function format_rupiah($amount): string
    {
        return 'Rp ' . number_format($amount ?? 0, 0, ',', '.');
    }
}

// String manipulation dengan Laravel Str — KUK J.620100.003.01
if (!function_exists('generate_order_number')) {
    function generate_order_number(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }
}

// File handling — KUK J.620100.030.02
if (!function_exists('product_image_url')) {
    function product_image_url(?string $path): string
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }
        return 'https://placehold.co/400x400/e9ecef/495057?text=No+Image';
    }
}

// PHP Built-in: memory_get_usage() — KUK J.620100.019.02
if (!function_exists('get_memory_usage')) {
    function get_memory_usage(): array
    {
        return [
            'current' => round(memory_get_usage(true) / 1024 / 1024, 2),  // MB
            'peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2), // MB
        ];
    }
}

// PHP Built-in: microtime() — KUK J.620100.019.02
if (!function_exists('measure_execution_time')) {
    function measure_execution_time(callable $callback): array
    {
        $start = microtime(true);
        $result = $callback();
        $elapsed = round((microtime(true) - $start) * 1000, 2);  // milliseconds

        return [
            'result' => $result,
            'time_ms' => $elapsed,
        ];
    }
}

// PHP Built-in: RecursiveDirectoryIterator — KUK J.620100.019.02
if (!function_exists('get_directory_size')) {
    function get_directory_size(string $path): float
    {
        if (!file_exists($path)) return 0;

        $size = 0;
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        } catch (Exception $e) {
            return 0;
        }

        return round($size / 1024, 1);  // KB
    }
}
````

Register di `composer.json`:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    },
    "files": [
        "app/Helpers/helpers.php"
    ]
}
```

**Setelah edit composer.json, run:** `composer dump-autoloadaddress: Jl. Merdeka No. 100

- city: Bandung
- province: Jawa Barat

````

### ProductSeeder

Buat minimum 20 products (distributed across sellers):

**Seller 1 (Jepara Furniture) - 8 products:**

1. Sofa Minimalis Kayu Jati (Living Room) - Rp 8,500,000
2. Meja Makan Jati 6 Kursi (Dining Room) - Rp 12,000,000
3. Lemari Pakaian 4 Pintu Jati (Bedroom) - Rp 15,000,000
4. Meja Belajar Anak Jati (Kids Room) - Rp 3,500,000
5. Rak Buku Minimalis Jati (Office) - Rp 4,200,000
6. Tempat Tidur Ukir Jati King Size (Bedroom) - Rp 18,000,000
7. Meja TV Minimalis Jati (Living Room) - Rp 5,500,000
8. Kursi Tamu Jati Set (Living Room) - Rp 9,000,000

**Seller 2 (Modern Living) - 7 products:**

1. Sofa L Modern Grey (Living Room) - Rp 6,500,000
2. Meja Kerja Standing Desk (Office) - Rp 4,800,000
3. Lemari Sliding Modern (Bedroom) - Rp 9,500,000
4. Rak Dinding Minimalis (Storage) - Rp 1,200,000
5. Kursi Gaming Ergonomis (Office) - Rp 3,200,000
6. Coffee Table Marble (Living Room) - Rp 2,800,000
7. Buffet TV Modern (Living Room) - Rp 5,200,000

**Seller 3 (Indo Rotan) - 5 products:**

1. Kursi Rotan Sintetis Outdoor (Outdoor) - Rp 2,500,000
2. Meja Rotan Bulat (Dining Room) - Rp 3,800,000
3. Keranjang Penyimpanan Rotan (Storage) - Rp 450,000
4. Ayunan Rotan Garden (Outdoor) - Rp 5,500,000
5. Kursi Santai Rotan (Living Room) - Rp 3,200,000

Each product harus include:

- name, slug, description (min 100 chars)
- price, stock (random 5-50)
- material (Kayu Jati/Metal/Rotan/Fabric/etc)
- color (Natural/White/Black/Grey/Brown/etc)
- dimensions (e.g., "200x100x80 cm")
- weight (random 10-150 kg)
- warranty_period (12 atau 24 bulan)
- is_featured (random 30% products)
- 3-4 product_images each (bisa placeholder paths)

---

## 12. HELPER FUNCTIONS

Create `app/Helpers/helpers.php`:

```php
if (!function_exists('format_rupiah')) {
    function format_rupiah($amount) {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('generate_order_number')) {
    function generate_order_number() {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }
}

if (!function_exists('product_image_url')) {
    function product_image_url($path) {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }
        return asset('images/placeholder-product.jpg');
    }
}
````

Register di `composer.json`:

```json
"autoload": {
    "files": [
        "app/Helpers/helpers.php"
    ]
}
```

---

## 13. DASHBOARD STATISTICS

### Admin Dashboard

Show:

- Total Products (count)
- Total Users (count by role)
- Total Orders (count by status)
- Total Revenue (sum of completed orders)
- Recent Orders Table (latest 10)
- Top Selling Products (top 5 by order count)

### Seller Dashboard

Show:

- My Products Count
- Total Sales (completed orders)
- Revenue This Month
- Pending Orders Count
- Recent Orders Table (latest 10 for this seller)
- Top Selling Products (seller's top 5)

---

## 14. ADDITIONAL FEATURES

### Search Functionality

- Search bar di navbar
- Search by product name, description, material
- Filter by category, price range
- Sort options

### Product Listing Features

- Pagination (12 products per page)
- Grid/List view toggle
- Filter sidebar:
    - Category checkbox
    - Price range slider
    - Material checkbox
    - Color checkbox
- Sort dropdown (Newest, Price Low-High, Price High-Low, Most Popular)

### Product Detail Page

- Image gallery dengan zoom on hover
- Product specifications table
- Related products carousel
- Add to cart button dengan quantity selector
- Stock availability indicator
- Seller info card dengan link ke seller's products

### Cart Features

- Update quantity dengan +/- buttons
- Remove item
- Show subtotal per item
- Show grand total
- Empty cart state dengan CTA to products
- Continue shopping button

### Order Management

- Order status badge dengan colors
- Order timeline/tracking
- Print order button (print-friendly view)
- Seller can update status: PROCESSING → SHIPPED → DELIVERED
- Buyer can cancel order jika masih PENDING

---

## 15. UI/UX REQUIREMENTS

### Design Style

- Modern, clean, minimalist
- Bootstrap 5 components
- Color scheme: Primary #2C3E50, Secondary #E67E22, Success #27AE60
- Responsive design (mobile, tablet, desktop)
- Font: Inter atau Poppins (Google Fonts)

### Key Pages Layout

**Homepage:**

- Hero section dengan jumbotron + CTA
- Featured Products grid (8 items)
- Categories grid dengan images (8 categories)
- Testimonials/Features section
- Newsletter subscribe (optional)

**Product Listing:**

- Breadcrumb navigation
- Filter sidebar (collapsible on mobile)
- Product grid (3-4 cols desktop, 2 cols tablet, 1 col mobile)
- Pagination at bottom

**Product Detail:**

- 2-column layout (images left, info right)
- Image gallery dengan thumbnails
- Product info: name, price, stock, rating (optional)
- Specifications table
- Add to cart form
- Related products section

**Admin Panel:**

- Sidebar navigation (fixed)
- Top navbar dengan user dropdown
- Content area dengan breadcrumb
- DataTables untuk listings
- Form layouts dengan proper spacing

---

## 16. FILE UPLOAD HANDLING

### Product Images

- Store in: `storage/app/public/products/`
- Max 5 images per product
- Max size: 2MB per image
- Allowed: jpg, jpeg, png
- Resize to max 1200x1200px (maintain aspect ratio)
- Create thumbnail 300x300px
- Filename: `{product_id}_{timestamp}_{random}.jpg`

### Category Images

- Store in: `storage/app/public/categories/`
- Max size: 2MB
- Resize to 800x800px

### User Avatars

- Store in: `storage/app/public/avatars/`
- Max size: 1MB
- Resize to 300x300px circle crop

**Don't forget:** `php artisan storage:link`

---

## 17. SETUP INSTRUCTIONS

Create comprehensive README.md dengan:

1. Requirements (PHP 8.2+, Composer, MySQL, Node.js)
2. Installation steps:
    ```bash
    composer install
    npm install && npm run build
    cp .env.example .env
    phpIMPLEMENTASI KUK (KRITERIA UNJUK KERJA) - **WAJIB!**
    ```

Pastikan SEMUA KUK berikut ter-implementasi dengan jelas dalam code:

### KUK J.620100.003.01 - Library/Framework

**Implementasi:**

1. **Laravel 11** - Framework MVC utama
2. **Bootstrap 5** - UI framework (link via CDN di layout)
3. **FontAwesome 6** - Icon library (link via CDN)
4. **Yajra DataTables** - Package untuk admin tables
5. **PHPUnit 11** - Testing framework (built-in Laravel 11)

**Contoh di code:**

```blade
{{-- resources/views/layouts/app.blade.php --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
```

```php
// composer.json dependencies
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.0",
        "yajra/laravel-datatables": "^11.0"
    },
    "require-dev": {
        "phpunit/phpunit": "^11.0"
    }
}
```

### KUK J.620100.017.02 - MVC Pattern

**Implementasi:**

- **Model**: `app/Models/Product.php` (Eloquent ORM)
- **View**: `resources/views/products/index.blade.php` (Blade template)
- **Controller**: `app/Http/Controllers/ProductController.php`
- **Routes**: `routes/web.php` (routing definition)

**Contoh:**

```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
});

// Controller
public function index(Request $request)
{
    $products = Product::query()->paginate(12);  // MODEL
    return view('products.index', compact('products'));  // VIEW
}
```

### KUK J.620100.018.02 - OOP (Encapsulation, Inheritance, Polymorphism)

**Implementasi:**

1. **Encapsulation**: Protected `$fillable`, private properties
2. **Inheritance**: `class Product extends Model`
3. **Polymorphism**: Interface/method override
4. **Abstraction**: Service pattern, Facade pattern
5. **Dependency Injection**: Controller constructor

**Contoh:**

```php
// INHERITANCE
class Product extends Model { }  // extends Eloquent Model

// ENCAPSULATION - protected properties
protected $fillable = ['name', 'price'];
protected $casts = ['price' => 'decimal:2'];

// DEPENDENCY INJECTION
public function __construct(private ProductService $productService) {}

// ABSTRACTION - Service layer
class ProductService {
    public function store(array $data, ?UploadedFile $image): Product { }
}
```

### KUK J.620100.019.02 - PHP Built-in Functions

**Implementasi - WAJIB gunakan:**

1. `PHP_VERSION` - konstanta
2. `memory_get_usage()` - memory monitoring
3. `memory_get_peak_usage()` - peak memory
4. `ini_get()` - PHP configuration
5. `microtime()` - execution time measurement
6. `file()` - read file to array
7. `json_decode()` - parse JSON
8. `number_format()` - format numbers
9. `RecursiveDirectoryIterator` - folder traversal

**Lokasi implementasi:**

- `MonitorController@index` - memory, microtime, RecursiveDirectoryIterator
- `LogController@index` - file()
- `AboutController@index` - json_decode(), ini_get()
- `helpers.php` - number_format()

### KUK J.620100.020.02 - SQL

**Implementasi - SQL yang dihasilkan Eloquent:**

```php
// SELECT dengan WHERE LIKE
Product::where('name', 'like', "%laptop%")->get();
// SQL: SELECT * FROM products WHERE name LIKE '%laptop%'

// ORDER BY
Product::orderBy('price', 'desc')->get();
// SQL: SELECT * FROM products ORDER BY price DESC

// LIMIT OFFSET (pagination)
Product::paginate(12);
// SQL: SELECT * FROM products LIMIT 12 OFFSET 0

// JOIN via eager loading
Order::with('orderItems')->get();
// SQL: SELECT * FROM orders
//      SELECT * FROM order_items WHERE order_id IN (...)

// INSERT
Product::create([...]);
// SQL: INSERT INTO products (...) VALUES (...)

// UPDATE
$product->update(['price' => 15000]);
// SQL: UPDATE products SET price = 15000 WHERE id = ?

// DELETE CASCADE (via foreign key)
$order->delete();
// SQL: DELETE FROM orders WHERE id = ?
//      DELETE FROM order_items WHERE order_id = ? (CASCADE)

// AGGREGATION
DB::table('order_items')->sum('subtotal');
// SQL: SELECT SUM(subtotal) FROM order_items
```

### KUK J.620100.021.02 - Eloquent ORM

**Implementasi:**

1. **Route Model Binding** - auto fetch by ID
2. **Relationships** - hasMany, belongsTo, hasOne
3. **Eager Loading** - prevent N+1 queries
4. **Query Scopes** - reusable query methods
5. **Accessors** - transform attributes

**Contoh:**

```php
// Route Model Binding — auto SELECT by ID dari URL
public function show(Product $product) { }  // auto fetch

// Relationships
public function orderItems() {
    return $this->hasMany(OrderItem::class);
}

// Eager Loading (2 queries instead of N+1)
$orders = Order::with('orderItems')->get();

// Query Scopes
public function scopeActive($query) {
    return $query->where('is_active', true);
}
// Usage: Product::active()->get();

// Accessor
public function getFormattedPriceAttribute(): string {
    return format_rupiah($this->price);
}
```

### KUK J.620100.022.01 - Algoritma

**Implementasi - WAJIB ada:**

1. **Search/Filter** - WHERE LIKE dengan keyword
2. **Sort** - ORDER BY dengan multiple columns
3. **Pagination** - LIMIT/OFFSET calculation
4. **Kalkulasi numerik** - total = price × qty
5. **Agregasi** - SUM, COUNT, AVG
6. **Loop & kondisi** - foreach, if-else logic
7. **Rekursi** - folder size calculation

**Contoh:**

```php
// 1. SEARCH ALGORITHM
$query = Product::query();
if ($search = $request->get('search')) {
    $query->where('name', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%");
}

// 2. SORT ALGORITHM
$sort = $request->get('sort', 'id');
$dir = $request->get('dir', 'asc');
$query->orderBy($sort, $dir);

// 3. PAGINATION ALGORITHM
$products = $query->paginate(12);  // LIMIT 12 OFFSET (page-1)*12

// 4. KALKULASI
$subtotal = $item->price * $item->quantity;
$grandTotal = $items->sum('subtotal');

// 5. AGREGASI
$totalRevenue = Order::where('status', 'DELIVERED')->sum('total_price');
$totalOrders = Order::count();

// 6. REKURSI (folder size)
private function getDirSize(string $path): float {
    $size = 0;
    foreach (new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path)
    ) as $file) {
        if ($file->isFile()) $size += $file->getSize();
    }
    return $size;
}
```

### KUK J.620100.024.02 - Database Migration

**Implementasi:**

```php
// Migrasi dengan foreign keys & cascade
Schema::create('order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained();
    $table->decimal('price', 12, 2);
    $table->integer('quantity');
    $table->decimal('subtotal', 12, 2);
    $table->timestamps();
});

// Run migrations
// php artisan migrate
// php artisan migrate:fresh --seed
```

### KUK J.620100.030.02 - Upload File/Multimedia

**Implementasi:**

1. Validation (image, mimes, max size)
2. Storage facade
3. Delete old file on update
4. Symlink public/storage
5. Accessor untuk URL

**Contoh:**

```php
// Validation
'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'

// Upload
if ($image = $request->file('image')) {
    $path = $image->store('products', 'public');
    // Saved to: storage/app/public/products/randomname.jpg
}

// Delete old
if ($product->image) {
    Storage::disk('public')->delete($product->image);
}

// Accessor untuk URL
public function getImageUrlAttribute(): string {
    return $this->image
        ? asset('storage/' . $this->image)
        : 'https://placehold.co/400x400?text=No+Image';
}
```

### KUK J.620100.032.02 - Code Review & Best Practices

**Implementasi:**

1. Follow PSR-12 coding standards
2. Type hints pada method parameters & return
3. Proper naming conventions (camelCase, PascalCase, snake_case)
4. DRY principle (Don't Repeat Yourself) → use Service classes
5. SOLID principles
6. Comments pada complex logic
7. Use Laravel built-in helpers
8. Avoid N+1 queries (eager loading)

### KUK J.620100.036.01 - Testing (PHPUnit)

**Implementasi - minimal Feature Tests:**

```php
// tests/Feature/ProductTest.php
class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_products_page()
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    public function test_can_create_product()
    {
        $user = User::factory()->create(['role' => 'SELLER']);

        $this->actingAs($user)->post('/dashboard/products', [
            'name' => 'Test Product',
            'category_id' => 1,
            'price' => 100000,
            'stock' => 10,
            'description' => 'Test description...',
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product'
        ]);
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}
```

### KUK J.620100.044.01 - Alert/Flash Messages

**Implementasi:**

```php
// Controller
return redirect()->route('products.index')
                 ->with('success', 'Produk berhasil ditambahkan!');

// Blade template
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

### KUK J.620100.045.01 - Monitoring

**Implementasi - buat 3 halaman:**

**1. /monitor - System Monitor**

```php
public function index()
{
    return view('monitor', [
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'memory_current' => round(memory_get_usage(true) / 1024 / 1024, 2),
        'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'storage_size' => get_directory_size(storage_path('app/public')),
        'db_size' => $this->getDatabaseSize(),
    ]);
}
```

**2. /logs - Log Viewer**

```php
public function index(Request $request)
{
    $logFile = storage_path('logs/laravel.log');
    $lines = file_exists($logFile) ? file($logFile, FILE_IGNORE_NEW_LINES) : [];

    // Filter by keyword & level
    if ($filter = $request->get('filter')) {
        $lines = array_filter($lines, fn($line) =>
            stripos($line, $filter) !== false
        );
    }

    $lines = array_slice(array_reverse($lines), 0, 200);  // latest 200

    return view('logs', compact('lines'));
}
```

**3. /about - Application Info**

```php
public function index()
{
    $composerJson = json_decode(
        file_get_contents(base_path('composer.json')),
        true
    );

    return view('about', [
        'app_name' => config('app.name'),
        'laravel_version' => app()->version(),
        'php_version' => PHP_VERSION,
        'dependencies' => $composerJson['require'] ?? [],
        'dev_dependencies' => $composerJson['require-dev'] ?? [],
        'extensions' => get_loaded_extensions(),
    ]);
}
```

### KUK J.620100.047.01 - Pembaruan Program Logic

**Implementasi - Update Stock Otomatis:**

```php
// Saat checkout - decrease stock
foreach ($cartItems as $item) {
    $product = $item->product;
    $product->decrement('stock', $item->quantity);
    // atau: $product->stock -= $item->quantity; $product->save();
}

// Saat order cancelled - restore stock
foreach ($order->orderItems as $item) {
    $product = $item->product;
    $product->increment('stock', $item->quantity);
}
```

---

## 21. artisan key:generate

    php artisan migrate
    php artisan db:seed
    php artisan storage:link
    php artisan serve
    ```

3. Demo accounts table
4. Features checklist
5. Technology stack
6. Project structure
7. Troubleshooting

---

## 18. TESTING CHECKLIST

Setelah implementasi, test semua flow:

**Admin:**

- [ ] Login as admin
- [ ] Create/Edit/Delete categories
- [ ] View all products, edit/delete any product
- [ ] View all users, create/edit/delete users
- [ ] View all orders, update order status
- [ ] Dashboard statistics displayed correctly

**Seller:**

- [ ] Register dengan role SELLER
- [ ] Setup store info
- [ ] Create product dengan multiple images
- [ ] Edit own product
- [ ] Delete own product
- [ ] View own orders only
- [ ] Update order item status
- [ ] Dashboard statistics untuk own sales

**Buyer:**

- [ ] Register as USER
- [ ] Browse products, use filters & search
- [ ] View product detail
- [ ] Add to cart, update quantity, remove from cart
- [ ] Checkout process complete
- [ ] View order history
- [ ] View order detail
- [ ] Edit profile

**General:**

- [ ] Responsive pada mobile, tablet, desktop
- [ ] Forms validation working
- [ ] Flash messages displayed
- [ ] Unauthorized access blocked (middleware)
- [ ] Images uploaded & displayed correctly
- [ ] Soft deletes working

---

## 19. BONUS FEATURES (Optional - Jika Ada Waktu)

- [ ] Product reviews & ratings
- [ ] Wishlist functionality
- [ ] Order cancellation request (buyer → seller approval)
- [ ] Export orders to Excel (admin/seller)
- [ ] Product import via CSV (admin)
- [ ] Email notifications (order created, status updated)
- [ ] Product comparison (max 3 products)
- [ ] Dark mode toggle
- [ ] Multi-language (ID/EN)

---

## 21. NOTES UNTUK AI ASSISTANT

Kamu adalah software engineer yang akan membangun aplikasi **Furniture Marketplace** berbasis Laravel 11 dari nol. Generate semua file yang disebutkan di spesifikasi ini dengan lengkap dan pastikan **SEMUA 14 KUK (Kriteria Unjuk Kerja) ter-implementasi dengan jelas**.

### Yang HARUS Kamu Lakukan:

1. **Create Project Structure** - lengkap dengan migrations, models, controllers, views, routes, helpers, tests
2. **Implement ALL Features** - User, Seller, Admin roles dengan semua CRUD nya
3. **Implement ALL KUK Requirements** - Setiap KUK harus ada contoh implementasi yang jelas di code
4. **Ready to Run** - Project harus bisa langsung `php artisan serve` setelah setup
5. **Include Setup Commands** - Migration, seeder, storage link, dll

### KUK Implementation Checklist:

✅ **J.620100.003.01** - Library/Framework: Laravel 11, Bootstrap 5, FontAwesome 6, PHPUnit 11, DataTables  
✅ **J.620100.017.02** - MVC Pattern: Routes → Controllers → Models → Views  
✅ **J.620100.018.02** - OOP: Inheritance (extends Model), Encapsulation ($fillable), DI (Service)  
✅ **J.620100.019.02** - PHP Built-in: memory_get_usage(), microtime(), file(), json_decode(), RecursiveDirectoryIterator  
✅ **J.620100.020.02** - SQL: Generated by Eloquent (SELECT, INSERT, UPDATE, DELETE, JOIN, ORDER BY, LIMIT)  
✅ **J.620100.021.02** - Eloquent ORM: Route Model Binding, Relationships, Eager Loading, Scopes, Accessors  
✅ **J.620100.022.01** - Algoritma: Search, Sort, Pagination, Calculation, Aggregation, Recursion  
✅ **J.620100.024.02** - Database Migration: Tables with foreign keys & cascade  
✅ **J.620100.030.02** - Upload Multimedia: Image validation, storage, delete old, accessor  
✅ **J.620100.032.02** - Code Review: PSR-12, type hints, naming conventions, DRY, SOLID  
✅ **J.620100.036.01** - Testing: PHPUnit feature tests (minimum 3 tests)  
✅ **J.620100.044.01** - Alert Messages: session flash messages (success, error) + validation errors  
✅ **J.620100.045.01** - Monitoring: /monitor (system info), /logs (log viewer), /about (app info)  
✅ **J.620100.047.01** - Update Logic: Stock auto decrease saat checkout, restore saat cancel

### Implementation Priority:

1. **Database Structure First** (migrations dengan foreign keys & cascade)
2. **Core Models** (User, Category, Product, Order, OrderItem dengan relationships)
3. **Authentication** (Laravel Breeze dengan role field)
4. **User Features** (browse, kategori, detail, cart, checkout)
5. **Seller Dashboard** (CRUD produk milik seller sendiri)
6. **Admin Dashboard** (kelola kategori, kelola semua produk, kelola orders, user management)
7. **Monitoring Pages** (/monitor, /logs, /about untuk KUK monitoring)
8. **Testing** (minimal 3 feature tests untuk KUK testing)
9. **Seeders** (Admin, Seller demo, Categories, Products)
10. **Helper Functions** (format_rupiah, dll)

### Code Quality Standards:

- **PSR-12 Coding Standards** - proper indentation, spacing, naming
- **Type Hints** - semua method parameters & return types
- **DRY Principle** - no duplicate code, use Service classes
- **Eager Loading** - prevent N+1 queries dengan `with()`
- **Route Model Binding** - auto fetch models by ID
- **Form Requests** - validation classes terpisah
- **Comments** - on complex algorithms & business logic
- **Security** - middleware, CSRF, authorization policies

### UI/UX Requirements:

- **Bootstrap 5** - responsive grid system
- **FontAwesome 6** - icons untuk visual enhancement
- **DataTables** - for admin product/order tables
- **Alert Messages** - success (green), error (red), validation errors (list)
- **Mobile Responsive** - min-width breakpoints (sm, md, lg, xl)
- **Confirmation Dialog** - untuk delete actions

### Testing Requirements:

Create **tests/Feature/**:

- `ProductTest.php` - test view products, create product, auth required
- `CategoryTest.php` - test CRUD kategori hanya by admin
- `CheckoutTest.php` - test checkout flow, order creation

Run: `php artisan test`

### Final Deliverables:

1. **All Laravel Files** - routes, controllers, models, views, migrations, seeders, tests
2. **README.md** - Setup instructions dengan semua commands
3. **SETUP.md** - Quick start guide 5 menit
4. **.env.example** - Database config template
5. **helpers.php** - Registered in composer.json autoload.files
6. **Demo Accounts** - Admin, Seller (2), Buyer (2) via seeder

### Setup Commands to Include in README:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

### Demo Data via Seeder:

**Users:**

- Admin: admin@furniture.com / password
- Seller 1: jepara@furniture.com / password (Jepara Furniture Co.)
- Seller 2: modern@furniture.com / password (Modern Living)
- Buyer 1: buyer@example.com / password
- Buyer 2: customer@example.com / password

**Categories:** Kursi, Meja, Lemari, Sofa, Tempat Tidur, Rak Buku (minimal 6)

**Products:** Minimal 15 produk furniture dengan:

- Nama realistis (contoh: "Kursi Kayu Jati Ukir Jepara")
- Harga realistis (Rp 500.000 - Rp 15.000.000)
- Stock (10-50)
- Deskripsi lengkap (3-5 kalimat)
- Mix antara Seller 1 & Seller 2
- Gambar placeholder dari placehold.co

### Remember:

- **ALL KUK must be visible** - jangan hidden di abstraction
- **Code harus demonstratif** - untuk ujikom certification
- **Comments pada KUK implementation** - jelaskan KUK mana yang dipenuhi
- **Working application** - no bugs, siap run

**Mulai dengan migrations → models → seeders → controllers → views → tests.**

---

## 22. DELIVERABLES CHECKLIST

Sebelum submit, pastikan semua file ini ada:

### Database:

- [ ] `database/migrations/*_create_users_table.php` (tambah role field)
- [ ] `database/migrations/*_create_categories_table.php`
- [ ] `database/migrations/*_create_products_table.php`
- [ ] `database/migrations/*_create_orders_table.php`
- [ ] `database/migrations/*_create_order_items_table.php`
- [ ] `database/seeders/DatabaseSeeder.php`
- [ ] `database/seeders/UserSeeder.php`
- [ ] `database/seeders/CategorySeeder.php`
- [ ] `database/seeders/ProductSeeder.php`

### Models:

- [ ] `app/Models/User.php` (dengan role property)
- [ ] `app/Models/Category.php`
- [ ] `app/Models/Product.php` (dengan relationships)
- [ ] `app/Models/Order.php`
- [ ] `app/Models/OrderItem.php`

### Controllers:

- [ ] `app/Http/Controllers/HomeController.php`
- [ ] `app/Http/Controllers/ProductController.php`
- [ ] `app/Http/Controllers/CategoryController.php`
- [ ] `app/Http/Controllers/CartController.php`
- [ ] `app/Http/Controllers/CheckoutController.php`
- [ ] `app/Http/Controllers/MonitorController.php` (untuk KUK monitoring)
- [ ] `app/Http/Controllers/LogController.php` (untuk KUK monitoring)
- [ ] `app/Http/Controllers/AboutController.php` (untuk KUK monitoring)
- [ ] `app/Http/Controllers/Dashboard/ProductController.php` (Seller)
- [ ] `app/Http/Controllers/Dashboard/OrderController.php` (Seller)
- [ ] `app/Http/Controllers/Admin/CategoryController.php`
- [ ] `app/Http/Controllers/Admin/ProductController.php`
- [ ] `app/Http/Controllers/Admin/OrderController.php`
- [ ] `app/Http/Controllers/Admin/UserController.php`

### Form Requests (Validation):

- [ ] `app/Http/Requests/ProductRequest.php`
- [ ] `app/Http/Requests/CategoryRequest.php`
- [ ] `app/Http/Requests/CheckoutRequest.php`

### Services:

- [ ] `app/Services/ProductService.php` (untuk DI & OOP)
- [ ] `app/Services/OrderService.php` (untuk business logic)

### Middleware:

- [ ] `app/Http/Middleware/CheckRole.php` (admin, seller authorization)

### Views - Layouts:

- [ ] `resources/views/layouts/app.blade.php` (main layout dengan Bootstrap 5 + FontAwesome 6)
- [ ] `resources/views/layouts/dashboard.blade.php` (seller dashboard layout)
- [ ] `resources/views/layouts/admin.blade.php` (admin layout)

### Views - User Pages:

- [ ] `resources/views/home.blade.php` (landing page with products)
- [ ] `resources/views/products/index.blade.php` (browse all products)
- [ ] `resources/views/products/show.blade.php` (product detail)
- [ ] `resources/views/products/category.blade.php` (products by category)
- [ ] `resources/views/cart/index.blade.php` (shopping cart)
- [ ] `resources/views/checkout/index.blade.php` (checkout form)
- [ ] `resources/views/orders/index.blade.php` (my orders)
- [ ] `resources/views/orders/show.blade.php` (order detail)

### Views - Seller Dashboard:

- [ ] `resources/views/dashboard/index.blade.php` (seller stats)
- [ ] `resources/views/dashboard/products/index.blade.php` (manage my products - DataTables)
- [ ] `resources/views/dashboard/products/create.blade.php`
- [ ] `resources/views/dashboard/products/edit.blade.php`
- [ ] `resources/views/dashboard/orders/index.blade.php` (my orders)

### Views - Admin Panel:

- [ ] `resources/views/admin/index.blade.php` (admin stats)
- [ ] `resources/views/admin/categories/index.blade.php` (DataTables)
- [ ] `resources/views/admin/categories/create.blade.php`
- [ ] `resources/views/admin/categories/edit.blade.php`
- [ ] `resources/views/admin/products/index.blade.php` (kelola semua produk - DataTables)
- [ ] `resources/views/admin/orders/index.blade.php` (semua orders - DataTables)
- [ ] `resources/views/admin/users/index.blade.php` (user management)

### Views - Monitoring (KUK J.620100.045.01):

- [ ] `resources/views/monitor.blade.php` (system monitor dengan PHP_VERSION, memory, storage)
- [ ] `resources/views/logs.blade.php` (log viewer dengan file() function)
- [ ] `resources/views/about.blade.php` (app info dengan json_decode composer.json)

### Routes:

- [ ] `routes/web.php` (semua routes dengan grouping & middleware)
- [ ] `routes/auth.php` (Laravel Breeze auth routes)

### Helpers:

- [ ] `app/Helpers/helpers.php` (format_rupiah, generate_order_number, dll)
- [ ] `composer.json` autoload.files (register helpers.php)

### Tests (KUK J.620100.036.01):

- [ ] `tests/Feature/ProductTest.php` (minimal 3 test methods)
- [ ] `tests/Feature/CategoryTest.php` (minimal 2 test methods)
- [ ] `tests/Feature/CheckoutTest.php` (minimal 2 test methods)

### Config & Setup:

- [ ] `README.md` (full setup instructions)
- [ ] `SETUP.md` (quick setup 5 menit)
- [ ] `.env.example` (template dengan DB config)
- [ ] `composer.json` (dependencies sudah declare)

### Assets:

- [ ] Public folder siap untuk storage link
- [ ] Bootstrap 5 CDN link di layout
- [ ] FontAwesome 6 CDN link di layout
- [ ] DataTables CDN di admin layouts

---

**SELESAI! Pastikan semua KUK ter-cover dengan jelas. Good luck untuk ujikom! 🚀**

END OF SPECIFICATION.
