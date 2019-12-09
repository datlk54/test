<?php

namespace Tests\Unit\Models;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    private $userModel;
    public function setUp()
    {
        parent::setUp();
        $this->userModel = new User();
        factory(User::class)->create([
            'id' => 1,
            'name' => 'test_user_1',
            'prefers' => 'appearance'
        ]);
        factory(User::class)->create([
            'id' => 2,
            'name' => 'test_user_2',
            'prefers' => 'appearance'
        ]);
        factory(User::class)->create([
            'id' => 3,
            'name' => 'test_user_3',
            'prefers' => 'smokes'
        ]);
        factory(User::class)->create([
            'id' => 4,
            'name' => 'test_user_4',
            'prefers' => 'smokes'
        ]);
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }
    /**
     * @dataProvider providerTestGetMatchUser
     * @param $prepare
     * @param $expected
     */
    public function testGetMatchUser($prepare, $expected)
    {
        $userLogin = null;
        if (!is_null($prepare['user'])) {
            $userLogin = factory(User::class)->create($prepare['user']);
        }
        $result = $this->userModel->getMatchUser($userLogin);
        if (!$result->isEmpty()) {
            foreach ($result as $key => $item) {
                $this->assertEquals($expected[$key]['oldest_id'], $item->oldest_id);
                $this->assertEquals($expected[$key]['id'], $item->id);
            }
        }else {
            $this->assertEquals($expected, $result->toArray());
        }
    }
    
    public function providerTestGetMatchUser() {
        return [
            'search as visitor' => [
                [
                    'user' => null
                ],
                [
                    [
                        'oldest_id' => 1,
                        'id' => 2
                    ],
                    [
                        'oldest_id' => 3,
                        'id' => 4
                    ]
                ]
            ],
            'search as user in system' => [
                [
                    'user' => [
                        'id' => 5,
                        'prefers' => 'appearance'
                    ]
                ],
                [
                    [
                        'oldest_id' => 5,
                        'id' => 1
                    ],
                    [
                        'oldest_id' => 5,
                        'id' => 2
                    ]
                ]
            ],
            'search as user in system and no prefers match' => [
                [
                    'user' => [
                        'id' => 6,
                        'prefers' => 'religion'
                    ]
                ],
                []
            ]
        ];
    }
}
