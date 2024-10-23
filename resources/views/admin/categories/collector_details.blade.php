@extends('layouts.app')

@section('content')
<div class="contant">
    <div class="lstViewd_leagues_contOut allRecords_cont">
      <div class="autoContent">
        <div class="lstViewd_leagues_contInnr">
          <div class="lstViewd_leagues_titleHdng">
            <h2>All Records  <strong>{{ $collector->name }}</strong></h2>
            <div class="lstViewd_bckPrev_page clearfix">
              <ul>
                <li> <a class="lstViewd_bckPrev_btn" href="{{ url('/') }}">Home </a> </li>
                <li> <span>> All Records {{ $collector->name }}</span> </li>
              </ul>
            </div>
          </div>
          <div class="lstViewd_leagues_contMain clearfix">
            
            <!-- Include last viewed html-->
            @include('sections.last_viewed')
            
            <!-- hidden fields-->
            @if($category)
            <input type="hidden" id="league_id" value="{{ $category->id }}"/>
            @endif
            <input type="hidden" id="user_id" value="{{ $collector->id }}"/>
            <input type="hidden" id="pagination_limit" value="10"/>
            <input type="hidden" id="postion_league_order" value="desc"/>
            <input type="hidden" id="records_order" value="desc"/>
            <input type="hidden" id="score_order" value="desc"/> 
            <input type="hidden" id="alphanumeric" value="all"/>
            <!-- hidden fields-->

            <div class="lstViewd_midContent_outer">
            <div class="lstViewd_midContent">
               <div class="entryType_regionCont">
                <div class="entryType_region_tabsOut">
                  
                   <!-- Include League Menu html-->
                   @if($category)
                    @include('sections.league_menu') 
                   @endif
                   <!-- Include League Menu html-->
                   
                  <div class="entryType_region_tabsShowOut">
                     <div class="collectionListOuter  clearfix">
                        <div class="visitorViewOuter">
                          <div class="visitorViewmain">
                            <div class="visitorViewinner"> <span><img src="{{ checkImage('users/thumbs/'. $collector->profile_image) }}" alt="#"></span>
                              <div class="visitorlastView">
                                <div class="visitorlastView_head clearfix">
                                  <div class="visitornameOuter">
                                    <h4>{{ $collector->name }}</h4>
                                  </div>
                                </div>
                                <div class="visitDetailOuter clearfix">
                                  <div class="visitorLastvisitview"> <span><img src="{{ checkImage('users/thumbs/'. $collector->profile_image) }}" alt="#"></span> </div>
                                  <div class="visiteDetailInfo">
                                    <p>Last Visited </p>
                                    <strong>{{ date("d M Y", strtotime($collector->created_at)) }}</strong> </div>
                                </div>
                                <div class="messagesouter clearfix">
                                  <ul>
                                    <li><a href="#"><i class="fa fa-comments" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i> </a></li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="wishlistcollectionevent clearfix">
                          <div class="wishlisteventHead clearfix">
                            <h2>{{ $collector->name }}</h2>
                            <div class="wishlistSociallink clearfix">
                              <div class="addthis_inline_share_toolbox" data-url="{{ url('user-profile/'.HashIds::encode($collector->id)) }}" data-title="{{ $collector->name }}" data-description="{{ $collector->description }}" data-media="{{ checkImage('users/thumbs/'. $collector->profile_image) }}"></div>    
                            </div>
                          </div>
                          <div class="discriptionOfuser clearfix">
                            <div class="discriptioninfo">
                              <h6>{{ $collector->description }}</h6>
                            </div>
                            
                            @auth
                                @if(!inWatchlist(Hashids::encode($collector->id),2))
                                <div class="wishtListDelate add_to_watchlist" data-id="{{ $collector->id }}"> <a href="javascript:void(0)">Save Collector</a> </div>
                                @endif
                            @endauth
                            
                            
                          </div>

                          <div class="alRec_colectLeague_dataBoxOut">
                               <h2>Collector’s League Data</h2>
                                <div class="alRec_colectLeague_dataTbl">
                                        <ul>
                                        <li>
                                           <div class="card_dataMain clearfix">
                                             <p>Member Since:</p>
                                             <strong>{{ date("d M Y", strtotime($collector->created_at)) }}</strong> 
                                           </div>
                                        </li>
                                        @if($category)
                                        <li>
                                           <div class="card_dataMain clearfix">
                                             <p>Number of Records in this League:</p>
                                             <strong>{{ $collector->league_records_count }}</strong> 
                                           </div>
                                        </li>
                                        @endif
                                        <li>
                                           <div class="card_dataMain clearfix">
                                             <p>Location:</p>
                                             <strong>{{ $collector->country->name }}</strong> 
                                           </div>
                                        </li>
                                        @if($category)
                                        <li>
                                           <div class="card_dataMain clearfix">
                                             <p>Position in League:</p>
                                             <strong>{{ $collector->position_in_league }}</strong> 
                                           </div>
                                        </li>
                                        @endif
                                        <li>
                                           <div class="card_dataMain clearfix">
                                             <p>Active Leagues:</p>
                                             <strong>{{ $collector->active_leagues }}</strong> 
                                           </div>
                                        </li>
                                        @if($category)
                                        <li>
                                           <div class="card_dataMain clearfix">
                                             <p>Highest Score:</p>
                                             <strong>{{ $collector->highest_score }}</strong> 
                                           </div>
                                        </li>
                                        @endif
                                        <li>
                                           <div class="card_dataMain clearfix">
                                             <p>Number of All Records:</p>
                                             <strong>{{ $collector->records_count }}</strong> 
                                           </div>
                                        </li>
                                        @if($category)
                                        <li>
                                           <div class="card_dataMain clearfix">
                                             <p>Lowest Score:</p>
                                             <strong>{{ $collector->lowest_score }}</strong> 
                                           </div>
                                        </li>
                                        @endif
                                        <li>
                                           <div class="card_dataMain clearfix">
                                             <p>Overal Score:</p>
                                             <strong>{{ $collector->user_score }}</strong> 
                                           </div>
                                        </li>                                  
                                    </ul>
                                  </div>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>



            <div class="secondViewd_leagues_titleHdng">
                <h2>{{ $collector->name }}’s Records 
                  @if($category)
                    in  <strong>{{ $category->category_name }}</strong>
                  @endif
                </h2>
              </div>

                <div class="lstViewd_midContent" id="records_overlay">
               <div class="entryType_regionCont">
                <div class="entryType_region_tabsOut">
                  <div class="entryType_region_tabsTitle clearfix">
                    <ul>
                      <li> <a class="entryType_region_tabsBtn active" href="#"><em>Number of Records</em></a> </li>
                      <li> <a class="entryType_region_tabsBtn " href="#"><em>Average Score</em></a> </li>
                      <li> <a class="entryType_region_tabsBtn " href="#"><em>Score</em></a> </li>
                     </ul>
                  </div>
                  
                    @php( $alphanumericArray = ['0-9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'])
                  
                 <div class="searchRecordAlphbaticlly wishlistTorecord clearfix">
                    <ul class="alphanumeric">
                         <li><a class="select" href="javascript:void(0)" data-id="all"> All</a></li>
                         @foreach($alphanumericArray as $alphanum)
                             <li><a href="javascript:void(0)" data-id="{{ $alphanum }}">{{ $alphanum }}</a></li>
                         @endforeach
                     </ul>
                  </div>
                    
                  <div class="entryType_region_tabsShowOut" id="filter_records"> </div>
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
    </div>
@endsection
                           
@section('scripts')
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#async=1&pubid=ra-5a28d88682efe47d"></script>
<script>
    
function getRecords(page) 
{        
    $("#records_overlay").LoadingOverlay("show");

    var limit = $("#pagination_limit").val();
    var category_id = $("#category_id").val(); 
    var user_id = $("#user_id").val(); 
    var alphanumeric = $("#alphanumeric").val(); 

    $.ajax({
        type: "post",
        url: "{{ url('search-records') }}"+"?page=" + page,
        data: {category_id:category_id, user_id:user_id, alphanumeric:alphanumeric, limit: limit},
        success:function (result) {                                              
            $("#filter_records").html(result);
            $("#records_overlay").LoadingOverlay("hide");
        }
    });
}
      
$(document).ready(function() {
   getRecords(1); 
   
   //filter by alphanumeric value
    $(document).on('click', '.alphanumeric li a', function (e) 
    {
          e.preventDefault();
          var el = $(this);

          $('.alphanumeric').find('li a').removeClass("select");                                     
          el.addClass("select");              
          $("#alphanumeric").val(el.data('id'));   

          getRecords(1);            
      });
   
   $(document).on('click', '.entryType_pagiLeft .pagination a', function (e) 
   {
        e.preventDefault();
        getRecords($(this).attr('href').split('page=')[1]);            
    });

   $(document).on('change', '#pagination_view', function (e) 
   {
        e.preventDefault();
        $("#pagination_limit").val($(this).val());            
        getRecords(1);            
    });
    
   $('.add_to_watchlist').click(function(){
        var el = $(this);
        var id = el.data('id');
        var url= "{{url('watchlist')}}";

        swal({
            title: "Are you sure! Add to Watchlist?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {

                $.ajax({
                    url:url,
                    type:"post",
                    data:{type:2,opponent_id:id},
                    complete:function (result, status) {
                        if(result.status == 200){
                            swal({title: 'Successfully added to your watchlist', type: "success"});
                            el.remove();
                        }else{
                            swal({title: 'Opponent already exist to your watchlist', type: "error" });
                        }
                    }

                });//..... end of ajax() .....//
        } else {
            swal("Cancelled", "Record is not Deleted.", "error");
        }
        });
    }); 
});

       </script>
@endsection