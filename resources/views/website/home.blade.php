@extends('website.layouts.app')

@section('title', __('website/home.page_title'))

@section('content')
    <!-- Hero Section -->
    <div class="hero py-24 bg-base-200/50 rounded-box">
        <div class="hero-content text-center">
            <div class="max-w-md">
                <h1 class="text-5xl font-bold text-primary">{{ __('website/home.hero.title') }}</h1>
                <p class="py-6">{{ __('website/home.hero.description') }}</p>
                <button class="btn btn-primary">{{ __('website/home.hero.cta_button') }}</button>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    {{-- <div class="py-20 bg-base-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Our Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card bg-base-200 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title">Corporate Events</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                            labore.</p>
                    </div>
                </div>
                <div class="card bg-base-200 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title">Wedding Planning</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                            labore.</p>
                    </div>
                </div>
                <div class="card bg-base-200 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title">Social Gatherings</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                            labore.</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- CTA Section -->
    {{-- <div class="bg-primary text-primary-content py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Plan Your Next Event?</h2>
            <p class="mb-8">Contact us today and let's create something amazing together!</p>
            <button class="btn btn-secondary">Contact Us</button>
        </div>
    </div> --}}

    <!-- Testimonials Section -->
    {{-- <div class="py-20 bg-base-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">What Our Clients Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="card bg-base-200">
                    <div class="card-body">
                        <div class="rating mb-4">
                            <input type="radio" name="rating-1" class="mask mask-star-2 bg-warning" checked />
                            <input type="radio" name="rating-1" class="mask mask-star-2 bg-warning" checked />
                            <input type="radio" name="rating-1" class="mask mask-star-2 bg-warning" checked />
                            <input type="radio" name="rating-1" class="mask mask-star-2 bg-warning" checked />
                            <input type="radio" name="rating-1" class="mask mask-star-2 bg-warning" checked />
                        </div>
                        <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                            labore."</p>
                        <div class="card-actions justify-end">
                            <div class="font-bold">John Doe</div>
                        </div>
                    </div>
                </div>
                <div class="card bg-base-200">
                    <div class="card-body">
                        <div class="rating mb-4">
                            <input type="radio" name="rating-2" class="mask mask-star-2 bg-warning" checked />
                            <input type="radio" name="rating-2" class="mask mask-star-2 bg-warning" checked />
                            <input type="radio" name="rating-2" class="mask mask-star-2 bg-warning" checked />
                            <input type="radio" name="rating-2" class="mask mask-star-2 bg-warning" checked />
                            <input type="radio" name="rating-2" class="mask mask-star-2 bg-warning" checked />
                        </div>
                        <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                            labore."</p>
                        <div class="card-actions justify-end">
                            <div class="font-bold">Jane Smith</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
