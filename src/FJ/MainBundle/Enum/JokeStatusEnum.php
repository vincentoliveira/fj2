<?php

namespace FJ\MainBundle\Enum;

/**
 * Joke status list
 */
class JokeStatusEnum
{

    const PENDING =     0;
    const VALIDATED =   1;
    const MODERATED =   -1;
    const DELETED =     -2;
    
    public static $statuses = array(
        self::PENDING => 'pending',
        self::VALIDATED => 'validated',
        self::MODERATED => 'moderated',
        self::DELETED => 'deleted',
    );

}