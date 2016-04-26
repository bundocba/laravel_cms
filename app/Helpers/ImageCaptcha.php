<?php
use Illuminate\Support\Facades\Session;
class ImageCaptcha
{
    /** Width of the image */
    public $width = 140;

    /** Height of the image */
    public $height = 50;
    public $resourcesPath = './assets/landing';
    public $minWordLength = 6;
    public $maxWordLength = 6;

    /** Session name to store the original text */
    public $sessionVar = 'captcha';

    /** Background color in RGB-array */
    public $backgroundColor = array(255, 255, 255);

    public $lineColor = array(125, 125, 125);

    public $arcLineColor = array(125, 125, 125);

    /** Foreground colors in RGB-array */
    public $textColors = array(
        array(27, 78, 181), // blue
        array(22, 163, 35), // green
        array(214, 36, 7), // red
        array(99, 0, 0),
        array(66, 66, 66),
    );

    /** Foreground colors in RGB-array */
    public $fakeTextColors = array(
        array(185, 185, 185),
        array(255, 185, 185),
        array(185, 185, 255),
    );

    /** Shadow color in RGB-array or null */
    public $shadowColor = null; //array(0, 0, 0);

    /** Horizontal line through the text */
    public $lineWidth = 0;

    /**
     * Font configuration
     *
     * - font: TTF file
     * - spacing: relative pixel space between character
     * - minSize: min font size
     * - maxSize: max font size
     */
    public $fonts = array(
        //'Antykwa'  => array('spacing' => -3, 'minSize' => 27, 'maxSize' => 30, 'font' => 'AntykwaBold.ttf'),
        'Candice'  => array('spacing' =>- 1.5,'minSize' => 20, 'maxSize' => 24, 'font' => 'Candice.ttf'),
        'DingDong' => array('spacing' => -2, 'minSize' => 20, 'maxSize' => 24, 'font' => 'Ding-DongDaddyO.ttf'),
        //'Duality'  => array('spacing' => -2, 'minSize' => 20, 'maxSize' => 22, 'font' => 'Duality.ttf'),
        ////'Heineken' => array('spacing' => -2, 'minSize' => 24, 'maxSize' => 34, 'font' => 'Heineken.ttf'),     // Can not display number
        //'Jura' => array('spacing' => -2, 'minSize' => 28, 'maxSize' => 32, 'font' => 'Jura.ttf'),
        //'StayPuft' => array('spacing' => -1.5, 'minSize' => 28, 'maxSize' => 32, 'font' => 'StayPuft.ttf'),
        'Times' => array('spacing' => -2, 'minSize' => 20, 'maxSize' => 26, 'font' => 'TimesNewRomanBold.ttf'),
        'VeraSans' => array('spacing' => -1, 'minSize' => 18, 'maxSize' => 22, 'font' => 'VeraSansBold.ttf'),
    );

    public $textColor = null; //array(0, 0, 0);
    public $fakeTextColor = null; //array(0, 0, 0);

    /** Wave configuracion in X and Y axes */
    //public $Xperiod = 11;
    //public $Xamplitude = 5;
    //public $Yperiod = 12;
    //public $Yamplitude = 14;

    public $Xperiod = 9;
    public $Xamplitude = 5;
    public $Yperiod = 6;
    public $Yamplitude = 8;

    /** letter rotation clockwise */
    //public $maxRotation = 6;
    public $maxRotation = 2;

    /**
     * Internal image size factor (for better image quality)
     * 1: low, 2: medium, 3: high
     */
    public $scale = 2;

    /**
     * Blur effect for better image quality (but slower image processing).
     * Better image results with scale=3
     */
    public $blur = false;

    /** Debug? */
    public $debug = false;

    /** Image format: jpeg or png */
    public $imageFormat = 'png';

    /** GD image */
    public $img;

    public function __construct()
    {
    }

    public function createImage()
    {
        $ini = microtime(true);

        /** Initialization */
        $this->imageAllocate();

        /** Fake text insertion */
        $fakeText = $this->getRandomText();
        $fakeFontCfg = $this->fonts[array_rand($this->fonts)];
        //$this->writeFakeText($fakeText, $fakeFontCfg);
        $this->waveImage();

        /** Text insertion */
        $text = $this->getRandomText();
        Session::put('captcha',$text);
        $fontCfg = $this->fonts[array_rand($this->fonts)];
        $this->writeText($text, $fontCfg);

        
        
//        echo $this->sessionVar;
//        var_dump($_SESSION[$this->sessionVar]);die();
        /** Transformations */
        if (!empty($this->lineWidth)) {
            $this->writeOverTextLine();
        }

        $this->waveImage2();

        if ($this->blur && function_exists('imagefilter')) {
            imagefilter($this->img, IMG_FILTER_GAUSSIAN_BLUR);
        }

        $this->reduceImage();

        if ($this->debug) {
            imagestring($this->img, 1, 1, $this->height - 8, "$text {$fontCfg['font']} " . round((microtime(true) - $ini) * 1000) . "ms", $this->textColor);
        }
        /** Output */
        $this->writeImage();
        $this->cleanup();
//        return $text;
    }

    /**
     * Creates the image resources
     */
    protected function imageAllocate() {
        // Cleanup
        if (!empty($this->img)) {
            imagedestroy($this->img);
        }

        $this->img = imagecreatetruecolor($this->width * $this->scale, $this->height * $this->scale);

        // Background color
        $backgroundColor = imagecolorallocate($this->img, $this->backgroundColor[0], $this->backgroundColor[1], $this->backgroundColor[2]);
        $this->gdBgColor = imagefilledrectangle($this->img, 0, 0, $this->width * $this->scale, $this->height * $this->scale, $backgroundColor);

        // Foreground color
        $textColor = $this->textColors[mt_rand(0, sizeof($this->textColors) - 1)];
        $this->textColor = imagecolorallocate($this->img, $textColor[0], $textColor[1], $textColor[2]);

        // Fake text color
        $fakeTextColor = $this->fakeTextColors[mt_rand(0, sizeof($this->fakeTextColors) - 1)];
        $this->fakeTextColor = imagecolorallocate($this->img, $fakeTextColor[0], $fakeTextColor[1], $fakeTextColor[2]);

        // Shadow color
        if (!empty($this->shadowColor) && is_array($this->shadowColor) && sizeof($this->shadowColor) >= 3) {
            $this->GdShadowColor = imagecolorallocate($this->img, $this->shadowColor[0], $this->shadowColor[1], $this->shadowColor[2]);
        }
    }

    protected function getRandomText($length = null)
    {
        if (empty($length)) {
            $length = rand($this->minWordLength, $this->maxWordLength);
        }

        $words = "abcdefghijlmnopqrstvwyz123456789"; //ABCDEFGHIJKLMNOPQRSTUVXYZ";
        $vocals = "aeiou";

        $text = "";
        $vocal = rand(0, 1);
        for ($i = 0; $i < $length; $i++) {
            if ($vocal) {
                $text .= substr($vocals, mt_rand(0, strlen($vocals) - 1), 1);
            } else {
                $text .= substr($words, mt_rand(0, strlen($words) - 1), 1);
            }
            $vocal = !$vocal;
        }
        return $text;
    }


    protected function writeLines()
    {
        $lineColor = imagecolorallocate($this->img, $this->lineColor[0], $this->lineColor[1], $this->lineColor[2]);
        imagesetthickness($this->img, 1);

        //vertical lines
        for ($x = 0; $x < $this->width; $x += 10) {
            imageline($this->img, $x, 0, $x, $this->height, $lineColor);
        }
        imageline($this->img, $this->width - 1, 0, $this->width - 1, $this->height, $lineColor);

        //horizontal lines
        for ($y = 0; $y < $this->height; $y += 10) {
            imageline($this->img, 0, $y, $this->width, $y, $lineColor);
        }

        imageline($this->img, 0, $this->height -1, $this->width, $this->height -1, $lineColor);
    }

    function writeArcLines()
    {
        //$colors = explode(',', $this->arcLineColor);
        $colors = $this->textColors;
        imagesetthickness($this->img, 3);

        $color = $colors[rand(0, sizeof($colors) - 1)];
        $lineColor = imagecolorallocate($this->img, $color[0], $color[1], $color[2]);

        $xpos   = 10 + (10 * 2) + rand(-5, 5);
        $width  = $this->width / 2.66 + rand(3, 10);
        $height = 10 * 2.14 - rand(3, 10);

        if (rand(0, 100) % 2 == 0 ) {
            $start = rand(0, 66);
            $ypos  = $this->height / 2 - rand(5, 15);
            $xpos += rand(5, 15);
        } else {
            $start = rand(180, 246);
            $ypos  = $this->height / 2 + rand(5, 15);
        }

        $end = $start + rand(75, 110);

        imagearc($this->img, $xpos, $ypos, $width, $height, $start, $end, $lineColor);

        $color = $colors[rand(0, sizeof($colors) - 1)];
        $lineColor = imagecolorallocate($this->img, $color[0], $color[1], $color[2]);

        if (rand(1,75) % 2 == 0 ) {
          $start = rand(45, 111);
          $ypos  = $this->height / 2 - rand(5, 15);
          $xpos += rand(5, 15);
        } else {
          $start = rand(200, 250);
          $ypos  = $this->height / 2 + rand(5, 15);
        }

        $end = $start + rand(75, 100);

        imagearc($this->img, $this->width * 0.75, $ypos, $width, $height, $start, $end, $lineColor);
    }

    /**
     * Horizontal line insertion
     */
    protected function writeOverTextLine()
    {
        $x1 = $this->width * $this->scale * 0.15;
        $x2 = $this->textFinalX;
        $y1 = rand($this->height * $this->scale * 0.40, $this->height * $this->scale * 0.65);
        $y2 = rand($this->height * $this->scale * 0.40, $this->height * $this->scale * 0.65);
        $width = $this->lineWidth / 2 * $this->scale;

        for ($i = $width * -1; $i <= $width; $i++) {
            imageline($this->img, $x1, $y1 + $i, $x2, $y2 + $i, $this->textColor);
        }
    }

    /**
     * Fake text insertion
     */
    protected function writeFakeText($text, $fontcfg = array())
    {
        if (empty($fontcfg)) {
            // Select the font configuration
            $fontcfg = $this->fonts[array_rand($this->fonts)];
        }

        // Full path of font file
        $fontfile = $this->resourcesPath . '/fonts/' . $fontcfg['font'];

        /** Increase font-size for shortest words: 9% for each glyp missing */
        $lettersMissing = $this->maxWordLength - strlen($text);
        $fontSizefactor = 1 + ($lettersMissing * 0.09);

        // Text generation (char by char)
        $x = 20 * $this->scale;
        $y = round(($this->height * 27 / 40) * $this->scale);
        $length = strlen($text);
        for ($i = 0; $i < $length; $i++) {
            $degree = rand($this->maxRotation * -1, $this->maxRotation);
            $fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize']) * $this->scale * $fontSizefactor;
            $letter = substr($text, $i, 1);

            if ($this->shadowColor) {
                $coords = imagettftext($this->img, $fontsize, $degree, $x + $this->scale, $y + $this->scale, $this->GdShadowColor, $fontfile, $letter);
            }
            $coords = imagettftext($this->img, $fontsize, $degree, $x, $y, $this->fakeTextColor, $fontfile, $letter);
            $x += ($coords[2] - $x) + ($fontcfg['spacing'] * $this->scale);
        }

        $this->textFinalX = $x;
    }

    /**
     * Text insertion
     */
    protected function writeText($text, $fontcfg = array())
    {
        if (empty($fontcfg)) {
            // Select the font configuration
            $fontcfg = $this->fonts[array_rand($this->fonts)];
        }

        // Full path of font file
        $fontfile = $this->resourcesPath . '/fonts/' . $fontcfg['font'];
//        $fontfile = public_path() . "/assets/landing/fonts/" . $fontcfg['font'];
        
        /** Increase font-size for shortest words: 9% for each glyp missing */
        $lettersMissing = $this->maxWordLength - strlen($text);
        $fontSizefactor = 1 + ($lettersMissing * 0.09);

        // Text generation (char by char)
        $x = 8 * $this->scale;
        $y = round(($this->height * 27 / 40) * $this->scale);
        $length = strlen($text);
        for ($i = 0; $i < $length; $i++) {
            $degree = rand($this->maxRotation * -1, $this->maxRotation);
            $fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize']) * $this->scale * $fontSizefactor;
            $letter = substr($text, $i, 1);

            if ($this->shadowColor) {
                $coords = imagettftext($this->img, $fontsize, $degree, $x + $this->scale, $y + $this->scale, $this->GdShadowColor, $fontfile, $letter);
            }
            $coords = imagettftext($this->img, $fontsize, $degree, $x, $y, $this->textColor, $fontfile, $letter);
            $x += ($coords[2] - $x) + ($fontcfg['spacing'] * $this->scale);
        }

        $this->textFinalX = $x;
    }

    /**
     * Wave filter
     */
    protected function waveImage()
    {
        // X-axis wave generation
        $xp = $this->scale * $this->Xperiod * rand(1, 3);
        $k = rand(0, 100);
        for ($i = 0; $i < ($this->width * $this->scale); $i++) {
            imagecopy($this->img, $this->img, $i - 1, sin($k + $i / $xp) * ($this->scale * $this->Xamplitude), $i, 0, 1, $this->height * $this->scale);
        }

        // Y-axis wave generation
        $k = rand(0, 100);
        $yp = $this->scale * $this->Yperiod * rand(1, 2);
        for ($i = 0; $i < ($this->height * $this->scale); $i++) {
            imagecopy($this->img, $this->img, sin($k + $i / $yp) * ($this->scale * $this->Yamplitude), $i - 1, 0, $i, $this->width * $this->scale, 1);
        }
    }

    protected function waveImage2()
    {
        // X-axis wave generation
        $xp = $this->scale * $this->Xperiod * rand(3, 3);
        $k = rand(100, 100);
        for ($i = 0; $i < ($this->width * $this->scale); $i++) {
            imagecopy($this->img, $this->img, $i - 1, sin($k + $i / $xp) * ($this->scale * $this->Xamplitude), $i, 0, 1, $this->height * $this->scale);
        }

        // Y-axis wave generation
        $k = rand(100, 100);
        $yp = $this->scale * $this->Yperiod * rand(2, 2);
        for ($i = 0; $i < ($this->height * $this->scale); $i++) {
            imagecopy($this->img, $this->img, sin($k + $i / $yp) * ($this->scale * $this->Yamplitude), $i - 1, 0, $i, $this->width * $this->scale, 1);
        }
    }

    /**
     * Reduce the image to the final size
     */
    protected function reduceImage()
    {
        $imgResampled = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($imgResampled, $this->img, 0, 0, 0, 0, $this->width, $this->height, $this->width * $this->scale, $this->height * $this->scale);
        imagedestroy($this->img);
        $this->img = $imgResampled;
    }

    /**
     * File generation
     */
    protected function writeImage()
    {
        if ($this->imageFormat == 'png' && function_exists('imagepng')) {
            header("Content-type: image/png");
            imagepng($this->img);
        } else {
            header("Content-type: image/jpeg");
            imagejpeg($this->img, null, 80);
        }
    }

    /**
     * Cleanup
     */
    protected function cleanup()
    {
        imagedestroy($this->img);
    }

}
