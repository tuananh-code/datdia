@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="item-loop {{$wrap_class ?? ''}}">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="thumb-image p-0 h-185">
        @if($row->image_url)
                @if(!empty($disable_lazyload))
                    <div id="slide-car-map-{{ $row->id }}" class="carousel slide h-100" data-interval="false">
                        <div class="carousel-inner h-100">
                            @php
                                $row->gallery =  $row->image_id . ',' .  $row->gallery;
                            @endphp
                            @foreach (explode(",", $row->gallery) as $key => $img)
                                <div class="carousel-item h-100 @if (!$key) active @endif">
                                    <img src="{{ get_file_url($img, 'medium') }}" class="img-responsive" alt="car">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev carousel-control" type="button" data-target="#slide-car-map-{{ $row->id }}" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </button>
                        <button class="carousel-control-next carousel-control" type="button" data-target="#slide-car-map-{{ $row->id }}" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </button>
                    </div>
                @else
                    <div id="slide-car-{{ $row->id }}" class="carousel slide h-100" data-interval="false">
                        <div class="carousel-inner h-100">
                            @php
                                $row->gallery =  $row->image_id . ',' .  $row->gallery;
                            @endphp
                            @foreach (explode(",", $row->gallery) as $key => $img)
                                <div class="carousel-item  h-100 @if (!$key) active @endif">
                                    <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
                                        {!! get_image_tag($img,'medium',['class'=>'img-responsive d-block','alt'=>$row->title]) !!}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev carousel-control" type="button" data-target="#slide-car-{{ $row->id }}" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </button>
                        <button class="carousel-control-next carousel-control" type="button" data-target="#slide-car-{{ $row->id }}" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </button>
                    </div>
                @endif
        @endif
        <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
            <i class="fa fa-heart-o"></i>
        </div>
    </div>
    <div class="item-title">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            @if($row->is_instant)
                <i class="fa fa-bolt d-none"></i>
            @endif
                {!! clean($translation->title) !!}
        </a>
        @if($row->discount_percent)
            <div class="sale_info">{{$row->discount_percent}}</div>
        @endif
    </div>
    <div class="location">
        @if(!empty($row->location->name))
            @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
            {{$location->name ?? ''}}
        @endif
    </div>
    <div class="amenities">
        @if($row->passenger)
            <span class="amenity total" data-toggle="tooltip"  title="{{ __("Passenger") }}">
                <i class="input-icon field-icon icon-passenger  "></i>
                <span class="text">
                    {{$row->passenger}}
                </span>
            </span>
        @endif
        @if($row->gear)
            <span class="amenity bed" data-toggle="tooltip" title="{{__("Gear Shift")}}">
                <i class="input-icon field-icon icon-gear"></i>
                <span class="text">
                    {{$row->gear}}
                </span>
            </span>
        @endif
        @if($row->baggage)
            <span class="amenity bath" data-toggle="tooltip" title="{{__("Baggage")}}" >
                <i class="input-icon field-icon icon-baggage"></i>
                <span class="text">
                    {{$row->baggage}}
                </span>
            </span>
        @endif
        @if($row->door)
            <span class="amenity size" data-toggle="tooltip" title="{{__("Door")}}" >
                <i class="input-icon field-icon icon-door"></i>
                <span class="text">
                    {{$row->door}}
                </span>
            </span>
        @endif
    </div>
    <div class="info">
        <div class="g-price">
            <div class="prefix">
                <span class="fr_text">{{__("from")}}</span>
            </div>
            <div class="price">
                <span class="onsale">{{ $row->display_sale_price }}</span>
                <span class="text-price">{{ $row->display_price }} <span class="unit">{{__("/day")}}</span></span>
            </div>
        </div>
    </div>
</div>
