<?php

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function testFetchUserData()
    {
        $model=$this->fakeModel(User::class);
        $user=$model->fetchUserData();
        $this->assertIsArray($user);
        $this->assertCount(6,$user);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('surname', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('address', $user);
        $this->assertArrayHasKey('phone', $user);

    }


    /**
     * @param string $class
     * @return mixed
     */
    private function fakeModel(string $class)
    {
        return (factory($class)->create());
    }
}
