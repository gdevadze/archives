<?php

namespace App\Http\Controllers;

use App\Models\Forest;
use App\Models\ForestGeneralCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $users = User::count();
        return view('pages.dashboard',compact('users'));
    }
}
