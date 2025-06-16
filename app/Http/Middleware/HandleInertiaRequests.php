<?php

namespace App\Http\Middleware;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use Symfony\Component\HttpFoundation\Response;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function getCachedProducts(): Collection
    {
        Cache::add('products',Product::query()->whereHas('units')-> with('units')->get());
        return Cache::get('products');
    }
    public function share(Request $request): array
    {
        $a = Cache::get('products') ;
        if ($a->isEmpty()) {
            Product::recache();
        }
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        $string = file_get_contents(lang_path() . '/' . 'ar.json');
        $json_a = json_decode($string);

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'products'=>Cache::get('products') ,
            'ziggy' => fn (): array => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'locale' => app()->currentLocale(),
            'lang_json'=>$json_a,
            'success'=>session()->get('success'),
            'failure'=>session()->get('failure'),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

}
