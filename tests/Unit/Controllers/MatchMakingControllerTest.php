<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\MatchMakingController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class MatchMakingControllerTest extends TestCase
{
    protected $matchMakingController;
    protected $userModel;
    protected $request;
    
    
    public function setUp()
    {
        parent::setUp();
        
        $this->userModel = Mockery::mock(User::class);
        $this->app->instance('App\User', $this->userModel);
        $this->request = \Mockery::mock(Request::class);
    }
    public function tearDown()
    {
        parent::tearDown();
    }
    
    /**
     * test index function
     * @dataProvider providerTestIndex
     * @param $prepare
     * @param $expected
     */
    public function testIndex($prepare, $expected)
    {
        Auth::shouldReceive('user')->andReturn((object)$prepare['user']);
        $this->userModel
            ->shouldReceive('getMatchUser')
            ->andReturn($prepare['matchUser']);
        $this->request
            ->shouldReceive('query')
            ->andReturn($prepare['search']);
    
        $this->matchMakingController = new MatchMakingController($this->userModel);
        $response = $this->matchMakingController->index($this->request);
        $this->assertSame($expected['viewIs'], $response->getName());
        $this->assertSame($expected['viewHas'], $response->getData());
    }
    
    public function providerTestIndex()
    {
        $matchUsers = collect([
            [
                'oldest_id' => 5,
                'id' => 1
            ],
            [
                'oldest_id' => 5,
                'id' => 2
            ]
        ]);
        
        return [
            'access index success search null' =>[
                //Prepare data
                [
                    'search' => null,
                    'user' => null,
                    'matchUser' => []
                ],
                //Expect data
                [
                    'viewHas' => [
                        'matchUsers' =>  []
                    ],
                    'viewIs' => 'match_make.index',
                ],
            ],
            'access as visitor' =>[
                [
                    'search' => 'true',
                    'user' => null,
                    'matchUser' => $matchUsers
                ],
                //Expect data
                [
                    'viewHas' => [
                        'matchUsers' =>  $matchUsers
                    ],
                    'viewIs' => 'match_make.index',
                ],
            ],
            'access as user' =>[
                [
                    'search' => 'true',
                    'user' => [
                        'id' => 5,
                        'prefers' => 'appearance'
                    ],
                    'matchUser' => $matchUsers
                ],
                //Expect data
                [
                    'viewHas' => [
                        'matchUsers' =>  $matchUsers
                    ],
                    'viewIs' => 'match_make.index',
                ],
            ],
        ];
    }
    
}
