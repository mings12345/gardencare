<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GardenerController extends Controller
{
    public function index()
    {
        $gardeners = User::where('user_type', 'gardener')->get();
        return view('admin.manage-gardeners', compact('gardeners'));
    }
}
