{{-- Product Card Component --}}
<div class="card product-card h-100">
    <a href="{{ route('products.show', $product->slug) }}">
        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
    </a>
    <div class="card-body d-flex flex-column">
        <small class="text-muted">{{ $product->category->name ?? '' }}</small>
        <h6 class="card-title mt-1">
            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                {{ Str::limit($product->name, 40) }}
            </a>
        </h6>
        <p class="text-primary fw-bold mb-1">{{ $product->formatted_price }}</p>
        <small class="text-muted"><i class="fas fa-store me-1"></i>{{ $product->user->store_name ?? $product->user->name ?? '' }}</small>
        <div class="mt-auto pt-2">
            @if($product->stock > 0)
            <small class="text-success"><i class="fas fa-check-circle"></i> Stok: {{ $product->stock }}</small>
            @else
            <small class="text-danger"><i class="fas fa-times-circle"></i> Habis</small>
            @endif
        </div>
    </div>
</div>