@extends('layouts.app')

@section('content')
<div class="lstViewd_leagues_contOut">
    <div class="autoContent">
      <div class="lstViewd_leagues_contInnr">
        <div class="lstViewd_leagues_titleHdng">
          <h2>Scoreboard for <strong>{{ $category->category_name }}</strong></h2>
          <div class="lstViewd_bckPrev_page clearfix">
            <ul>
              <li> <a class="lstViewd_bckPrev_btn" href="{{ url('/') }}">Home ></a> </li>
              <li> <span>Scoreboard</span> </li>
            </ul>
          </div>
        </div>
        <div class="lstViewd_leagues_contMain clearfix">
          
            <!-- Include last viewed html-->
            @include('sections.last_viewed')
            
            <!-- hidden fields-->
            <input type="hidden" id="league_id" value="{{ $category->id }}"/>
            <input type="hidden" id="pagination_limit" value="10"/>
            <input type="hidden" id="filter_type" value="0"/>
            <input type="hidden" id="postion_league_order" value="desc"/>
            <input type="hidden" id="records_order" value="desc"/>
            <input type="hidden" id="score_order" value="desc"/> 
            <!-- hidden fields-->
            
          <div class="lstViewd_midContent_outer clearfix">           
            <div class="lstViewd_midContent">
              <div class="entryType_regionCont">
                <div class="entryType_region_tabsOut">
                  <div class="entryType_region_tabsTitle wishtlistRecord clearfix">
                    <ul>
                        <li> <a class="entryType_region_tabsBtn" href="javascript:void(0)"><em>Position in League</em></a>
                            <ul class="tabItem_dropDown" data-type="postion_league_order">
                                <li><a class="" href="javascript:void(0)" data-id="desc"><em>Highest</em></a></li>
                                <li><a class="" href="javascript:void(0)" data-id="asc"><em>Lowest</em></a></li>
                             </ul>

                         </li>
                         <li> <a class="entryType_region_tabsBtn" href="javascript:void(0)"><em>Number of Records</em></a>
                              <ul class="tabItem_dropDown" data-type="records_order">
                                <li><a class="" href="javascript:void(0)" data-id="desc"><em>Highest</em></a></li>
                                <li><a class="" href="javascript:void(0)" data-id="asc"><em>Lowest</em></a></li>                              
                              </ul>
                         </li>
                        <li> <a class="entryType_region_tabsBtn" href="javascript:void(0)"><em>Score</em></a>
                              <ul class="tabItem_dropDown" data-type="score_order">
                                <li><a class="" href="javascript:void(0)" data-id="desc"><em>Highest</em></a></li>
                                <li><a class="" href="javascript:void(0)" data-id="asc"><em>Lowest</em></a></li>                            
                              </ul>
                         </li>                      
                    </ul>
                  </div>
                  <div class="recordCollectioninner clearfix">
                    <div class="artBoardOuter clearfix">
                        <div id="filter_collectors"></div>
                    </div>
                  </div>
                </div>
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
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#async=1&pubid=ra-5a28d88682efe47d"></script>
<script>
    
    function getCollectors(page) {        
      $("#filter_collectors").LoadingOverlay("show");
      
      var limit = $("#pagination_limit").val();
      var league_id = $("#league_id").val();
      var postion_league_order = $("#postion_league_order").val();  
      var records_order = $("#records_order").val();  
      var score_order = $("#score_order").val(); 
      var filter_type = $("#filter_type").val();
            
      $.ajax({
          type: "post",
          url: "{{ url('search-collectors') }}"+"?page=" + page,
          data: {league_id:league_id, postion_league_order:postion_league_order,filter_type:filter_type, records_order:records_order, score_order:score_order, limit: limit},
          success:function (result) {                                              
              $("#filter_collectors").html(result);
              $("#filter_collectors").LoadingOverlay("hide");
              
              addthis.layers.refresh();
          }
      });
      }
      
    $(document).ready(function() {
       getCollectors(1); 
       
       $(document).on('click', '.entryType_region_tabsTitle .tabItem_dropDown li a', function (e) {
            e.preventDefault();
            var el = $(this);
            var id = el.data('id');
            var type_el = el.parents(".tabItem_dropDown");            
            var type = type_el.data('type');
            
            //el.parents(".tabItem_dropDown").prev().html(el.html());
            
            if(type == 'postion_league_order'){
              $("#filter_type").val(1);  
              $("#postion_league_order").val(id);  
            }else if(type == 'records_order'){
              $("#filter_type").val(2);  
              $("#records_order").val(id);  
            }else if(type == 'score_order'){
              $("#filter_type").val(3); 
              $("#score_order").val(id); 
            }
            
            getCollectors(1);            
        });
        
       $(document).on('click', '.entryType_pagiLeft .pagination a', function (e) {
            e.preventDefault();
            getCollectors($(this).attr('href').split('page=')[1]);            
        });
       
       $(document).on('change', '#pagination_view', function (e) {
            e.preventDefault();
            $("#pagination_limit").val($(this).val());            
            getCollectors(1);            
        });            
    
     });

       </script>
@endsection