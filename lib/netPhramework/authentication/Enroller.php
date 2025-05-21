<?php

namespace netPhramework\authentication;

use netPhramework\presentation\FormInput\Input;

interface Enroller
{
    /**
     * @param User $user
     * @return User|null An enrolled User (usually with hashed password)
     *
     */
    public function enroll(User $user):?User;
	public function getUsernameInput():Input;
	public function getPasswordInput():Input;
}