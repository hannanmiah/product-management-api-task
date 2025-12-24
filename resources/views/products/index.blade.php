@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Products</h1>
    <form method="POST" action="{{ route('products.fetch') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary" onclick="return confirm('Fetch products from FakeStore API?')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
            </svg>
            Fetch from API
        </button>
    </form>
</div>

@if($products->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->title }}" class="product-image">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->title }}</strong>
                                    <br>
                                    <small class="text-muted">ID: {{ $product->api_product_id ?? $product->id }}</small>
                                </td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $product->category }}</span>
                                </td>
                                <td>
                                    @if($product->rating_rate)
                                        <span class="badge bg-warning text-dark">
                                            {{ number_format($product->rating_rate, 1) }}
                                        </span>
                                        <small class="text-muted">({{ $product->rating_count ?? 0 }})</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $products->links() }}
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <h3 class="text-muted">No Products Found</h3>
            <p class="text-muted">Click the "Fetch from API" button to load products from the FakeStore API.</p>
        </div>
    </div>
@endif
@endsection
