<?php

namespace AutoDealersDigital\PhotoProcessor\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Background;

class CloudinaryProcessing
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
        // Initialize Cloudinary SDK
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('photo_processor.services.cloudinary.cloud_name'),
                'api_key'    => config('photo_processor.services.cloudinary.api_key'),
                'api_secret' => config('photo_processor.services.cloudinary.api_secret'),
            ]
        ]);

        $results = [];

        $fill = $this->params['fill'];
        $quality = $this->params['quality'] ?? 100;
        $overlay = $this->params['overlay_images'] ?? null;

        foreach ($this->params['photos'] as $photo) {

            $photo_url_origin = "{$this->params['user_id']}/{$this->vehicle_id}/{$photo}";
            $uploadOptions = [];

            // Apply dynamic transformations
            // 1. Quality
            $uploadOptions['transformation'][] = Effect::quality($quality);

            // 2. Watermark (if provided)
            if ($overlay) {
                $uploadOptions['transformation'][] = [
                    'overlay' => $overlay,
                    'gravity' => 'south_east', 
                    'x' => 10,
                    'y' => 10
                ];
            }

            // 3. Background color (if fill is enabled)
            if ($fill == 1) {
                if (!empty($this->params['default_bg_color'])) {
                    $uploadOptions['transformation'][] = Background::color('#' . $this->params['default_bg_color']);
                } elseif (!empty($this->params['default_bg_color_blur'])) {
                    $uploadOptions['transformation'][] = Background::auto();
                } else {
                    $uploadOptions['transformation'][] = Background::auto();
                }
            }

            // 4. Resize logic (if width and height are provided)
            if (isset($this->params['width']) && isset($this->params['height'])) {
                $uploadOptions['transformation'][] = Resize::fill($this->params['width'], $this->params['height']);
            }

            // Upload and apply transformations
            $result = $cloudinary->uploadApi()->upload($photo_url_origin, $uploadOptions);
            $results[] = $result['secure_url'];  
        }

        return $results;  
    }
}
