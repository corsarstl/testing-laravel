<?php

namespace Tests\Feature;

use App\User;
use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function ItHasName()
    {
        $team = new Team(['name' => 'Acme']);

        $this->assertEquals('Acme', $team->name);
    }

    /** @test */
    public function ItCanAddMembers()
    {
        $team = factory(Team::class)->create();

        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $team->add($user);
        $team->add($user2);

        $this->assertEquals(2, $team->count());
    }
    
    /** @test */
    public function ItCanAddMultipleMembersAtOnce()
    {
        $team = factory(Team::class)->create();

        $users = factory(User::class, 2)->create();

        $team->add($users);

        $this->assertEquals(2, $team->count());
    }

    /** @test */
    public function ItHasMaximumSize()
    {
        $team = factory(Team::class)->create(['size' => 2]);

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $team->add($user1);
        $team->add($user2);

        $this->assertEquals(2, $team->count());

        $this->expectException('Exception');

        $user3 = factory(User::class)->create();
        $team->add($user3);

    }

    /** @test */
    public function ItCanRemoveMember()
    {

    }

    /** @test */
    public function ItCanRemoveAllMembers()
    {

    }
}
