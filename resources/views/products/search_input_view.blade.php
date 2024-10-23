<form method="get" action="{{url('products')}}">
    <input type="hidden" name="category" value="all" class="records">
    <input type="hidden" name="records" value="10" class="records">
    <input type="hidden" name="order" value="asc">
    <input type="hidden" name="page" value="1">
    <input type="hidden" name="filter" value="true">

    <div class="widget widget-price">
        <div class="widget-title">
            <h3>Price<span></span></h3>
        </div>
        <div class="widget-content">
            <p>Price</p>
            <div class="price search-filter-input">

                <input id="min_price" type="hidden" name="min_price" value="0" />
                <input id="max_price" type="hidden" name="max_price" value="500" />
                <input id="view-type" type="hidden" name="view-type" value="grid" />
                <div id="slider-range2"></div>
                <p class="show_range" style="text-align: center; margin-top: 10px;"></p>

            </div>
        </div>
    </div><!-- /.widget widget-price -->
    <div class="widget widget-brands">
        <div class="widget-title">
            <h3>Brands<span></span></h3>
        </div>
        <div class="widget-content">
{{--                <input type="text" name="brands" placeholder="Brands Search">--}}
            <ul class="box-checkbox scroll">
                @forelse($brands as $key => $brand)
                <li class="check-box">
                    <input type="checkbox" name="brand[]" id="{{'checkbox'.$key}}" value="{{$key.'-'.$brand}}">
                    <label for="{{'checkbox'.$key}}">{{$brand}} <span>(4)</span></label>
                </li>
                @empty
                <li>
                    No Records Found
                </li>
                @endforelse
                {{--<li class="check-box">
                    <input type="checkbox" id="checkbox2" name="checkbox2">
                    <label for="checkbox2">Samsung <span>(2)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="checkbox3" name="checkbox3">
                    <label for="checkbox3">HTC <span>(2)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="checkbox4" name="checkbox4">
                    <label for="checkbox4">LG <span>(2)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="checkbox5" name="checkbox5">
                    <label for="checkbox5">Dell <span>(1)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="checkbox6" name="checkbox6">
                    <label for="checkbox6">Sony <span>(3)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="checkbox7" name="checkbox7">
                    <label for="checkbox7">Bphone <span>(4)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="checkbox8" name="checkbox8">
                    <label for="checkbox8">Oppo <span>(4)</span></label>
                </li>--}}
            </ul>
        </div>
    </div><!-- /.widget widget-brands -->
    {{--<div class="widget widget-color">
        <div class="widget-title">
            <h3>Color<span></span></h3>
            <div style="height: 2px"></div>
        </div>
        <div class="widget-content">
                <input type="text" name="color" placeholder="Color Search">
            <div style="height: 5px"></div>
            <ul class="box-checkbox scroll">
                <li class="check-box">
                    <input type="checkbox" id="check1" name="color[]" value="black" class="black">
                    <label for="check1">Black <span>(4)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="check2" name="color[]" value="yellow" class="yellow">
                    <label for="check2">Yellow <span>(2)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="check3" name="color[]" value="white" class="white">
                    <label for="check3">White <span>(2)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="check4" name="color[]" value="blue" class="blue">
                    <label for="check4">Blue <span>(2)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="check5" name="color[]" value="red" class="red">
                    <label for="check5">Red <span>(1)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="check6" name="color[]" value="pink" class="pink">
                    <label for="check6">Pink <span>(3)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="check7" name="color[]" value="green" class="green">
                    <label for="check7">Green <span>(4)</span></label>
                </li>
                <li class="check-box">
                    <input type="checkbox" id="check8" name="color[]" value="gold" class="gold">
                    <label for="check8">Gold <span>(4)</span></label>
                </li>
            </ul>
        </div>
    </div>--}}
    <div class="form-box">
        <button type="submit" class="save-to-proceed" style="margin-bottom: 20px">Apply Filters</button>
    </div>
</form>
