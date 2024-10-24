<?php

namespace AutoDealersDigital\PhotoProcessor\Services;

use Beeyev\Thumbor\Thumbor;
use Beeyev\Thumbor\Manipulations\Resize;
use Beeyev\Thumbor\Manipulations\Filter;
use Beeyev\Thumbor\Manipulations\Fit;
use Beeyev\Thumbor\Manipulations\Trim;



class ThumborProcessing
{
    protected $params;
    protected $vehicle_id;

    public function __construct($params, $vehicle_id)
    {
        $this->params = $params;
        $this->vehicle_id = $vehicle_id;
    }

    public function process()
    {
        $thumbor = new Thumbor(
            config('photo_processor.services.thumbor.base_url'),
            config('photo_processor.services.thumbor.secret_key')
        );

        $results = [];

        $fill = $this->params['fill'];
        foreach ($this->params['photos'] as $photo) {

            $photo_url_origin = "{$this->params['user_id']}/{$this->vehicle_id}/{$photo}";

            $thumbor->addFilter(Filter::QUALITY, $this->params['quality'] ?? 100);
            $thumbor->addFilter(Filter::WATERMARK, $this->params['overlay_images'] ?? null);

            if ($fill == 1) {
                if (!empty($this->params['default_bg_color'])) {
                    $hex = ltrim($this->params['default_bg_color'], '#');
                    $thumbor->addFilter(Filter::FILL, $hex);
                } elseif (!empty($this->params['default_bg_color_blur'])) {
                    $thumbor->addFilter(Filter::FILL, "blur");
                } else {
                    $thumbor->addFilter(Filter::FILL, "auto");
                }
            }

            $thumbor->resizeOrFit(
                $this->params['width'] ?? null,
                $this->params['height'] ?? null,
                Fit::FIT_IN
            );

            $thumbor->imageUrl($photo_url_origin);
            $results[] = $thumbor->get();
        }

        return $results;
    }
}
