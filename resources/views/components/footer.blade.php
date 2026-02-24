{{-- Footer Component --}}
<footer class="text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5><i class="fas fa-couch me-2"></i>FurniShop</h5>
                <p class="text-white-50">Marketplace furniture terpercaya untuk kebutuhan rumah Anda. Temukan furniture berkualitas dari penjual terbaik.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h6>Link Cepat</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none">Semua Produk</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h6>Kontak</h6>
                <ul class="list-unstyled text-white-50">
                    <li><i class="fas fa-envelope me-2"></i>info@furnishop.com</li>
                    <li><i class="fas fa-phone me-2"></i>+62 812-3456-7890</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i>Jakarta, Indonesia</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center text-white-50 mb-0">&copy; {{ date('Y') }} FurniShop. All rights reserved.</p>
    </div>
</footer>