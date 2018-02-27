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

        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $team->add($user);
        $team->add($user2);

        $this->assertEquals(2, $team->count());

        $this->expectException('Exception');

        $user3 = factory(User::class)->create();
        $team->add($user3);

    }

    /** @test */
    public function ItCanRemoveMember()
    {
        $team = factory(Team::class)->create(['size' => 2]);
        $users = factory(User::class, 2)->create();

        $team->add($users);

        $team->remove($users[0]);

        $this->assertEquals(1, $team->count());
    }
    
    /** @test */
    public function ItCanRemoveMoreThanOneMemberAtOnce()
    {
        $team = factory(Team::class)->create(['size' => 3]);
        $users = factory(User::class, 3)->create();

        $team->add($users);

        $team->remove($users->slice(0, 2));

        $this->assertEquals(1, $team->count());
    }

    /** @test */
    public function ItCanRemoveAllMembersAtOnce()
    {
        $team = factory(Team::class)->create(['size' => 2]);
        $users = factory(User::class, 2)->create();

        $team->add($users);

        $team->restart();

        $this->assertEquals(0, $team->count());
    }

    /** @test */
    public function whenAddingManyMembersAtOnceYouStillMayNotExceedTheTeamMaxSize()
    {
        $team = factory(Team::class)->create(['size' => 2]);
        $users = factory(User::class, 3)->create();

        $this->expectException('Exception');
        $team->add($users);
    }
}
