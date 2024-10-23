<!--header start-->

<header class="header fixed-top clearfix">

<!--logo start-->



<div class="brand">



    <a href="{{ url('admin/dashboard') }}" class="logo">

        <span>AQS</span>

    </a>

    <!-- <div class="sidebar-toggle-box">

        <div class="fa fa-bars"></div>

    </div> -->

</div>

<!--logo end-->





<div class="top-nav clearfix">

    <!--search & user info start-->

    <ul class="nav pull-right top-menu">

<!--        <li>

            <input type="text" class="form-control search" placeholder=" Search">

        </li>-->

        <!-- user login dropdown start-->

        <li class="dropdown">

            <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                @if(Auth::user()->logo)

                    <img alt="{{ Auth::user()->name }}" src="{{ checkImage('companies/thumbs/'. Auth::user()->logo) }}">

                @endif

                <span class="username">{{ Auth::user()->name }}</span>

                <b class="caret"></b>

            </a>

            <ul class="dropdown-menu extended logout">

                <li><a href="{{ url('admin/profile') }}"><i class=" fa fa-suitcase"></i>Profile</a></li>

                <li><a href="{{ url('admin/change-password') }}"><i class=" fa fa-key"></i>Change Password</a></li>
                @can('view settings')
                <li><a href="{{ url('admin/settings') }}"><i class="fa fa-cog"></i> Settings</a></li>
                @endcan
                <li><a href="{{ url('admin/logout') }}" onclick="event.preventDefault(); document.getElementById('company-logout-form').submit();"><i class="fa fa-key"></i> Log Out</a></li>



                <form id="company-logout-form" action="{{ url('admin/logout') }}" method="POST" style="display: none;">

                    {{ csrf_field() }}

                </form>

            </ul>

        </li>

        <!-- user login dropdown end -->

    </ul>

    <!--search & user info end-->

</div>

</header>

<!--header end-->