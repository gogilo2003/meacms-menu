<?php

namespace MeaCms\Menu\Services;

use MeaCms\Menu\Models\PageVisit;
use MeaCms\Menu\Models\PageVisitStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class VisitTracker
{
    protected $request;
    protected $visitorId;
    protected $userId;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->visitorId = $this->getVisitorId();
        $this->userId = $request->user()?->id;
    }

    public function track($routeName = null)
    {
        $url = $this->request->path();

        $isUnique = !PageVisit::where('url', $url)
            ->where('visitor_id', $this->visitorId)
            ->exists();

        PageVisit::create([
            'url' => $url,
            'route_name' => $routeName,
            'visitor_id' => $this->visitorId,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'referrer' => $this->request->header('referer'),
            'user_id' => $this->userId
        ]);

        $this->updateStatistics($url, $routeName, $isUnique);

        return $this;
    }

    protected function updateStatistics($url, $routeName, $isUnique)
    {
        $today = now()->toDateString();

        $urlStat = PageVisitStat::firstOrCreate(
            ['url' => $url, 'date' => $today],
            ['unique_visits' => 0, 'total_visits' => 0, 'route_name' => null]
        );

        $urlStat->increment('total_visits');

        if ($isUnique) {
            $urlStat->increment('unique_visits');
        }

        if ($routeName) {
            $routeStat = PageVisitStat::firstOrCreate(
                ['route_name' => $routeName, 'date' => $today, 'url' => null],
                ['unique_visits' => 0, 'total_visits' => 0]
            );

            $routeStat->increment('total_visits');

            if ($isUnique) {
                $routeStat->increment('unique_visits');
            }
        }
    }

    protected function getVisitorId()
    {
        $visitorId = $this->request->cookie('visitor_id');

        if (!$visitorId) {
            $visitorId = Str::uuid();
            Cookie::queue('visitor_id', $visitorId, 60 * 24 * 365);
        }

        return $visitorId;
    }

    public function getVisitorIdValue()
    {
        return $this->visitorId;
    }

    public function getPageVisitStats()
    {
        return PageVisitStat::query()
            ->selectRaw('
                route_name,
                SUM(unique_visits) as total_unique_visits,
                SUM(total_visits) as total_visits,
                MAX(date) as last_updated
            ')
            ->whereNotNull('route_name')
            ->groupBy('route_name')
            ->orderByDesc('total_visits')
            ->get();
    }

    public function getByRoutName($routeName)
    {
        $dailyStats = PageVisitStat::query()
            ->where('route_name', $routeName)
            ->orderBy('date')
            ->get();

        return $dailyStats;
    }
}
