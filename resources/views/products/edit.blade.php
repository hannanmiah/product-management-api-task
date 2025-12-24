@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Edit Product</h1>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input
                            type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            id="title"
                            name="title"
                            value="{{ old('title', $product->title) }}"
                            required
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                class="form-control @error('price') is-invalid @enderror"
                                id="price"
                                name="price"
                                value="{{ old('price', $product->price) }}"
                                required
                            >
                        </div>
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select
                            class="form-select @error('category') is-invalid @enderror"
                            id="category"
                            name="category"
                            required
                        >
                            <option value="">Select a category</option>
                            <option value="electronics" {{ old('category', $product->category) === 'electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="jewelery" {{ old('category', $product->category) === 'jewelery' ? 'selected' : '' }}>Jewelery</option>
                            <option value="men's clothing" {{ old('category', $product->category) === "men's clothing" ? 'selected' : '' }}>Men's Clothing</option>
                            <option value="women's clothing" {{ old('category', $product->category) === "women's clothing" ? 'selected' : '' }}>Women's Clothing</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            rows="4"
                        >{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image URL</label>
                        <input
                            type="url"
                            class="form-control @error('image') is-invalid @enderror"
                            id="image"
                            name="image"
                            value="{{ old('image', $product->image) }}"
                        >
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        @if($product->image)
                            <label class="form-label text-muted">Current Image</label>
                            <div>
                                <img src="{{ $product->image }}" alt="{{ $product->title }}" class="img-fluid" style="max-height: 200px;">
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>

        @if($product->rating_rate)
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Rating Information</h5>
                    <p class="card-text">
                        <strong>Rate:</strong> {{ number_format($product->rating_rate, 1) }} / 5<br>
                        <strong>Count:</strong> {{ $product->rating_count ?? 0 }} reviews
                    </p>
                    <small class="text-muted">This information is from the FakeStore API and cannot be edited.</small>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
