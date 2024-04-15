@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="item">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="header-thumb">
        @if($row->discount_percent)
            <div class="sale_info">{{$row->discount_percent}}</div>
        @endif
        @if($row->image_url)
            @if(!empty($disable_lazyload))
                <div id="slide-tour-map-{{ $row->id }}" class="carousel slide" data-interval="false">
                    <div class="carousel-inner">
                        @php
                            $row->gallery =  $row->image_id . ',' .  $row->gallery;
                        @endphp
                        @foreach (explode(",", $row->gallery) as $key => $img)
                            <div class="carousel-item  @if (!$key) active @endif">
                                <img src="{{ get_file_url($img, 'medium') }}" class="img-responsive" alt="tour">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev carousel-control" type="button" data-target="#slide-tour-map-{{ $row->id }}" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next carousel-control" type="button" data-target="#slide-tour-map-{{ $row->id }}" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                </div>
            @else
                <div id="slide-tour-{{ $row->id }}" class="carousel slide h-100" data-interval="false">
                    <div class="carousel-inner h-100">
                        @php
                            $row->gallery =  $row->image_id . ',' .  $row->gallery;
                        @endphp
                        @foreach (explode(",", $row->gallery) as $key => $img)
                            <div class="carousel-item  h-100 @if (!$key) active @endif">
                                {!! get_image_tag($img,'medium',['class'=>'img-responsive d-block','alt'=>$row->title]) !!}
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev carousel-control" type="button" data-target="#slide-tour-{{ $row->id }}" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next carousel-control" type="button" data-target="#slide-tour-{{ $row->id }}" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                </div>
            @endif
        @endif
        <a class="st-btn st-btn-primary tour-book-now" href="{{$row->getDetailUrl()}}">{{__("Book now")}}</a>
        <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
            <i class="fa fa-heart"></i>
        </div>
    </div>
    <div class="caption clear">
        <div class="title-address">
            <h3 class="title"><a href="{{$row->getDetailUrl()}}"> {!! clean($translation->title) !!} </a></h3>
            <p class="duration">
                <span>
                    {{duration_format($row->duration)}}
                </span>
                @if(!empty($row->location->name))
                    -
                    <i>
                        @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
                        {{$location->name ?? ''}}
                    </i>
                @endif
            </p>
        </div>
        <div class="g-price">
            <div class="price">
                <span class="onsale">{{ $row->display_sale_price }}</span>
                <span class="text-price">{{ $row->display_price }}</span>
            </div>
        </div>
    </div>
</div>