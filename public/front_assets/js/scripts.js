
$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function(){

    $(document).on("input", "#phone", function() {
        this.value = this.value.replace(/\D/g,'');
    });

    $(".quanlity span.btn-down").click(function(){
        var input = $(".quanlity input.qty");
        if(input.val()>1){
            input.val(input.val()-1);
        }
    });
    $(".quanlity span.btn-up").click(function(){
        var input = $(this).parents(".quanlity").find("input.qty");
        if(parseInt(input.val())<parseInt(input.attr("max"))){
            input.val(parseInt(input.val())+1);
        }else{
            error_message("You have added maximum "+input.attr("max")+" quanlity");
        }
    });
    $('#newsletterForm').validator().on('submit', function (e) {
        var subscriber_email = $("#subscriber_email").val();

        if (e.isDefaultPrevented()) {
        // handle the invalid form...
        } else {
            e.preventDefault();
            $.ajax({
                url: base_url + '/newsletter-subscribed',
                type: "post",
                data:{subscriber_email:subscriber_email},
                beforeSend: function(){
                    $("#subscriber_email").LoadingOverlay("show");
                },
                complete:function (res) {
                    $("#subscriber_email").LoadingOverlay("hide");
                    if(res.status == 422){
                        $('#subscriber_email_error').text('Sorry! This email has already been subscribed with us').show();
                    } else {
                        toastr.success('Subscription successfull');
                        $('#subscriber_email').val('');
                    }
                }//.... end of success.
            });
        }
    })
});

/*common btn loader*/
$(document).on("click",".btnLoader", function(){
    show_loader();
});

$(".cat-list-search ul li").click(function(){
    var id = $(this).data('id');
    var name = $(this).text();

    $("#searchCategory option").val(id);
    $("#searchCategory option").text(name);
});
function getProducts(url, records_per_page, order)
{
    if(records_per_page === undefined || records_per_page === null || records_per_page === '')
    {
      var records_per_page   = $('.page_records').val();
    }

    if(order === undefined || order === null || order === '')
    {
      var order   = $('.page_order').val();
    }

    var view_type = getParams('view-type');
    var category = getParams('category');

    show_loader();

    $.ajax({
        type: "post",
        url: url,
        data: {
            records_per_page:records_per_page,
            order: order,
            category: category
        },
        success:function (result) {
            $("#partial_records").html(result);
            hide_loader();
        },
        complete: function(){
            manageViewType(view_type);
        }
    });
}

function manageViewType(view_type){
    if(view_type == 'list'){
        $('.grid_section').addClass('hide');
        $('.grid_pagination').addClass('hide');
        $('.list_section').addClass('show');
    }else{
        $('.grid_section').addClass('flex');
        $('.list_section').addClass('hide');
        $('.grid_section').removeClass('hide');
    }
}

function show_loader()
{
    $(".preloader").show();
}

function hide_loader()
{
    $(".preloader").hide();
}

function getBrands(url, brand)
{
    show_loader();
    $.ajax({
        type: "post",
        url: url,
        data: {
            brand:brand
        },
        success:function (result) {
            $("#partial_brands").html(result);
            hide_loader();
        }
    });
}

function getHomeProducts(url, param)
{
    show_loader();
    $.ajax({
        type: "post",
        url: url,
        data: {
            param:param
        },
        success:function (result) {
            $("#partial_home_products").html(result);
            hide_loader();
        }
    });
}

function getCategories(url, records_per_page) {
    show_loader();
    $.ajax({
        type: "post",
        url: url,
        data: {
            records_per_page:records_per_page},
        success:function (result) {
            $("#partial_records").html(result);
            hide_loader();
        }
    });
}

function topCategories(url, records_per_page) {
    show_loader();
    $.ajax({
        type: "get",
        url: url,
        data: {
            records_per_page:records_per_page},
        success:function (result) {
            $("#partial_records").html(result);
            hide_loader();
        }
    });
}

function getWishlist(url, records_per_page, product_id) {
    if(product_id !== undefined){
        product_id = product_id
    }
    show_loader();
    $.ajax({
        type: "post",
        url: url,
        data: {
            records_per_page:records_per_page,
            product_id: product_id
        },
        success:function (result) {
            $("#partial_records").html(result);
            if(product_id){
                toastr.success("Item removed from your wishlist successfully");
            }
            hide_loader();
        }
    });
}

function getMyOrders(url, records_per_page, product_id) {
    if(product_id !== undefined){
        product_id = product_id
    }
    show_loader();
    $.ajax({
        type: "post",
        url: url,
        data: {
            records_per_page:records_per_page,
            product_id: product_id
        },
        success:function (result) {
            $("#partial_records").html(result);
            if(product_id){
                toastr.success("Item removed from your wishlist successfully");
            }
            hide_loader();
        }
    });
}

function success_message(message, title)
{
    if (!title) title = "";
    toastr.remove();
    toastr.success(message, title, {
        closeButton: true,
        timeOut: 4000,
        progressBar: true,
        newestOnTop: true
    });
}

function error_message(message, title)
{
    if (!title) title = "";
    toastr.remove();
    toastr.error(message, title, {
        closeButton: true,
        timeOut: 4000,
        progressBar: true,
        newestOnTop: true
    });
}

function browserBackBtnHandler(url)
{
    // set timeout to get actual page url otherwise page number missing
    setTimeout(function(){

        var current_url = window.location.href;
        // getting page number from url
        var page_number = getParams('page', current_url);
        if(!page_number){
            page_number = 1;
        }
        // getting records from url
        var records =  getParams('records', current_url);
        if(!records){
            records = 10;
        }
        // getting order from url
        var ordering=  getParams('order', current_url);
        if(!ordering){
            ordering = 'desc';
        }
        getProducts(url+'?page='+page_number, records, ordering);

    }, 1000);
    hide_loader();
}

function pageClick(url, current_url, more_options_url, relatedTo)
{
    // get page number from ajax url to execute ajax search results
    var new_page_number = (url).split('page=').pop();
    // alert(new_page_number);return false;

    var page_number        = getParams('page', current_url);
    if(!page_number){
        page_number = 1;
    }
    // getting records from url
    var records =  getParams('records', current_url);
    if(!records){
        records = 10;
    }
    // getting order from url
    var ordering=  getParams('order', current_url);
    if(!ordering){
        ordering = 'desc';
    }

    if(relatedTo == 'wishlist')
    {
        getWishlist(url, records);
        // manage url state with ajax url

        if((current_url.indexOf('page') == -1)){
            set_page_to = current_url+'?page='+new_page_number;
        }else{
            set_page_to = current_url.replace("page="+page_number, "page="+new_page_number);
        }
    }
    if(relatedTo == 'products')
    {
        // getProducts(url+more_options_url, records, ordering);
        if((current_url.indexOf('filter') != -1)){
            url = url+more_options_url;
        }
        getProducts(url, records, ordering);

        //start code to maintain url history in the browser url
        //alert(current_url);
        if((current_url.indexOf('records') == -1)){//alert('if');
            set_page_to = current_url+'&records='+records+'&order='+ordering+'&page='+new_page_number;
        }else{//alert('else');
            set_page_to = current_url.replace("page="+page_number, "page="+new_page_number);
        }
    }
    if(relatedTo == 'categories')
    {
        // getProducts(url+more_options_url, records, ordering);
        getCategories(url, records, ordering);

        //start code to maintain url history in the browser url
        if((current_url.indexOf('page') == -1)){
            set_page_to = current_url+'?page='+new_page_number;
        }else{
            set_page_to = current_url.replace("page="+page_number, "page="+new_page_number);
        }
    }

    // manage url state with ajax url
    window.history.pushState("", "", set_page_to);
}

function recordsPerPage(records_per_page, url, current_url, more_options_url, ordering)
{
    if((current_url.indexOf('records') == -1)){
        updated_url = current_url+'&records='+records_per_page+'&order='+ordering+'&page=1';
    }else{
        var page_number = getParams('page');
        if(!page_number){
            page_number = 1;
        }
        // getting records from url
        var records =  getParams('records');
        if(!records){
            records = 10;
        }
        var update_records  = current_url.replace("records="+records, "records="+records_per_page);
        // var update_records  = update_records.replace("category=null", "category=null");
        var updated_url     = update_records.replace("page="+page_number, "page=1");
    }
    getProducts(url+'?page=1'+more_options_url, records_per_page, '');

    // manage url state with ajax url
    window.history.pushState("", "", updated_url);
}

function orderChange(new_order, url, current_url, more_options_url, ordering, page_number, records)
{
    if((current_url.indexOf('records') == -1)){
        updated_url = current_url+'&records='+records+'&order='+new_order+'&page='+page_number;
    }else{
        var ordering=  getParams('order');
        if(!ordering){
            ordering = 'desc';
        }
        var updated_url    = current_url.replace("order="+ordering, "order="+new_order);
    }
    // console.log(url+'?page='+page_number+more_options_url, records, new_order);
    getProducts(url+'?page='+page_number+more_options_url, records, new_order);
    // manage url state with ajax url
    window.history.pushState("", "", updated_url);
}

function getCategoryProducts(new_order, url, current_url, more_options_url, ordering, page_number, records)
{
    getProducts(url+'?page='+page_number+more_options_url, records, new_order);
}

function getParams( name, url)
{
    if (!url) url = location.href;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    return results == null ? null : results[1];
}

function sendUserFavoriteRequest(product_id,successCallBack, url, records, ordering, post_url, detail)
{
    show_loader();
    $.ajax({
        type: "post",
        url: post_url,
        data: {
            product_id: product_id
        },
        success:function (result) {
            successCallBack(result);
            if(detail === undefined || detail === null || detail === '')
            {
                getProducts(url, records, ordering);
            }else{
                if(result.favorite){
                    $('.yes').show();
                    $('.no').hide();
                }else{
                    $('.no').show();
                    $('.yes').hide();
                }
            }
            hide_loader();

        },

        error: function(){
            toastr.error("Some Error Occured");
            hide_loader();
        }
    });
}

function favoriteRequest(product_id, url, records, ordering, login_check, post_url, detail)
{
    var successCallBack = function(result){
        if(result.favorite){
            toastr.success("Item added to your wishlist successfully");
        }else{
            toastr.success("Item removed from your wishlist successfully");
        }
    }
    if(login_check)
    {
        sendUserFavoriteRequest(product_id,successCallBack, url, records, ordering, post_url, detail);
        hide_loader();
    }else{
        toastr.info("Login first to make item favorite");
        return false;
    }
}

function addToCart(id, qty){
    if(typeof qty === "number" && qty < 1){
        return false;
    }
    $.ajax({
        url: base_url + '/cart-add',
        method: "POST",
        data: {id: id},
        success: function (response) {
            if(response.status) {
                $('#cartTotal').text(response.cartTotal);
                $('#cartPrice').text('£'+response.cartPrice);
                toastr.success("Item successfully added to your cart");
            } else {
                toastr.error(response.message);
            }
        }
    });
}
