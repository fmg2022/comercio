<?php

namespace App\View\Composers;

use App\Models\Category;
use App\Models\Offer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class OfferComposer
{
    protected const LIMIT = 8;

    protected const CACHE_MINUTES = 60;

    public function compose(View $view): void
    {
        $view->with('categories', $this->getCategories())
            ->with('offers', $this->getOffers());
    }

    protected function getCategories(): Collection
    {
        return Cache::remember('view:categories:full_tree', self::CACHE_MINUTES, function () {
            return Category::getFullTree();
        });
    }

    protected function getOffers(): Collection
    {
        return Cache::remember('view:offers:active', self::CACHE_MINUTES, function () {
            return Offer::with([
                'offerState:id,slug,name',
                'offerTemplate:id,name,offer_type_id,buy_qty,pay_qty',
                'offerTemplate.offerType:id,slug,name',
            ])
                ->active()
                ->select([
                    'offers.id',
                    'offers.name',
                    'offers.offer_state_id',
                    'offers.offer_template_id',
                    'offers.start_date',
                    'offers.end_date',
                ])
                ->latest('start_date')
                ->limit(self::LIMIT)
                ->get();
        });
    }
}
