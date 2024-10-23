@extends('layouts.app')

@section('content')
<div class="lstViewd_leagues_contOut">
    <div class="autoContent">
      <div class="lstViewd_leagues_contInnr">
        <div class="lstViewd_leagues_titleHdng">
          <h2>All Items in <strong> {{ $category->category_name }} </strong></h2>
          <div class="lstViewd_bckPrev_page clearfix">
            <ul>
              <li> <a class="lstViewd_bckPrev_btn" href="{{ url('/') }}">Home </a> </li>
              <li> <a href="{{ url('leagues/' . Hashids::encode($category->id)) }}">>  League Overview  > </a> </li>
              <li> <span>All items</span> </li>
            </ul>
          </div>
        </div>
        <div class="lstViewd_leagues_contMain clearfix">
          
            <!-- Include last viewed html-->
            @include('sections.last_viewed')
            
            <!-- hidden fields-->
            <input type="hidden" id="category_id" value="{{ $category->id }}"/>
            <input type="hidden" id="pagination_limit" value="10"/>
            <input type="hidden" id="region_id" value="0"/>
            <input type="hidden" id="alphanumeric" value="all"/>
            <!-- hidden fields-->
            
          <div class="lstViewd_midContent">
            <div class="entryType_regionCont">
              <div class="entryType_region_tabsOut">
                
                  <!-- Include League Menu html-->
                  @include('sections.league_menu')        
                  @php( $alphanumericArray = ['0-9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'])
                <div class="searchRecordAlphbaticlly clearfix">
                    <ul class="alphanumeric">
                    <li><a class="select" href="javascript:void(0)" data-id="all"> All</a></li>
                    @foreach($alphanumericArray as $alphanum)
                        <li><a href="javascript:void(0)" data-id="{{ $alphanum }}">{{ $alphanum }}</a></li>
                    @endforeach
                  </ul>
                  <div class="scoreOuter clearfix">
                    <ul>
                      <li><a href="#">Score </a></li>
                      <li><a  href="#"> YES </a></li>
                      <li><a class="select" href="#"> NO</a></li>
                        
                      @foreach($regions as $region)
                        <li class="filter_region"><a href="javascript:void(0)" data-id="{{ $region->id }}"> {{ $region->code }} </a></li>
                      @endforeach
                    
                    </ul>
                  </div>
                </div>
                
                <!-- Item partial view  -->
                <div class="entryType_region_tabsShowOut" id="filter_items"></div>  
                  
              </div>
            </div>
          </div>
          
        <!-- Include right side Ads html-->
        @include('sections.ads_right')    
        
        </div>
         
        <!-- Include bottom Ads html-->
        @include('sections.ads_bottom')   
        
      </div>
    </div>
  </div>      
@endsection
                           
@section('scripts')
<script type="text/javascript"> 
    function getItems(page) {
        
      $("#filter_items").LoadingOverlay("show");
      
      var limit = $("#pagination_limit").val();
      var category_id = $("#category_id").val(); 
      var region_id = $("#region_id").val(); 
      var alphanumeric = $("#alphanumeric").val(); 
            
      $.ajax({
          type: "post",
          url: "{{ url('search-items') }}"+"?page=" + page,
          data: {category_id:category_id, region_id: region_id, alphanumeric:alphanumeric, limit: limit},
          success:function (result) {                                              
              $("#filter_items").html(result);
              $("#filter_items").LoadingOverlay("hide");
          }
      });
      }
    
    $(document).ready(function(){
       getItems(1); 
        
      //filter by alphanumeric value
      $(document).on('click', '.alphanumeric li a', function (e) {
            e.preventDefault();
            var el = $(this);
            
            $('.alphanumeric').find('li a').removeClass("select");                                     
            el.addClass("select");              
            $("#alphanumeric").val(el.data('id'));   
            
            getItems(1);            
        });
      
      //filter by region value
      $(document).on('click', '.filter_region', function (e) {
            e.preventDefault();
            $('.filter_region').find('a').removeClass("select");            
            $(this).find('a').addClass("select");            
            $("#region_id").val($(this).find('a').data('id'));            
            getItems(1);            
        });
        
       $(document).on('click', '.entryType_pagiLeft .pagination a', function (e) {
            e.preventDefault();
            getItems($(this).attr('href').split('page=')[1]);            
        });
       
       $(document).on('change', '#pagination_view', function (e) {
            e.preventDefault();
            $("#pagination_limit").val($(this).val());            
            getItems(1);            
        });              
        
    });
    
    
</script>
@endsection