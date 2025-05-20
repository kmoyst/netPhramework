<?php

namespace netPhramework\authentication;

interface Enroller
{
    /**
     * @param User $user
     * @return User|null An enrolled User (usually with hashed password)
     *
     */
    public function enroll(User $user):?User;
}