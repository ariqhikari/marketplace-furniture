# 📋 Dokumentasi Proses Bisnis, Stakeholder & ERD

## FurniShop — Marketplace Furniture

---

## Deskripsi Aplikasi

**FurniShop** adalah aplikasi marketplace berbasis web untuk jual beli produk furniture secara online. Aplikasi ini menghubungkan penjual (Seller) yang memasarkan produk furniture seperti kursi, meja, lemari, dan rak dengan pembeli (Buyer) yang ingin membeli produk tersebut.

Terdapat tiga peran pengguna dalam aplikasi ini, yaitu **Admin** yang mengelola keseluruhan data, **Seller** yang mendaftarkan dan memasarkan produk, serta **Buyer** yang mencari dan membeli produk. Pembayaran dilakukan melalui **transfer bank** (BCA, BNI, BRI, Mandiri) dengan mengunggah bukti pembayaran.

Fitur utama aplikasi meliputi:

- Pendaftaran dan login pengguna (Admin, Seller, Buyer).
- Pengelolaan produk oleh Seller beserta gambar produk.
- Pencarian dan penjelajahan katalog produk.
- Keranjang belanja dan proses pemesanan.
- Pembayaran melalui transfer bank dengan unggah bukti pembayaran.
- Pelacakan status pesanan (Pending, Processing, Shipped, Delivered, Cancelled).
- Panel Admin untuk mengelola kategori, pengguna, produk, dan pesanan.

---

## 1. PROSES BISNIS

| Nomor Proses | Nama Proses                 | Ruang Lingkup                                                                                                 | Batasan                                                                                 | Input                                             | Output                                      |
| :----------: | --------------------------- | ------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | ------------------------------------------------- | ------------------------------------------- |
|  **PB-01**   | Pendaftaran & Login         | Pengguna mengisi formulir registrasi sebagai Buyer atau Seller, lalu login untuk mengakses sistem.            | Hanya dapat dilakukan oleh pengguna yang belum memiliki akun. Email harus unik.         | Data Identitas (nama, email, password, role)      | Info Akun Terdaftar, Akses sesuai Role      |
|  **PB-02**   | Pengelolaan Produk          | Seller menambah, mengedit, dan menghapus produk furniture miliknya beserta gambar produk.                     | Seller hanya dapat mengelola produk miliknya sendiri. Produk harus memiliki kategori.   | Data Produk (nama, harga, stok, kategori, gambar) | Info Produk Terpublikasi                    |
|  **PB-03**   | Pemesanan Produk            | Buyer mencari produk, menambah ke keranjang, melakukan checkout dengan memilih bank tujuan transfer.          | Buyer harus login. Stok produk akan dikurangi setelah order dibuat.                     | Data Keranjang, Bank Tujuan, Alamat Pengiriman    | Data Pesanan (order_number, status PENDING) |
|  **PB-04**   | Pembayaran                  | Buyer mengunggah bukti transfer bank setelah melakukan pembayaran.                                            | Hanya dapat dilakukan jika status pesanan masih PENDING dan belum ada bukti pembayaran. | File Gambar Bukti Transfer                        | Pesanan Terupdate (payment_proof tersimpan) |
|  **PB-05**   | Pengelolaan Pesanan         | Seller memproses pesanan (update status). Buyer dapat membatalkan pesanan PENDING atau konfirmasi penerimaan. | Seller hanya mengelola order item produk miliknya. Pembatalan hanya saat PENDING.       | Status Baru                                       | Info Pesanan Terupdate                      |
|  **PB-06**   | Pengelolaan Data oleh Admin | Admin mengelola data kategori, pengguna, produk, dan memantau seluruh pesanan.                                | Hanya Admin yang dapat mengakses halaman admin.                                         | Data Kategori, Data Pengguna                      | Info Data Terupdate                         |

---

## 2. STAKEHOLDER

| Kode Stakeholder | Nama Stakeholder | Tanggung Jawab                                   |
| :--------------: | ---------------- | ------------------------------------------------ |
|      **S1**      | Admin            | • Mengelola data kategori, pengguna, dan produk. |
|                  |                  | • Memantau dan mengelola seluruh pesanan.        |
|      **S2**      | Seller           | • Mengelola produk furniture dan gambar produk.  |
|                  |                  | • Memproses pesanan masuk (update status).       |
|      **S3**      | Buyer (User)     | • Mencari produk dan melakukan pemesanan.        |
|                  |                  | • Mengunggah bukti pembayaran transfer bank.     |
|                  |                  | • Melihat riwayat pesanan dan mengelola profil.  |

---

## 3. ALUR PROSES BISNIS

### APB-01: Pendaftaran & Login

|                         |                                                                                                                      |
| ----------------------- | -------------------------------------------------------------------------------------------------------------------- |
| **Nomor Proses**        | PB-01                                                                                                                |
| **Nama Proses**         | Pendaftaran & Login                                                                                                  |
| **Stakeholder Terkait** | S3 - Buyer (User), S2 - Seller                                                                                       |
| **Alur Proses**         | 1. Pengguna membuka halaman registrasi dan memilih role (Buyer atau Seller).                                         |
|                         | 2. Pengguna mengisi formulir registrasi (nama, email, password, konfirmasi password).                                |
|                         | 3. Sistem memvalidasi data (email unik, password minimal 8 karakter).                                                |
|                         | 4. Jika validasi gagal, sistem menampilkan pesan error dan pengguna mengisi ulang.                                   |
|                         | 5. Jika validasi berhasil, data pengguna disimpan ke database dan pengguna diarahkan ke halaman login.               |
|                         | 6. Pengguna memasukkan email dan password pada halaman login.                                                        |
|                         | 7. Sistem memverifikasi email dan password yang dimasukkan.                                                          |
|                         | 8. Jika berhasil, pengguna diarahkan ke halaman sesuai role (Buyer → Home, Seller → Dashboard, Admin → Admin Panel). |

---

### APB-02: Pengelolaan Produk

|                         |                                                                                                                                           |
| ----------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| **Nomor Proses**        | PB-02                                                                                                                                     |
| **Nama Proses**         | Pengelolaan Produk                                                                                                                        |
| **Stakeholder Terkait** | S2 - Seller                                                                                                                               |
| **Alur Proses**         | 1. Seller login dan masuk ke halaman Dashboard Produk.                                                                                    |
|                         | 2. Seller mengklik tombol "Tambah Produk" dan mengisi formulir (nama, kategori, deskripsi, harga, stok, material, warna, dimensi, berat). |
|                         | 3. Seller mengunggah satu atau lebih gambar produk.                                                                                       |
|                         | 4. Sistem memvalidasi data produk dan menyimpan ke database.                                                                              |
|                         | 5. Produk tampil di katalog publik jika status aktif dan stok tersedia.                                                                   |
|                         | 6. Seller dapat mengedit atau menghapus produk melalui halaman daftar produk miliknya.                                                    |

---

### APB-03: Pemesanan Produk

|                         |                                                                                                               |
| ----------------------- | ------------------------------------------------------------------------------------------------------------- |
| **Nomor Proses**        | PB-03                                                                                                         |
| **Nama Proses**         | Pemesanan Produk                                                                                              |
| **Stakeholder Terkait** | S3 - Buyer (User)                                                                                             |
| **Alur Proses**         | 1. Buyer mencari produk melalui halaman katalog atau fitur pencarian.                                         |
|                         | 2. Buyer melihat detail produk (harga, deskripsi, stok, gambar).                                              |
|                         | 3. Buyer menambahkan produk ke keranjang belanja dengan jumlah yang diinginkan.                               |
|                         | 4. Buyer membuka halaman keranjang, dapat mengubah jumlah atau menghapus item.                                |
|                         | 5. Buyer mengklik "Checkout" dan mengisi alamat pengiriman (nama, telepon, alamat, kota, provinsi, kode pos). |
|                         | 6. Buyer memilih bank tujuan transfer (BCA, BNI, BRI, atau Mandiri).                                          |
|                         | 7. Sistem membuat pesanan dengan status PENDING, stok produk dikurangi, dan keranjang dikosongkan.            |
|                         | 8. Buyer diarahkan ke halaman detail pesanan dengan informasi rekening bank tujuan.                           |

---

### APB-04: Pembayaran

|                         |                                                                             |
| ----------------------- | --------------------------------------------------------------------------- |
| **Nomor Proses**        | PB-04                                                                       |
| **Nama Proses**         | Pembayaran                                                                  |
| **Stakeholder Terkait** | S3 - Buyer (User)                                                           |
| **Alur Proses**         | 1. Buyer membuka halaman detail pesanan yang berstatus PENDING.             |
|                         | 2. Buyer melakukan transfer bank ke rekening tujuan sesuai total pesanan.   |
|                         | 3. Buyer mengunggah bukti transfer melalui form upload di halaman pesanan.  |
|                         | 4. Sistem memvalidasi file (harus berupa gambar, maksimal 2MB).             |
|                         | 5. Jika validasi berhasil, bukti pembayaran disimpan dan pesanan terupdate. |
|                         | 6. Seller dapat melihat bukti pembayaran dan memproses pesanan.             |

---

### APB-05: Pengelolaan Pesanan

|                         |                                                                                                     |
| ----------------------- | --------------------------------------------------------------------------------------------------- |
| **Nomor Proses**        | PB-05                                                                                               |
| **Nama Proses**         | Pengelolaan Pesanan                                                                                 |
| **Stakeholder Terkait** | S2 - Seller, S3 - Buyer (User)                                                                      |
| **Alur Proses**         | 1. Seller membuka halaman Dashboard Pesanan dan melihat daftar pesanan masuk.                       |
|                         | 2. Seller melihat detail pesanan termasuk bukti pembayaran dari buyer.                              |
|                         | 3. Seller mengupdate status dari PENDING → PROCESSING setelah memverifikasi pembayaran.             |
|                         | 4. Seller mengupdate status dari PROCESSING → SHIPPED.                                              |
|                         | 5. Buyer menerima barang dan mengkonfirmasi penerimaan, status berubah menjadi DELIVERED.           |
|                         | 6. Alternatif: Buyer dapat membatalkan pesanan jika status masih PENDING, stok produk dikembalikan. |

---

### APB-06: Pengelolaan Data oleh Admin

|                         |                                                                                                   |
| ----------------------- | ------------------------------------------------------------------------------------------------- |
| **Nomor Proses**        | PB-06                                                                                             |
| **Nama Proses**         | Pengelolaan Data oleh Admin                                                                       |
| **Stakeholder Terkait** | S1 - Admin                                                                                        |
| **Alur Proses**         | 1. Admin login dan masuk ke halaman Admin Panel.                                                  |
|                         | 2. Admin mengelola data kategori: menambah, mengedit, atau menghapus kategori produk.             |
|                         | 3. Admin mengelola data pengguna: menambah, mengedit, atau menghapus akun Buyer/Seller/Admin.     |
|                         | 4. Admin memantau seluruh produk dan dapat mengedit atau menghapus produk jika diperlukan.        |
|                         | 5. Admin memantau seluruh pesanan, melihat detail, dan mengupdate status pesanan jika diperlukan. |
|                         | 6. Admin melihat dashboard ringkasan (total produk, pesanan, pengguna, dan pendapatan).           |

---

## 4. ANALISIS DATA

### Identifikasi Data

| Nomor Data | Nama Data                     | Atribut                                                                                                                                                                                | Keterangan                                                              |
| :--------: | ----------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------- |
|    D-1     | User (Pengguna)               | id, name, email, password, phone, avatar, role, store_name, store_description, store_logo, address, city, province, postal_code                                                        | Role: USER (Buyer), SELLER, ADMIN. Field store\_\* khusus untuk Seller. |
|    D-2     | Category (Kategori)           | id, name, slug, description, image, is_active                                                                                                                                          | Kategori produk furniture (Kursi, Meja, Lemari, Rak).                   |
|    D-3     | Product (Produk)              | id, user_id, category_id, name, slug, description, price, stock, material, color, dimensions, weight, is_active                                                                        | Produk furniture milik Seller.                                          |
|    D-4     | Product Image (Gambar Produk) | id, product_id, image_path                                                                                                                                                             | Satu produk dapat memiliki banyak gambar.                               |
|    D-5     | Cart (Keranjang)              | id, user_id, product_id, quantity                                                                                                                                                      | Keranjang belanja Buyer. Satu produk satu entry per user.               |
|    D-6     | Bank                          | id, bank_name, account_number, account_holder, logo, is_active                                                                                                                         | Daftar rekening bank tujuan transfer (BCA, BNI, BRI, Mandiri).          |
|    D-7     | Order (Pesanan)               | id, order_number, user_id, bank_id, total_price, status, payment_proof, shipping_name, shipping_phone, shipping_address, shipping_city, shipping_province, shipping_postal_code, notes | Status: PENDING, PROCESSING, SHIPPED, DELIVERED, CANCELLED.             |
|    D-8     | Order Item (Item Pesanan)     | id, order_id, product_id, seller_id, quantity, price, subtotal, status                                                                                                                 | Detail item per pesanan, terhubung ke Seller dan Produk.                |

---

## 5. ERD (Entity Relationship Diagram)

### 5.1 Relasi Antar Tabel (ERD Sederhana)

```
┌──────────────────────┐       ┌──────────────────────┐       ┌──────────────────────┐
│      categories      │       │       products       │       │        users         │
├──────────────────────┤       ├──────────────────────┤       ├──────────────────────┤
│ id (PK)              │       │ id (PK)              │       │ id (PK)              │
│ name                 │◄──────│ category_id (FK)     │       │ name                 │
│ slug                 │       │ user_id (FK)─────────┼──────►│ email (unique)       │
│ description          │       │ name                 │       │ password (hash)      │
│ image                │       │ slug                 │       │ phone                │
│ is_active            │       │ description          │       │ avatar               │
│ created_at           │       │ price                │       │ role (ENUM)          │
│ updated_at           │       │ stock                │       │ store_name           │
│ deleted_at           │       │ material             │       │ store_description    │
└──────────────────────┘       │ color                │       │ store_logo           │
                               │ dimensions           │       │ address              │
┌──────────────────────┐       │ weight               │       │ city                 │
│   product_images     │       │ is_active            │       │ province             │
├──────────────────────┤       │ created_at           │       │ postal_code          │
│ id (PK)              │       │ updated_at           │       │ remember_token       │
│ product_id (FK)──────┼──────►│ deleted_at           │       │ created_at           │
│ image_path           │       └──────────────────────┘       │ updated_at           │
│ created_at           │                                      └──────────┬───────────┘
│ updated_at           │                                                 │
└──────────────────────┘                                                 │
                                                                         │
┌──────────────────────┐       ┌──────────────────────┐                  │
│        banks         │       │       orders         │                  │
├──────────────────────┤       ├──────────────────────┤                  │
│ id (PK)              │       │ id (PK)              │                  │
│ bank_name            │◄──────│ bank_id (FK)         │                  │
│ account_number       │       │ user_id (FK)─────────┼──────────────────┘
│ account_holder       │       │ order_number         │       ┌──────────────────────┐
│ logo                 │       │ total_price          │       │        carts         │
│ is_active            │       │ status (ENUM)        │       ├──────────────────────┤
│ created_at           │       │ payment_proof        │       │ id (PK)              │
│ updated_at           │       │ shipping_name        │       │ user_id (FK)────────►│ users
└──────────────────────┘       │ shipping_phone       │       │ product_id (FK)      │
                               │ shipping_address     │       │ quantity             │
                               │ shipping_city        │       │ created_at           │
                               │ shipping_province    │       │ updated_at           │
                               │ shipping_postal_code │       └──────────────────────┘
                               │ notes                │
                               │ created_at           │       ┌──────────────────────┐
                               │ updated_at           │       │    order_items       │
                               │ deleted_at           │       ├──────────────────────┤
                               └──────────┬───────────┘       │ id (PK)              │
                                          │                   │ order_id (FK)        │
                                          └──────────────────►│ product_id (FK)      │
                                                              │ seller_id (FK)──────►│ users
                                                              │ quantity             │
                                                              │ price                │
                                                              │ subtotal             │
                                                              │ status (ENUM)        │
                                                              │ created_at           │
                                                              │ updated_at           │
                                                              └──────────────────────┘
```

### 5.2 Relasi (Relationships)

| Tabel Asal  |  Relasi   | Tabel Tujuan            | Keterangan                                |
| ----------- | :-------: | ----------------------- | ----------------------------------------- |
| users       |  hasMany  | products                | Satu seller memiliki banyak produk        |
| users       |  hasMany  | orders                  | Satu buyer memiliki banyak pesanan        |
| users       |  hasMany  | carts                   | Satu user memiliki banyak item keranjang  |
| users       |  hasMany  | order_items (seller_id) | Satu seller memiliki banyak order item    |
| categories  |  hasMany  | products                | Satu kategori memiliki banyak produk      |
| products    | belongsTo | users                   | Produk dimiliki oleh satu seller          |
| products    | belongsTo | categories              | Produk termasuk dalam satu kategori       |
| products    |  hasMany  | product_images          | Satu produk memiliki banyak gambar        |
| products    |  hasMany  | order_items             | Satu produk bisa ada di banyak order item |
| carts       | belongsTo | users                   | Keranjang milik satu user                 |
| carts       | belongsTo | products                | Keranjang berisi satu produk              |
| banks       |  hasMany  | orders                  | Satu bank digunakan di banyak pesanan     |
| orders      | belongsTo | users                   | Pesanan milik satu buyer                  |
| orders      | belongsTo | banks                   | Pesanan menggunakan satu bank tujuan      |
| orders      |  hasMany  | order_items             | Satu pesanan memiliki banyak item         |
| order_items | belongsTo | orders                  | Order item milik satu pesanan             |
| order_items | belongsTo | products                | Order item berisi satu produk             |
| order_items | belongsTo | users (seller_id)       | Order item terkait satu seller            |

### 5.3 Alur Proses Utama

```
Register → User::create()        ──►  role='USER' atau 'SELLER'
Login (Auth::attempt)             ──►  cek email + password
Buyer browse produk               ──►  products.is_active = true
Buyer tambah ke keranjang          ──►  carts.user_id = auth()->id()
Buyer checkout                    ──►  orders + order_items dibuat, stok dikurangi
Buyer upload bukti bayar          ──►  orders.payment_proof = file upload
Seller update status              ──►  order_items.status = PROCESSING/SHIPPED
Buyer konfirmasi terima           ──►  order_items.status = DELIVERED
Buyer batalkan pesanan            ──►  orders.status = CANCELLED, stok dikembalikan
```

### 5.4 ENUM Values

| Field              | Values                                                       |
| ------------------ | ------------------------------------------------------------ |
| users.role         | `USER`, `SELLER`, `ADMIN`                                    |
| orders.status      | `PENDING`, `PROCESSING`, `SHIPPED`, `DELIVERED`, `CANCELLED` |
| order_items.status | `PENDING`, `PROCESSING`, `SHIPPED`, `DELIVERED`, `CANCELLED` |

---

## 6. SPESIFIKASI TEKNOLOGI

### 6.1 Teknologi yang Digunakan

| No  | Komponen           | Teknologi          | Versi |
| :-: | ------------------ | ------------------ | ----- |
|  1  | Bahasa Pemrograman | PHP                | 8.2+  |
|  2  | Framework Backend  | Laravel            | 12    |
|  3  | Database           | MySQL              | 8.x   |
|  4  | Frontend           | Bootstrap          | 5     |
|  5  | Icon               | Font Awesome       | 6     |
|  6  | Build Tool         | Vite               | 7     |
|  7  | Image Processing   | Intervention Image | 3     |
|  8  | Testing            | PHPUnit            | 11    |
|  9  | Web Server Lokal   | MAMP (Apache)      | -     |

### 6.2 Arsitektur Aplikasi

Aplikasi ini menggunakan arsitektur **MVC (Model-View-Controller)** yang merupakan pola bawaan dari framework Laravel:

| Lapisan        | Peran                                                           | Contoh pada Aplikasi                               |
| -------------- | --------------------------------------------------------------- | -------------------------------------------------- |
| **Model**      | Mengelola data dan logika bisnis, berinteraksi dengan database. | User, Product, Order, Category, Cart, Bank         |
| **View**       | Menampilkan antarmuka pengguna menggunakan template Blade.      | Halaman katalog, dashboard, form checkout          |
| **Controller** | Menerima request, memproses logika, dan mengembalikan response. | ProductController, OrderController, AuthController |

Selain MVC, aplikasi juga menerapkan:

- **Middleware** — Mengontrol akses berdasarkan role pengguna (Admin, Seller, Buyer) sebelum request masuk ke controller.
- **Eloquent ORM** — Mengelola interaksi dengan database menggunakan model PHP, tanpa menulis query SQL secara langsung.
- **Blade Templating** — Sistem template bawaan Laravel untuk membangun halaman dengan layout yang dapat digunakan ulang.
- **Migration & Seeder** — Mengelola struktur tabel database melalui kode PHP, sehingga mudah direplikasi dan dikelola versinya.

### 6.3 Alasan Pemilihan Teknologi

| Teknologi     | Alasan Pemilihan                                                                                                                                                           |
| ------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Laravel**   | Framework PHP paling populer dengan dokumentasi lengkap, komunitas besar, dan fitur bawaan seperti autentikasi, routing, ORM, dan migration yang mempercepat pengembangan. |
| **PHP**       | Bahasa pemrograman server-side yang banyak digunakan untuk pengembangan web, didukung oleh hampir semua layanan hosting.                                                   |
| **MySQL**     | Database relasional yang stabil, cepat, dan cocok untuk aplikasi e-commerce dengan banyak relasi antar tabel.                                                              |
| **Bootstrap** | Framework CSS yang menyediakan komponen siap pakai dan desain responsif, sehingga tampilan aplikasi rapi di berbagai ukuran layar.                                         |
| **Vite**      | Build tool modern yang cepat untuk mengelola aset frontend (CSS, JavaScript) selama pengembangan.                                                                          |
| **PHPUnit**   | Framework testing standar untuk PHP dan Laravel, digunakan untuk memastikan fitur aplikasi berjalan sesuai harapan.                                                        |
