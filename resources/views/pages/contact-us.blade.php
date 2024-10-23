@extends('layouts.app')

@section('content')
<section class="flat-map">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="flat-map" class="pdmap">
                    <div class="flat-maps" data-address="" data-height="444" data-images="images/icons/map.png" data-name="Themesflat Map"></div>
                    <div class="gm-map">                
                        <div class="map"></div>                        
                    </div>
                </div><!-- /#flat-map -->
            </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /#flat-map -->

<section class="flat-contact style2">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="form-contact left">
                    <?php echo $page->content; ?>
                    <div class="form-contact-content">
                        <form action="{{url('contact-us')}}" method="post" id="form-contact" accept-charset="utf-8" data-toggle="validator">
                        {{ csrf_field() }}
                            <div class="form-box one-half name-contact form-group">
                                <label for="name">Name*</label>
                                <input class="form-control" type="text" id="name-contact" name="name" placeholder="Name" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-box one-half password-contact form-group">
                                <label for="email">Email*</label>
                                <input class="form-control contact-email" type="email" name="email" placeholder="Email" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-box form-group">
                                <label for="subject">Subject</label>
                                <input class="form-control" type="text" id="subject-contact" name="subject" placeholder="Subject" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-box form-group">
                                <label for="comments">Comments</label>
                                <textarea class="form-control" id="comment-contact" name="message" required></textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-box">
                                <button type="submit" class="contact">Send</button>
                            </div>
                        </form><!-- /#form-contact -->
                    </div><!-- /.form-contact-content -->
                </div><!-- /.form-contact left -->
            </div><!-- /.col-md-7 -->
            <div class="col-md-5">
                <div class="box-contact">
                    <ul>
                        <li class="address">
                            <h3>Address</h3>
                            <p>
                                4 Gordon Avenue <br />G52 4TG
                                <br /> Hillington
                                <br />Glasgow
                            </p>
                        </li>
                        <li class="phone">
                            <h3>Phone</h3>
                            <p>
                                0141 3280103
                            </p>
                            
                        </li>
                        <li class="email">
                            <h3>Email</h3>
                            <p>
                               aqsinternational@badrayltd.co.uk
                            </p>
                        </li>
                        <li class="address">
                            <h3>Opening Hours</h3>
                            <p>
                               24/7
                            </p>
                           <!-- <p>
                                Saturday: 10am to 4pm
                            </p>
                            <p>
                                Sunday: 12am t0 4pm
                            </p>-->
                        </li>
                        <li>
                            <h3>Follow Us</h3>
                            <ul class="social-list style2">
                                <li>
                                    <a href="#" title="">
                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="">
                                        <i class="fa fa-twitter" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="">
                                        <i class="fa fa-instagram" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="">
                                        <i class="fa fa-pinterest" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="">
                                        <i class="fa fa-dribbble" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="">
                                        <i class="fa fa-google" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul><!-- /.social-list style2 -->
                        </li>
                    </ul>
                </div><!-- /.box-contact -->
            </div><!-- /.col-md-5 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.flat-contact style2 -->

@endsection

@section('scripts')


    
@endsection