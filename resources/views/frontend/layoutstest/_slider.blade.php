
<div class="your-class slide-img">
    @foreach($banners as $banner)
    <div><a href="{!! $banner->url !!}"><img src="{!! asset($banner->image)!!}"></a></div>
    @endforeach
</div>