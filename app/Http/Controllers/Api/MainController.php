<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\City;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Settings;
use App\Models\Page;
use App\Http\Resources;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Review;


class MainController extends Controller
{

    public function cities()
    {
        $cities = City::all();
        return resposeJson(1, 'succss', $cities);
    }
    public function regions(Request $request)
    {
        $regions = Region::where(function ($query) use ($request)
        {
            if ($request->has('city_id'))
            {
                $query->where('city_id', $request->city_id);

            }
            
        })->get();

        return resposeJson(1, 'succss', $regions);

    }
    public function categories()
    {
        $categories = Category::all();
        return resposeJson(1, 'succss', $categories);
    }
    public function restaurants(Request $request)
    {
        $restaurants = Restaurant::where(function ($query) use ($request)
        {
            if ($request->has('region_id'))
            {
                $query->where('region_id', $request->region);

            }
            if ($request->has('keyword'))
            {
                $query->where('name','LIKE','%'.$request->keyword.'%');

            }
            if ($request->has('categories'))
            {
                $query->whereHas('categories', function ($query2) use ($request) {
                    $query2->where('category_id', $request->categories);
                });

            }
         
        })->where('activated','1')->get();

        return resposeJson(1, 'succss', $restaurants);

    }
    public function restaurant(Request $request)
    {
        $restaurant = Restaurant::with('categories')->findOrFail($request->id);
        return resposeJson(1, 'succss',['restaurant'=>new \App\Http\Resources\Restaurant($restaurant->load('categories')),
    ]);
    }
    public function products(Request $request)
    {
        $products = Product::where(function ($query) use ($request)
        {
            if ($request->has('restaurant_id'))
            {
                $query->where('restaurant_id', $request->restaurant_id);

            }
            
        })->paginate(10);
        return resposeJson(1, 'succss', $products);

         
    }
    public function product(Request $request)
    {
        $product = Product::findOrFail($request->id)->paginate(10);
        return resposeJson(1, 'succss', $product);

         
    }
    public function offers(Request $request)
    {
        $offers = Offer::where(function ($query) use ($request)
        {
            if ($request->has('restaurant_id'))
            {
                $query->where('restaurant_id', $request->restaurant_id);

            }
            
        })->paginate(10);
        return resposeJson(1, 'succss', $offers);

         
    }
    public function offer(Request $request)
    {
        $offer = Offer::findOrFail($request->id);
        return resposeJson(1, 'succss',['offer'=>new \App\Http\Resources\Offer($offer),
    ]);
    }
    public function reviews(Request $request)
    {
        $reviews =Review::where(function ($query) use ($request)
        {
            if ($request->has('restaurant_id'))
            {
                $query->where('restaurant_id', $request->restaurant_id);

            }
            
        })->paginate(10);
       
        return resposeJson(1, 'succss',$reviews);
   
    }
    public function settings()
    {
        $settings = Settings::all();
        return resposeJson(1, 'succss', $settings);
    }
    public function pages()
    {
        $pages = Page::all();
        return resposeJson(1, 'succss', $pages);
    }
   }