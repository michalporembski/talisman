<?php

namespace CardMaker\Cards\NoImage;

use CardMaker\Cards\AbstractCard;

/**
 * Class Good
 *
 * @package CardMaker\Cards\NoImage
 */
class Good extends Evil
{
    protected $layerFile = 'good';

    protected $imageAreaStartX = 20;

    protected $imageAreaStartY = 110;

    protected $imageAreaWidth = 415;

    protected $imageAreaHeight = 340;

//    protected $titleHeight = 87;

    protected $tagHeight = 444;

//    protected $descriptionHeight = 200;

    protected $cardLevelX = 390;

    protected $cardLevelY = 670;

    protected $maxTitleWidth = 370;

    protected $maxTagWidth = 230;

    protected $maxCaptionWidth = 380;

    protected $maxWriteHeight = 670;

    protected $dummyTriangleStart = 999;

    protected $displayLevel = false;

    protected $displayImage = false;
}