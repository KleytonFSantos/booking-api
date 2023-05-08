<?php

namespace App\Enum;

enum ValidAudioFileExtensions: string
{
    case MP3 = 'mp3';
    case MP4 = 'mp4';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
