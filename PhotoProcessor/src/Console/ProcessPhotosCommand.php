<?php

namespace V12software\PhotoProcessor\Console;

use Illuminate\Console\Command;
use V12software\PhotoProcessor\PhotoProcessor;

class ProcessPhotosCommand extends Command
{
    protected $signature = 'photos:process {imageUrl} {--service=thumbor} {--fill_color=red} {--watermark=} {--width=800} {--height=1200}';
    protected $description = 'Process photos with Cloudinary or Thumbor + AWS S3';

    protected $photoProcessor;

    public function __construct(PhotoProcessor $photoProcessor)
    {
        parent::__construct();
        $this->photoProcessor = $photoProcessor;
    }

    public function handle()
    {
       
    }
}
