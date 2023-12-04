<?php

namespace App\Http\Controllers;

use App\Utilities\Affiliates;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AffiliatesController extends Controller
{
    const AFFILIATES_FILE = 'affiliates.txt';
    const AFFILIATES_DISK = 'affiliates';
    const REDIRECT_ROUTE = 'dashboard';

    /**
     * Display the affiliates create (list) view.
     */
    public function create(): View|RedirectResponse
    {
        $affiliates = new Affiliates(self::AFFILIATES_FILE, self::AFFILIATES_DISK);
        if (!$affiliates->isValid()) {
            session()->flash('error', $affiliates->getError());

            return redirect()->route(route: self::REDIRECT_ROUTE);
        }

        return view('affiliates.create', [
            'affiliates' => $affiliates->withinDistance(distance: 100),
        ]);
    }
}
