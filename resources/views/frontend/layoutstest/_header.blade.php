<!--Top heaer section-->
<header>
  <div class="header-top py-4">
    <div class="container">
      <div class="row">
        <div class="col-md-5">
          <a href="{!! url('/') !!}"><img src="{!! asset('public/frontend/images/logo.png')!!}"></a>
        </div>
        <div class="col-md-7 text-right pt-3">
          <div class="header-top-top  pb-3">

            <div class="location pb-1">
              <form>
                <select onchange="location = this.value;" >
                  <option disabled selected>Select your location</option>
                  @if(isset($location))
                    @foreach($location as $l)

                     <option value="{!! url('/?locations='.$l->id) !!}" @if(app('request')->input('locations') != null) @if(app('request')->input('locations') ==$l->id) selected="selected" @endif @endif>{!! $l->name !!}</option>
                    @endforeach
                  @endif
                </select>
              </form>
            </div>

            <div class="social">
              <ul>
                @if(isset($socialicon))
                  @foreach($socialicon as $icon)
                    <li><a href="{!! $icon->url !!}"><i class="fab fa-{!! lcfirst($icon->name) !!}"></i></a></li>
                  @endforeach
                @endif
              </ul>
            </div>
            <div class="authenticate">
             @if(auth()->user() == null)
              <ul>
                <li>
                  <a href="#" data-toggle="modal" data-target="#login">Login</a>
                </li>
                <li>
                  <a href="#" data-toggle="modal" data-target="#registration">Registration</a>
                </li>
              </ul>
               @else
                <div class="dropdown">
                  <a class="dropdown-toggle"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   {!! auth()->user()->first_name !!}
                  </a>
                  {{--<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
                    {{--<a class="dropdown-item" href="#">Action</a>--}}
                    {{--<a class="dropdown-item" href="#">Another action</a>--}}
                    {{--<a class="dropdown-item" href="#">Something else here</a>--}}
                  {{--</div>--}}
                </div>
                {{--</div>--}}
                {{--<div class="dropdown">--}}
                  {{--<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    {{--@if(auth()->user() != null)  {!! auth()->user()->first_name !!} @endif--}}
                  {{--</button>--}}
                  {{--<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
                    {{--<a class="dropdown-item" href="#">Profile</a>--}}
                    {{--<a class="dropdown-item" href="#">Orders</a>--}}
                    {{--<a class="dropdown-item" href="#">Logout</a>--}}
                  {{--</div>--}}
                {{--</div>--}}
               @endif
            </div>

            <div class="mini-cart">
              <p class="cart">
                <a href="{!! url('opencart') !!}">
                  <i class="fa fa-shopping-cart"></i>
                </a>
              </p>
              <span>
              {!! $cartCount !!}
             </span>
            </div>

          </div>

          <div class="header-top-bottom  col-md-12 ">
            <nav class="navbar navbar-expand-lg navbar-light">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">

                  @if(isset($mainmenus))
                    @if(!empty($mainmenus['menuArr']))
                    @foreach($mainmenus['menuArr'] as $menus)

                  <li class="nav-item @if($menus->type_id == 1)dropdown @endif ">
                    <a class="nav-link @if($menus->type_id == 1) dropdown-toggle @endif" @if($menus->type_id == 2) href="{!! $menus->url !!}" @endif  @if($menus->type_id == 1) id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"@endif>
                      {!! $menus->name !!}
                    </a>
                      @if($menus->type_id == 1)

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      @foreach($menus->link_url as $category)
                      <a class="dropdown-item" href="#{!! $category['slug'] !!}">{!! $category['name'] !!}</a>
                      @endforeach
                    </div>


                      @endif
                  </li>
                    @endforeach
                  @endif
                  @endif


                </ul>
              </div>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="header-bottom">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light">
        <div class="search">
          <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" id="searchValue" type="search" placeholder="Keyword" aria-label="Search">
            <span class="search-icon">
                    <i class="fa fa-search"></i>
          </span>
          </form>
          <div class="result"></div>
        </div>

        <span class="or">or</span>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            @if(isset($category_menu))

              @foreach($category_menu as $category)
            <li class="nav-item">
              <a class="nav-link" href="#{!! $category['slug'] !!}">
                <img src="{!! asset($category['image']) !!}">
                <span class="link-text">{!! ucfirst($category['name']) !!}</span>
              </a>
            </li>
              @endforeach
            @endif
          </ul>
        </div>
      </nav>
      {{--<div class="result"></div>--}}
    </div>

  </div>
</header>
