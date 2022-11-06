<?php

namespace App\Models;

enum Gender: string
{
    case MALE = 'm';
    case FEMALE = 'f';
    case DIVERS = 'x';
}
