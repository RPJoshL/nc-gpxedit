<?php

namespace OCA\GpxEdit\Helper;

use OCP\Http\Client\IClient;
use OCP\IConfig;

class TerrainElevation
{
	/** In memory cache for loaded GD images (key = tile filepath) */
	private array $imageCache = [];

    private string $cacheDir;

	public function __construct(
		private string $accessToken, 
		private IClient $client,
        private IConfig $config,
		private int $zoom = 14
	) {
        $dataDir = $this->config->getSystemValue('datadirectory');
        $this->cacheDir = $dataDir . '/gpxedit_tile_cache';
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    // 🔹 Hauptfunktion
    public function getElevations(array $coords): array
    {
        $results = [];

        foreach ($coords as $point) {
            [$lat, $lon] = $point;
            $results[] = [$lat, $lon, $this->getElevation($lat, $lon)];
        }

        return $results;
    }

	public function getElevation(float $lat, float $lon): float
	{
        [$tileX, $tileY] = $this->deg2tile($lat, $lon, $this->zoom);
        [$pixelX, $pixelY] = $this->deg2pixel($lat, $lon, $this->zoom);

        $tilePath = $this->getTile($tileX, $tileY);

        return $this->getElevationFromTile($tilePath, $pixelX, $pixelY);
	}

    private function getTile(int $x, int $y): string
    {
        $file = "{$this->cacheDir}/{$this->zoom}_{$x}_{$y}.png";

        if (!file_exists($file)) {
            $url = "https://api.mapbox.com/v4/mapbox.terrain-rgb/{$this->zoom}/{$x}/{$y}.pngraw?access_token={$this->accessToken}";
			$response = $this->client->get($url);
            if($response->getStatusCode() !== 200) {
                throw new \Exception("Failed to fetch tile: HTTP " . $response->getStatusCode());
            }

			$body = (string)$response->getBody();
            file_put_contents($file, $body);
        }

        return $file;
    }

    private function getElevationFromTile(string $file, int $px, int $py): float
    {
        if (!isset($this->imageCache[$file])) {
            $img = @imagecreatefrompng($file);
            if ($img === false) {
                return 0.0;
            }
            $this->imageCache[$file] = $img;
        } else {
            $img = $this->imageCache[$file];
        }

        $rgb = imagecolorat($img, $px, $py);

        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        return -10000 + (($r * 256 * 256 + $g * 256 + $b) * 0.1);
    }

    public function __destruct()
    {
        foreach ($this->imageCache as $img) {
            if (is_resource($img) || $img instanceof \GdImage) {
                @imagedestroy($img);
            }
        }
        $this->imageCache = [];
    }

    private function deg2tile(float $lat, float $lon, int $zoom): array
    {
        $latRad = deg2rad($lat);
        $n = pow(2, $zoom);

        $x = floor(($lon + 180.0) / 360.0 * $n);
        $y = floor((1.0 - log(tan($latRad) + 1 / cos($latRad)) / M_PI) / 2.0 * $n);

        return [$x, $y];
    }

    private function deg2pixel(float $lat, float $lon, int $zoom): array
    {
        $latRad = deg2rad($lat);
        $n = pow(2, $zoom);

        $x = (($lon + 180.0) / 360.0 * $n);
        $y = ((1.0 - log(tan($latRad) + 1 / cos($latRad)) / M_PI) / 2.0 * $n);

        $pixelX = floor(($x - floor($x)) * 256);
        $pixelY = floor(($y - floor($y)) * 256);

        return [$pixelX, $pixelY];
    }
}