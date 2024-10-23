@extends('layouts.app')
@section('content')
    <section class="flat-checkout">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-checkout">
                        <form class="checkout" id="myForm">
                            <div class="billing-fields">
                                <div class="fields-title">
                                    <h3>Update Profile</h3>
                                    <span></span>
                                    <div class="clearfix"></div>
                                </div><!-- /.fields-title -->
                                <div class="fields-content">
                                    <div class="field-row">
                                        <div class="form-group">
                                            <p class="field-one-half">
                                                <label for="first_name">First Name *</label>
                                                <input type="text" id="first-name" name="first_name" class="form-control" placeholder="Ali" value="{{$userData->first_name}}" required>
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <p class="field-one-half">
                                                <label for="last_name">Last Name *</label>
                                                <input type="text" id="last-name" name="last_name" class="form-control" placeholder="Tufan" value="{{$userData->last_name}}" required>
                                            </p>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="field-row">
                                        <div class="form-group">
                                            <p class="field-one-half">
                                                <label for="email_address">Email Address *</label>
                                                <input type="email" id="email-address" name="email_address" class="form-control" value="{{$userData->email}}" readonly>
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <p class="field-one-half">
                                                <label for="phone">Phone *</label>
                                                <input id="phone" type="text" id="phone" name="phone" class="form-control" value="{{$userData->phone}}"required>
                                            </p>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-box pull-right">
                                        <button style="margin-bottom: 20px" type="submit" class="save-to-proceed">Update</button>
                                    </div>
                                </div><!-- /.fields-content -->
                            </div><!-- /.billing-fields -->
                        </form><!-- /.checkout -->
                    </div><!-- /.box-checkout -->
                </div><!-- /.col-md-7 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-checkout -->
@endsection


@section('scripts')

    <script type="text/javascript">
        $(document).ready(function() {
            // save user data
            $('#myForm').validator().on('submit', function (e) {
                if (e.isDefaultPrevented()) {
                    return false;
                } else {
                    e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    // everything looks good!
                    var formData = $('#myForm').serialize();
                    show_loader();
                    $.ajax({
                        url: '{{ url('update-profile') }}',
                        method: "post",
                        data: {formData: formData},
                        success: function (response) {
                            if(response){
                                if(response.status){
                                    toastr.success(response.message);
                                    hide_loader();
                                }
                            }
                        }
                    });
                }
            })
        });
    </script>
@endsection
