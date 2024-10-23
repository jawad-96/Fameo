<div class="brands-list grid">
    @forelse($brands as $brand)
        <div class="brands-item ipsotope g q w d a c b l">
            <form class="brand_tag" action="{{ url('products') }}" method="get" accept-charset="utf-8">
                <input type="hidden" name="category" value="all" class="records">
                <input type="hidden" name="records" value="10" class="records">
                <input type="hidden" name="order" value="asc">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="filter" value="true">
                <input type="hidden" name="brand" value="{{$brand->id.'-'.$brand->name}}">
                <input type="hidden" name="view-type" value="grid">

                <a href="javascript:void(0)" onclick="brandFormSubmit()" class="box-cat" title="">
                    <div class="cat-name">
                        {{$brand->name}}
                    </div>
                    <div class="numb-product">
                        {{$brand->products_count}}
                    </div>
                </a>
            </form>
        </div>
    @empty
        <div>Brands not found</div>
    @endforelse
    <div class="clearfix"></div>
</div>
