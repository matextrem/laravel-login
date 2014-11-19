<?php
$I = new FunctionalTester($scenario);
$I->am('registered common user');
$I->wantTo('perform authentication actions');

// When
$I->amOnPage('login');
// Then
$I->see('Log in', 'h2');
$I->see('Username', 'label');
$I->see('Password', 'label');
$I->see('Remember me', 'label');
$I->seeElement('input', ['value' => 'Log in', 'class' => 'btn btn-primary btn-block']);

$I->amGoingTo('fill an invalid user in order to see the errors');
// When
$I->fillField('username', 'admin');
$I->fillField('password', '12345');
// And
$I->click('Log in', 'input[type=submit]');
// Then
$I->expectTo('see an error message');
$I->seeCurrentUrlEquals('/login');
$I->seeInField('username', 'admin');
$I->see('Invalid data', '.alert-danger');

$I->haveRecord('users', [
	'first_name'     => 'System',
	'last_name'      => 'Administrator',
	'username'       => 'admin',
	'password'       => Hash::make('secret'),
	'email'          => 'dacosta.dev@nucleogps.com',
	'remember_token' => null
]);

$I->amGoingTo('full a valid user and see the result');
// When
$I->fillField('password', 'secret');
// And
$I->click('Log in', 'input[type=submit]');
// Then
$I->seeCurrentUrlEquals('');
$I->see('You have arrived.', 'h1');
$I->see('Log out', 'a');
$I->seeAuthentication();

$I->amGoingTo('log out');
// When
$I->click('Log out', 'a');
// Then
$I->seeCurrentUrlEquals('/login');
$I->see('Logged out correctly', '.alert-danger');
$I->dontSeeAuthentication();