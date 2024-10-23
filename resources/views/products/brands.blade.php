@extends('layouts.app')

@section('content')
    <style>
        ul.brands-tablist li:hover, ul.brands-tablist a.active {
            color: #f28b00;
        }
    </style>

    <section class="flat-brand style4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="title">
                        <h3>Brands</h3>
                    </div>
                </div><!-- /.col-md-12 -->
                <div class="col-md-12">
                    <ul class="brands-tablist">
                        <li data-filter="*" class="active"><a class="active" href="javascript:void(0)" onclick="filterBrand('all')">All</a></li>
                        <li data-filter=".a"><a class="a" href="javascript:void(0)" onclick="filterBrand('a')">A</a></li>
                        <li data-filter=".b"><a class="b" href="javascript:void(0)" onclick="filterBrand('b')">B</a></li>
                        <li data-filter=".c"><a class="c" href="javascript:void(0)" onclick="filterBrand('c')">C</a></li>
                        <li data-filter=".d"><a class="d" href="javascript:void(0)" onclick="filterBrand('d')">D</a></li>
                        <li data-filter=".e"><a class="e" href="javascript:void(0)" onclick="filterBrand('e')">E</a></li>
                        <li data-filter=".f"><a class="f" href="javascript:void(0)" onclick="filterBrand('f')">F</a></li>
                        <li data-filter=".g"><a class="g" href="javascript:void(0)" onclick="filterBrand('g')">G</a></li>
                        <li data-filter=".h"><a class="h" href="javascript:void(0)" onclick="filterBrand('h')">H</a></li>
                        <li data-filter=".i"><a class="i" href="javascript:void(0)" onclick="filterBrand('i')">I</a></li>
                        <li data-filter=".j"><a class="j" href="javascript:void(0)" onclick="filterBrand('j')">J</a></li>
                        <li data-filter=".k"><a class="k" href="javascript:void(0)" onclick="filterBrand('k')">K</a></li>
                        <li data-filter=".l"><a class="l" href="javascript:void(0)" onclick="filterBrand('l')">L</a></li>
                        <li data-filter=".m"><a class="m" href="javascript:void(0)" onclick="filterBrand('m')">M</a></li>
                        <li data-filter=".n"><a class="n" href="javascript:void(0)" onclick="filterBrand('n')">N</a></li>
                        <li data-filter=".o"><a class="o" href="javascript:void(0)" onclick="filterBrand('o')">O</a></li>
                        <li data-filter=".p"><a class="p" href="javascript:void(0)" onclick="filterBrand('p')">P</a></li>
                        <li data-filter=".q"><a class="q" href="javascript:void(0)" onclick="filterBrand('q')">Q</a></li>
                        <li data-filter=".r"><a class="r" href="javascript:void(0)" onclick="filterBrand('r')">R</a></li>
                        <li data-filter=".s"><a class="s" href="javascript:void(0)" onclick="filterBrand('s')">S</a></li>
                        <li data-filter=".t"><a class="t" href="javascript:void(0)" onclick="filterBrand('t')">T</a></li>
                        <li data-filter=".u"><a class="u" href="javascript:void(0)" onclick="filterBrand('u')">U</a></li>
                        <li data-filter=".v"><a class="v" href="javascript:void(0)" onclick="filterBrand('v')">V</a></li>
                        <li data-filter=".w"><a class="w" href="javascript:void(0)" onclick="filterBrand('w')">W</a></li>
                        <li data-filter=".x"><a class="x" href="javascript:void(0)" onclick="filterBrand('x')">X</a></li>
                        <li data-filter=".y"><a class="y" href="javascript:void(0)" onclick="filterBrand('y')">Y</a></li>
                        <li data-filter=".z"><a class="z" href="javascript:void(0)" onclick="filterBrand('z')">Z</a></li>
                    </ul><!-- /.brands-tablist -->
                    <!-- partial records -->
                    <div id="partial_brands"></div>
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.flat-brand style4 -->
@endsection

@section('scripts')
<script type="text/javascript">

    $(document).ready(function() {

        const url = "{{ url('get-brands') }}";
        const brand = 'all';
        getBrands(url, brand);
    });

    function filterBrand(brand){
        $('a').removeClass('active');
        $('.'+brand).addClass('active');
        const url = "{{ url('get-brands') }}";
        getBrands(url, brand);
    }

    function brandFormSubmit(){
        $('.brand_tag').submit();
    }

</script>
@endsection
