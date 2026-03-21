<?php

namespace App\Http\Middleware;

use App\Models\Visitors;
use Closure;
use Illuminate\Http\Request;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $sessionKey = 'visited_' . md5($request->url());

        if (!session()->has($sessionKey)) {
            Visitors::create([
                'ip_address' => $request->ip(),
                'url' => $request->url(),
                'device' => $this->detectDevice($request->userAgent()),
                'user_agent' => $request->userAgent(),
            ]);

            session()->put($sessionKey, true);
        }

        return $next($request);
    }

    private function detectDevice(?string $userAgent): string
    {
        if (!$userAgent)
            return 'desktop';

        $ua = strtolower($userAgent);

        // Cek tablet dulu sebelum mobile
        $tabletKeywords = [
            'ipad',
            'tablet',
            'kindle',
            'silk',
            'playbook',
            'nexus 7',
            'nexus 9',
            'nexus 10',
            'galaxy tab',
            'sm-t',
            'gt-p',
            'kftt',
            'kfjwi'
        ];

        foreach ($tabletKeywords as $keyword) {
            if (str_contains($ua, $keyword)) {
                return 'tablet';
            }
        }

        // Cek mobile setelah tablet
        $mobileKeywords = [
            'mobile',
            'android',
            'iphone',
            'ipod',
            'blackberry',
            'windows phone',
            'opera mini',
            'iemobile',
            'webos',
            'symbian',
            'nokia',
            'samsung',
            'lg-',
            'mot-'
        ];

        foreach ($mobileKeywords as $keyword) {
            if (str_contains($ua, $keyword)) {
                return 'mobile';
            }
        }

        // Default desktop
        return 'desktop';
    }
}