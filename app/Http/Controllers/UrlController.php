<?php

namespace App\Http\Controllers;

use App\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class UrlController extends Controller
{
    public function index(Request $request)
    {
        /* $request->session()->flash('notice', 'Task was successful!'); */
        $urls= [
            new Url(["short_url" =>  '1ry23', 'original_url' =>  'http://google.com', 'created_at' => date("Y-m-d H:i:s"), 'clicks_count' => 0]),

            new Url(['short_url' =>  '45126', 'original_url' =>  'http://facebook.com', 'created_at' => date("Y-m-d H:i:s"), 'clicks_count' => 0]),
            new Url(['short_url' =>  '78sk9', 'original_url' =>  'http://yahoo.com', 'created_at' => date("Y-m-d H:i:s"), 'clicks_count' => 0])
        ];

        $url = new Url;

        return view('user.index', ['url' =>$url, 'urls' => $urls]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'original_url' => ['required', 'url'],
        ]);

        /* Create a new URL record */
        $url = Url::create([
            'short_url' => $this->generateCode(),
            'original_url' => $request->input('original_url'),
            'clicks_count' => 0,
        ]);

        return view('user.show', [
            'url' => $url,
            'browsers_clicks' => [],
            'daily_clicks' => [],
            'platform_clicks' => [],
        ]);
    }

    public function visit($url)
    {
        $agent = new Agent();

        // find record a clicks
        $url = Url::where('short_url', $url)->first();

        if (empty($url)) {
            abort(404);
        }

        // update url clicks count
        $url->clicks()->create([
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
        ]);

        // and redirect to original url
        return redirect()->away($url->original_url);
    }


    public function show(Url $url)
    {
        $url->loadMissing('clicks');

        $currentDate = Carbon::now();
        $carryDate = Carbon::now()->startOfMonth();

        $dailyClicks = [];
        for ($i = 1; $i <= (int) (clone $currentDate)->endOfMonth()->format('d'); $i++) {
            $clickCount = $url->clicks->filter(function ($entry) use ($carryDate) {
                return $entry['created_at']->gte((clone $carryDate)->startOfDay())
                    && $entry['created_at']->lte((clone $carryDate)->endOfDay());
            })->count();

            $dailyClicks[] = [$i, $clickCount];

            $carryDate->addDay();
        }

        $browsersClicks = [
            ['IE', $url->clicks->where('browser', 'IE')->pluck('browser')->count()],
            ['Firefox', $url->clicks->where('browser', 'Firefox')->pluck('browser')->count()],
            ['Chrome', $url->clicks->where('browser', 'Chrome')->pluck('browser')->count()],
            ['Safari', $url->clicks->where('browser', 'Safari')->pluck('browser')->count()]
        ];

        $platformClicks = [
            ['Windows', $url->clicks->where('platform', 'Windows')->pluck('platform')->count()],
            ['macOS', $url->clicks->where('platform', 'macOS')->pluck('platform')->count()],
            ['Ubuntu', $url->clicks->where('platform', 'Ubuntu')->pluck('platform')->count()],
            ['Other', $url->clicks->where('platform', 'Other')->pluck('platform')->count()]
        ];

        return view('user.show', [
            'url' => $url,
            'browsers_clicks' => $browsersClicks,
            'daily_clicks' => $dailyClicks,
            'platform_clicks' => $platformClicks
        ]);
    }

    private function generateCode(): string
    {
        $allowedCharacters = 'QWERTYUIOPASDFGHJKLZXCVBNM';
        $code = '';

        while (strlen($code) < 5) {
            $code .= $allowedCharacters[rand(0, strlen($allowedCharacters) - 1)];
        }

        if (Url::where('short_url', $code)->first()) {
            return $this->generateCode();
        }

        return $code;
    }
}
