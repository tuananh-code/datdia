<?php

namespace Modules\Space\Controllers;

use App\Http\Controllers\Controller;
use Modules\Location\Models\LocationCategory;
use Modules\Space\Models\Space;
use Illuminate\Http\Request;
use Modules\Location\Models\Location;
use Modules\Review\Models\Review;
use Modules\Core\Models\Attributes;
use DB;

class SpaceController extends Controller
{
    protected $spaceClass;
    protected $locationClass;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        $this->spaceClass = Space::class;
        $this->locationClass = Location::class;
        $this->locationCategoryClass = LocationCategory::class;
    }

    public function callAction($method, $parameters)
    {
        if (!Space::isEnable()) {
            return redirect('/');
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request)
    {

        $is_ajax = $request->query('_ajax');
        $list = call_user_func([$this->spaceClass, 'search'], $request);
        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {

                // if (!empty($request->query('owner'))) {
                if (true) {
                    $infobox = view('Space::frontend.layouts.search.owner-loop-gird', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render();
                } else {
                    // $infobox = view('Space::frontend.layouts.search.loop-gird', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render();
                }
                $markers[] = [
                    "id"      => $row->id,
                    "title"   => $row->title,
                    "lat"     => (float)$row->map_lat,
                    "lng"     => (float)$row->map_lng,
                    // Change to get thumb image on S3
                    "gallery" => $row->getGalleryS3(true),
                    "infobox" => $infobox,
                    'marker' => get_file_url(setting_item("space_icon_marker_map"), 'full') ?? url('images/icons/png/pin.png'),
                    // get_price_show($row->price),
                ];
            }
        }
        $limit_location = 15;
        if (empty(setting_item("space_location_search_style")) or setting_item("space_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'rows'               => $list,
            'list_location'      => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'space_min_max_price' => $this->spaceClass::getMinMaxPrice(),
            'markers'            => $markers,
            "blank"              => 1,
            "seo_meta"           => $this->spaceClass::getSeoMetaForPageList()
        ];
        $layout = setting_item("space_layout_search", 'normal');
        if ($request->query('_layout')) {
            $layout = $request->query('_layout');
        }

        if ($is_ajax) {
            return $this->sendSuccess([
                'html'    => view('Space::frontend.layouts.search-map.list-item', $data)->render(),
                "markers" => $data['markers']
            ]);
        }
        $data['attributes'] = Attributes::where('service', 'space')->orderBy("position", "desc")->with(['terms', 'translations'])->get();

        if ($layout == "map") {
            $data['body_class'] = 'has-search-map';
            $data['html_class'] = 'full-page';
            return view('Space::frontend.search-map', $data);
        }
        return view('Space::frontend.search', $data);
    }

    public function detail(Request $request, $slug)
    {
        $row = $this->spaceClass::where('slug', $slug)->with(['location', 'translations', 'hasWishList'])->first();
        if (empty($row) or !$row->hasPermissionDetailView()) {
            return redirect('/');
        }
        $translation = $row->translateOrOrigin(app()->getLocale());
        $space_related = [];
        $location_id = $row->location_id;
        if (!empty($location_id)) {
            $space_related = $this->spaceClass::where('location_id', $location_id)->where("status", "publish")->take(4)->whereNotIn('id', [$row->id])->with(['location', 'translations', 'hasWishList'])->get();
        }
        $review_list = $row->getReviewList();
        $data = [
            'row'          => $row,
            'translation'       => $translation,
            'space_related' => $space_related,
            'location_category' => $this->locationCategoryClass::where("status", "publish")->with('location_category_translations')->get(),
            'booking_data' => $row->getBookingData(),
            'review_list'  => $review_list,
            'seo_meta'  => $row->getSeoMetaWithTranslation(app()->getLocale(), $translation),
            'body_class' => 'is_single'
        ];
        $this->setActiveMenu($row);
        return view('Space::frontend.detail', $data);
    }

    function formatNumberToVietnamese($number)
    {
        $units = ['', 'nghìn', 'triệu', 'tỷ'];
        $number = (string) $number;
        $length = strlen($number);
        $formattedNumber = '';

        for ($i = $length - 1; $i >= 0; $i -= 3) {
            $chunk = substr($number, max(0, $i - 2), min(3, $i + 1));
            $formattedNumber = $chunk != '000' ? $chunk . ' ' . $units[$length - $i / 3 - 1] . ' ' . $formattedNumber : $formattedNumber;
        }

        return trim($formattedNumber);
    }
}
