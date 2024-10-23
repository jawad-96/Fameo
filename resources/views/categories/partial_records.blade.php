<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="flat-row-title">
				<h3>Top Categories</h3>
			</div>
		</div><!-- /.cl-md-12 -->
	</div><!-- /.row -->
	<div class="row">
		@forelse($all_categories as $category)
		<div class="col-md-6 col-lg-4">
			<div class="imagebox style1 v1">
				<div class="box-image">
					<a href="#" title="">
						<img src="{{asset('front_assets/images/product/other/s07.jpg')}}" alt="">
					</a>
				</div>
				<div class="box-content">
					<div class="cat-name">
						<a href="#" title="">{{$category->name}}</a>
					</div>
					<ul class="cat-list">
						<li><a href="#" title="">HTC</a></li>
						<li><a href="#" title="">Iphone</a></li>
						<li><a href="#" title="">LG</a></li>
						<li><a href="#" title="">Microsoft</a></li>
						<li><a href="#" title="">Oppo phone</a></li>
					</ul>
					<div class="btn-more">
						<a href="#" title="">See all</a>
					</div>
				</div>
			</div>
		</div>
		@empty
		<div>
			No Record Found
		</div>
		@endforelse
		<!-- <div class="col-md-6 col-lg-4">
			<div class="imagebox style1 v1">
				<div class="box-image">
					<a href="#" title="">
						<img src="{{asset('front_assets/images/product/other/15.jpg')}}" alt="">
					</a>
				</div>
				<div class="box-content">
					<div class="cat-name">
						<a href="#" title="">Televisions</a>
					</div>
					<ul class="cat-list">
						<li><a href="#" title="">4K Ultra HD TVs</a></li>
						<li><a href="#" title="">Curved TVs</a></li>
						<li><a href="#" title="">LED & LCD TVs</a></li>
						<li><a href="#" title="">OLED TVs</a></li>
						<li><a href="#" title="">Outdoor TVs</a></li>
					</ul>
					<div class="btn-more">
						<a href="#" title="">See all</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<div class="imagebox style1 v1">
				<div class="box-image">
					<a href="#" title="">
						<img src="{{asset('front_assets/images/product/other/16.jpg')}}" alt="">
					</a>
				</div>
				<div class="box-content">
					<div class="cat-name">
						<a href="#" title="">Laptops</a>
					</div>
					<ul class="cat-list">
						<li><a href="#" title="">Computers & Tablets</a></li>
						<li><a href="#" title="">Curved TVs</a></li>
						<li><a href="#" title="">Hard Drives & Storage</a></li>
						<li><a href="#" title="">Inkjet Printers</a></li>
						<li><a href="#" title="">Laptop Accessories</a></li>
					</ul>
					<div class="btn-more">
						<a href="#" title="">See all</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<div class="imagebox style1 v1">
				<div class="box-image">
					<a href="#" title="">
						<img src="{{asset('front_assets/images/product/other/s05.jpg')}}" alt="">
					</a>
				</div>
				<div class="box-content">
					<div class="cat-name">
						<a href="#" title="">Games & Drones</a>
					</div>
					<ul class="cat-list">
						<li><a href="#" title="">Audio</a></li>
						<li><a href="#" title="">Furniture & Decor</a></li>
						<li><a href="#" title="">OLED TVs</a></li>
						<li><a href="#" title="">LG</a></li>
						<li><a href="#" title="">Headphones</a></li>
					</ul>
					<div class="btn-more">
						<a href="#" title="">See all</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<div class="imagebox style1 v1">
				<div class="box-image">
					<a href="#" title="">
						<img src="{{asset('front_assets/images/product/other/s08.jpg')}}" alt="">
					</a>
				</div>
				<div class="box-content">
					<div class="cat-name">
						<a href="#" title="">Headphones</a>
					</div>
					<ul class="cat-list">
						<li><a href="#" title="">4K Ultra HD TVs</a></li>
						<li><a href="#" title="">Curved TVs</a></li>
						<li><a href="#" title="">LED & LCD TVs</a></li>
						<li><a href="#" title="">OLED TVs</a></li>
						<li><a href="#" title="">Outdoor TVs</a></li>
					</ul>
					<div class="btn-more">
						<a href="#" title="">See all</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<div class="imagebox style1 v1">
				<div class="box-image">
					<a href="#" title="">
						<img src="{{asset('front_assets/images/product/other/14.jpg')}}" alt="">
					</a>
				</div>
				<div class="box-content">
					<div class="cat-name">
						<a href="#" title="">Tablets</a>
					</div>
					<ul class="cat-list">
						<li><a href="#" title="">Car Speakers</a></li>
						<li><a href="#" title="">Car Subwoofers</a></li>
						<li><a href="#" title="">Enclosures</a></li>
						<li><a href="#" title="">Musical Instruments</a></li>
						<li><a href="#" title="">OLED TVs</a></li>
					</ul>
					<div class="btn-more">
						<a href="#" title="">See all</a>
					</div>
				</div>
			</div>
		</div> -->
	</div><!-- /.row -->
	<div id="pagination">
		{{$all_categories->render()}}
	</div>
</div><!-- /.container -->