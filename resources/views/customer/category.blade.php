@extends('customer.layouts.apps')
<style>
    .category-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    }
    .category-card {
        text-align: center;
        padding: 15px;
    }

</style>
@section('content')
    <section class="container">
        <h2 class="section-heading">All Product Categories</h2>

        @if ($category->count() > 0)
            <div class="category-grid">
                @foreach ($category as $cat)
                    <div class="category-card">
                        <img class="category-img" src="{{ asset($cat->image ?? 'images/default-category.png') }}" alt="{{ $cat->name }}">
                        <h3>
                            <a href="{{ route('customerCategoryProducts', $cat->id) }}">{{ $cat->name }}</a>
                        </h3>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="pagination" style="margin-top: 40px; text-align: center;">
                {{ $category->links() }}
            </div>
        @else
            <p style="text-align: center;">No categories available at the moment.</p>
        @endif
    </section>
@endsection
