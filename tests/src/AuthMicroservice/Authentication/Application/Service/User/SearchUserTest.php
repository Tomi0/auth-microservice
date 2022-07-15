<?php

namespace Tests\src\AuthMicroservice\Authentication\Application\Service\User;

use AuthMicroservice\Authentication\Application\Service\User\SearchUser;
use AuthMicroservice\Authentication\Application\Service\User\SearchUserRequest;
use AuthMicroservice\Authentication\Domain\Model\User\User;
use Tests\TestCase;

class SearchUserTest extends TestCase
{
    private SearchUser $searchUser;
    /**
     * @var User[]
     */
    private array $users;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchUser = $this->app->make(SearchUser::class);
        $this->initDatosTest();
    }

    private function initDatosTest(): void
    {
        $this->users = entity(User::class, 20)->create()->all();
    }

    public function testReturnValueIsArray(): void
    {
        $result = $this->searchUser->handle(new SearchUserRequest(null, null));
        $this->assertIsArray($result);
        $this->assertCount(count($this->users), $result);
    }

    public function testFilterByEmail(): void
    {
        $user = entity(User::class)->create([
            'email' => 'manolo@test.test',
        ]);
        $result = $this->searchUser->handle(new SearchUserRequest('nolo@tes', null));
        $this->assertEquals([$user], $result);
    }

    public function testFilterByAdmin(): void
    {
        $user = entity(User::class)->create([
            'email' => 'manolo@test.test',
            'admin' => true
        ]);
        $result = $this->searchUser->handle(new SearchUserRequest('nolo@tes', true));
        $this->assertEquals([$user], $result);
    }

    public function testFilterByNotAdmin(): void
    {
        $user = entity(User::class)->create([
            'email' => 'manolo@test.test',
            'admin' => false
        ]);
        $result = $this->searchUser->handle(new SearchUserRequest('nolo@tes', false));
        $this->assertEquals([$user], $result);
    }

    public function testReturnExpectedValues(): void
    {
        $expected = collect($this->users)->sortBy(fn (User $user) => $user->email())->values()->all();

        $result = $this->searchUser->handle(new SearchUserRequest(null, null));
        $this->assertEquals($expected, $result);
    }
}
