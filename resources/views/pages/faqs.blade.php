@extends('layouts.app')

@section('content')

<section class="flat-row flat-accordion">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="accordion">
                    <div class="title">
                        <h3>Answers to Your Questions</h3>
                    </div>
                    @foreach($faqs as $faq)
                    <div class="accordion-toggle">
                        <div class="toggle-title">
                            {{$faq->question}}
                        </div>
                        <div class="toggle-content">
                            <p>
                                {{$faq->answer}}
                            </p>
                        </div>
                    </div><!-- /.accordion-toggle -->                    
                    @endforeach
                </div><!-- /.accordion -->
            </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.flat-accordion -->
@endsection