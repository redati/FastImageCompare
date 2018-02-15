<?php
/**
 * (c) Paweł Plewa <pawel.plewa@gmail.com> 2018
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */

namespace pepeEpe\FastImageCompare;


use Gumlet\ImageResize;

class NormalizerSizeType extends NormalizerBase
{
    private $sampleSize;

    public function __construct($sampleSize = 8)
    {
        parent::__construct();
        $this->setSampleSize(max(2,$sampleSize));
    }

    public function normalize($imagePath, $tempDir)
    {
        $result = $imagePath;
        if (file_exists($imagePath)) {
            $cacheFileName = $this->getCachedFile($imagePath,$tempDir);
            if (!file_exists($cacheFileName)) {
                $imageResize = new ImageResize($imagePath);
                $imageResize->quality_jpg = 100;
                $imageResize->quality_png = 9;
                $imageResize->quality_webp = 100;
                $imageResize->quality_truecolor = true;
                $imageResize->resize($this->getSampleSize(), $this->getSampleSize(), true);
                $imageResize->save($cacheFileName,IMAGETYPE_PNG);
                unset($imageResize);
            }
            return $cacheFileName;
        }
        return $result;
    }

    public function getCacheKey($imagePath)
    {
        return md5($imagePath).'.n'.$this->getSampleSize().'.png';
    }

    /**
     * @return int
     */
    public function getSampleSize()
    {
        return $this->sampleSize;
    }

    /**
     * @param int $sampleSize
     */
    public function setSampleSize($sampleSize)
    {
        $this->sampleSize = $sampleSize;
    }

}