<div class="category-area pt-80 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-12 wow fadeInDown" data-wow-delay=".25s">
                <div class="site-heading-inline">
                    <h2 class="site-title">Top Category</h2>
                    <a href="#">View More <i class="fas fa-angle-double-right"></i></a>
                </div>
            </div>
        </div>
        <div class="category-slider owl-carousel owl-theme wow fadeInUp" data-wow-delay=".25s">
            @foreach($topCategories as $category)
                <div class="category-item">
                    <a href="#">
                        <div class="category-info">
                            <div class="icon">
                                <img src="{{ asset('images/icon/' . $category->icon . '.svg') }}" alt="{{ $category->name }}">
                            </div>
                            <div class="content">
                                <h4>{{ $category->name }}</h4>
                                <p>{{ $category->products_count }} Items</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
