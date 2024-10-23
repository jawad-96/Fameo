
<div class="btn-mega"><span></span>ALL CATEGORIES</div>
<ul class="menu">
    @foreach(getCategories() as $category)
    <li>
        <a href="{{url('products?category='.$category->id.'-'.string_replace($category->name).'&view-type=grid')}}" title="" class="{{ $category->subcategories->count()>0?'dropdown':'' }}">
            <span class="menu-img">
                <img style="max-width: 30px !important;" src="{{ $category->image_url }}" alt="">
            </span>
            @if (isset($products_check))
            <span class="menu-title categoryType" id="{{$category->id.'-'.$category->name}}">{{ $category->name }}</span>
            @else
            <span>
                {{ $category->name }}
            </span>
            @endif
        </a>
        @if($category->subcategories->count()>0)
            <div class="drop-menu">
                <div class="one-third">
                    <ul>
                        @foreach($category->subcategories as $subcategory)
                            @if (isset($products_check))
                                <li class="categoryType" id="{{$subcategory->id.'-'.$subcategory->name}}">{{ $subcategory->name }}</li>
                            @else
                                <li>
                                    <a href="{{url('products?category='.$subcategory->id.'-'.string_replace($subcategory->name).'&view-type=grid')}}" title="">{{ $subcategory->name }}</a>
                                </li>
                            @endif

                        @endforeach
                    </ul>
                </div>
            </div><!-- /.drop-menu -->
        @endif
    </li>
    @endforeach
</ul><!-- /.menu -->
