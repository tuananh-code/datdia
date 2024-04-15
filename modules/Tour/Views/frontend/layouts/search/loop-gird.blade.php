@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="item-tour {{$wrap_class ?? ''}}">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="thumb-image p-0">
        @if($row->discount_percent)
            <div class="sale_info">{{$row->discount_percent}}</div>
        @endif
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            @if($row->image_url)
                @if(!empty($disable_lazyload))
                    <div id="slide-tour-map-{{ $row->id }}" class="carousel slide h-100" data-interval="false">
                        <div class="carousel-inner h-100">
                            @php
                                $row->gallery =  $row->image_id . ',' .  $row->gallery;
                            @endphp
                            @foreach (explode(",", $row->gallery) as $key => $img)
                                <div class="carousel-item h-100 @if (!$key) active @endif">
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
                                    <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
                                        {!! get_image_tag($img,'medium',['class'=>'img-responsive d-block','alt'=>$row->title]) !!}
                                    </a>
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
        </a>
        <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
            <i class="fa fa-heart"></i>
        </div>
    </div>
    <div class="location">
        @if(!empty($row->location->name))
            @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
            <i class="icofont-paper-plane"></i>
            {{$location->name ?? ''}}
        @endif
    </div>
    <div class="item-title">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            {!! clean($translation->title) !!}
        </a>
    </div>
    @if(setting_item('tour_enable_review'))
    <?php
    $reviewData = $row->getScoreReview();
    $score_total = $reviewData['score_total'];
    ?>
    <div class="service-review tour-review-{{$score_total}}">
        <div class="list-star">
            <ul class="booking-item-rating-stars">
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
            </ul>
            <div class="booking-item-rating-stars-active" style="width: {{  $score_total * 2 * 10 ?? 0  }}%">
                <ul class="booking-item-rating-stars">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
        </div>
        <span class="review">
            @if($reviewData['total_review'] > 1)
                {{ __(":number Reviews",["number"=>$reviewData['total_review'] ]) }}
            @else
                {{ __(":number Review",["number"=>$reviewData['total_review'] ]) }}
            @endif
        </span>
    </div>
    @endif
    <div class="info">
        <div class="duration">
            <i class="icofont-wall-clock"></i>
            {{duration_format($row->duration)}}
        </div>
        <div class="g-price">
            <div class="prefix">
                <i class="icofont-flash"></i>
                <span class="fr_text">{{__("from")}}</span>
            </div>
            <div class="price">
                <span class="onsale">{{ $row->display_sale_price }}</span>
                <span class="text-price">{{ $row->display_price }}</span>
            </div>
        </div>
    </div>
</div>
