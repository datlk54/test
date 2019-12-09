<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchMakingController extends Controller
{
    private $userModel;
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    
    /**
     * show list user match making
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $user = Auth::user();
        $matchUsers = [];
        if ($request->query('search') === 'true') {
            $matchUsers = $this->userModel->getMatchUser($user);
        }
        return view('match_make.index', [
            'matchUsers' => $matchUsers
        ]);
    }
}
