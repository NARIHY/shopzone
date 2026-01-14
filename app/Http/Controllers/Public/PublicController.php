<?php

namespace App\Http\Controllers\Public;

use App\Common\CommonPublicView;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactRequest;
use App\Jobs\Contact\ProcesseCreateContactJob;
use App\Models\Contact\Contact;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;


class PublicController extends Controller
{
    public function home(): View
    {
         $categories = ProductCategory::with(['products' => function ($q) {
                            $q->where('is_active', true)
                            ->where('stock', '>', 0)
                            ->latest();
                        }])->get();
        return view(CommonPublicView::getHomeView(), compact('categories'));
    }

    public function showProduct(Product $productToShow): View
    {
        $product = Product::with(['category', 'media'])
                    ->where('is_active', true)
                    ->where('stock', '>', 0)
                    ->findOrFail($productToShow->id);
        return view(CommonPublicView::getShowProductView(), compact('product'));
    }

    public function showCategory(): View
    {
        $categories = Cache::remember(
            'public_product_categories',
            now()->addMinutes(5), 
            function () {
                return ProductCategory::query()
                    ->where('is_active', true)
                    ->withCount('products')
                    ->select('id', 'name', 'description')
                    ->get();
            }
        );

        return view(CommonPublicView::getShowCategoryView(), compact('categories'));
    }

    public function about(): View
    {
        return view(CommonPublicView::getAboutView());
    }

    public function contact(): View
    {
        return view(CommonPublicView::getContactView());
    }

    public function storeContact(ContactRequest $contactRequest)
    {       
        ProcesseCreateContactJob::dispatch($contactRequest->validated());
        // Redirect with success message
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}