
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
                                <img src="{{ asset('categories/' . $category->image) }}" alt="{{ $category->name }}">
                            </div>
                            <div class="content">
                                <h4>{{ $category->name }}</h4>
                                <p>{{  $category->category_products_count }} Items</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@section('scripts')
    <script>

        $(function () {

        	var url = "{{ url('categories/top-categories') }}";
        	var current_url = window.location.href;

	        var page_number = getParams('page', current_url);
	        if(!page_number){
	            page_number = 1;
	        }
        	// getting records from url
	        var records =  getParams('records', current_url);
	        if(!records){
	            records = 1;
	        }

        	// on browser back button
	        window.onpopstate = function() {

	            // $("body").LoadingOverlay("show");
	            var current_url = window.location.href;
	            var page_number = getParams('page', current_url);
	            if(!page_number){
		            page_number = 1;
		        }

	            // set timeout to get actual page url otherwise page number missing
	            setTimeout(function(){

	                topCategories(url+'?page='+page_number, records);

	            }, 1000);
	            $("body").LoadingOverlay("hide");
	        };

            topCategories(url+'?page='+page_number, records);

            $('body').on('click', '.pagination a', function(e) {
	            e.preventDefault();

	            var url = $(this).attr('href');
	            // alert(url);return false;

	            var current_url = window.location.href;
	            // alert(current_url);return false;

	            pageClick(url, current_url, 'categories');
	        });
        });
    </script>
@endsection

