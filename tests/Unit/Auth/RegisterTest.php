<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\GenericValidationTests;
use Tests\GenericActionTests;

use App\User;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    protected static $body, $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    protected function setUp() {
        parent::setUp();

        self::$body = [
            'name' => 'Test User',
            'email' => 'newuser@test.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];
    }

    /**
     * Valid test case.
     */
    public function testRegisterValid() {
        $generic = new GenericActionTests($this);
        $expected = array_diff_key(self::$body, ['password_confirmation' => 0, 'password' => 0]);
        $generic->testSuccessfulCreate('register', 'POST', self::$headers, self::$body, $expected);
    }

    /**
     * Name tests
     */

    /**
     * Test email is an empty string.
     *
     * @return void
     */
    public function testRegisterWithEmptyName()
    {
        $generic = new GenericValidationTests($this);
        $generic->testEmptyAttribute('register', 'POST', self::$headers, self::$body, 'name');
    }

    /**
     * Test name is null.
     *
     * @return void
     */
    public function testRegisterWithNullName()
    {
        $generic = new GenericValidationTests($this);
        $generic->testNullAttribute('register', 'POST', self::$headers, self::$body, 'name');
    }

    /**
     * Test name is 256 or more chars long.
     *
     * @return void
     */
    public function testRegisterWithLongName()
    {
        $generic = new GenericValidationTests($this);
        $generic->testMaxStringAttribute('register', 'POST', self::$headers, self::$body, 'name', 255);
    }


    /**
     * Email tests
     */

    /**
     * Test email is an empty string.
     *
     * @return void
     */
    public function testRegisterWithEmptyEmail()
    {
        $generic = new GenericValidationTests($this);
        $generic->testEmptyAttribute('register', 'POST', self::$headers, self::$body, 'email');
    }

    /**
     * Test email is null.
     *
     * @return void
     */
    public function testRegisterWithNullEmail()
    {
        $generic = new GenericValidationTests($this);
        $generic->testNullAttribute('register', 'POST', self::$headers, self::$body, 'email');
    }

    /**
     * Test email is 256 or more chars long.
     *
     * @return void
     */
    public function testRegisterWithLongEmail()
    {
        $generic = new GenericValidationTests($this);
        $generic->testMaxStringAttribute('register', 'POST', self::$headers, self::$body, 'email', 255);
    }

    /**
     * Test email is not a valid email address.
     *
     * @return void
     */
    public function testRegisterWithInvalidEmail()
    {
        $generic = new GenericValidationTests($this);
        $generic->testInvalidEmailAttribute('register', 'POST', self::$headers, self::$body, 'email');
    }

    /**
     * Test email is already used.
     *
     * @return void
     */
    public function testRegisterWithRepeatedEmail()
    {
        self::$body['email'] = User::first()->email;

        $generic = new GenericValidationTests($this);
        $generic->testNotUniqueAttribute('register', 'POST', self::$headers, self::$body, 'email');
    }

    /**
     * Password tests
     */

    /**
     * Test password is an empty string.
     *
     * @return void
     */
    public function testRegisterEmptyPassword()
    {
        $generic = new GenericValidationTests($this);
        $generic->testEmptyAttribute('register', 'POST', self::$headers, self::$body, 'password', 'password_confirmation');
    }

    /**
     * Test password is null.
     *
     * @return void
     */
    public function testRegisterNullPassword()
    {
        $generic = new GenericValidationTests($this);
        $generic->testNullAttribute('register', 'POST', self::$headers, self::$body, 'password', 'password_confirmation');
    }

    /**
     * Test password is 5 or less chars long.
     */
    public function testRegisterShortPassword() {
        $generic = new GenericValidationTests($this);
        $generic->testMinStringAttribute('register', 'POST', self::$headers, self::$body, 'password', 6, 'password_confirmation');
    }

    /**
     * Test password is 30 or more chars long.
     */
    public function testRegisterLongPassword() {
        $generic = new GenericValidationTests($this);
        $generic->testMaxStringAttribute('register', 'POST', self::$headers, self::$body, 'password', 30, 'password_confirmation');
    }

    /**
     * Test attribute is not confirmed.
     */
    public function testRegisterNotConfirmedPassword() {
        $generic = new GenericValidationTests($this);
        $generic->testNotConfirmedAttribute('register', 'POST', self::$headers, self::$body, 'password');
    }
}
