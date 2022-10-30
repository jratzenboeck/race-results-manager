<?php

namespace App\Models;

enum RaceSplitType: string
{
    case SWIM = 'Swim';
    case BIKE = 'Bike';
    case RUN = 'Run';
    case TRANSITION1 = 'Transition 1';
    case TRANSITION2 = 'Transition 2';
}
