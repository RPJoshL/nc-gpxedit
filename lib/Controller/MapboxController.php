<?php

namespace OCA\GpxEdit\Controller;

use OCA\GpxEdit\AppInfo\Application;
use OCA\GpxEdit\Helper\TerrainElevation;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\JSONResponse;
use OCP\Http\Client\IClient;
use OCP\Http\Client\IClientService;
use OCP\IAppConfig;
use OCP\IConfig;
use OCP\IRequest;
use Psr\Log\LoggerInterface;

class MapboxController extends Controller {

	private IClient $client;
	private TerrainElevation $terrainElevation;

	public const API_KEY_CONFIG = "mapbox_api_key";

	public function __construct(
        string $appName, 
        IRequest $request, 
        private ?string $userId,
        private IAppConfig $appConfig,
		private IConfig $config,
		private IClientService $clientService,
		private LoggerInterface $logger
	) {
		parent::__construct($appName, $request);

		$this->client = $clientService->newClient();
		$this->terrainElevation = new TerrainElevation(
			$appConfig->getValueString(Application::APP_ID, self::API_KEY_CONFIG, ''),
			$this->client, $this->config,
		);
	}

	#[NoAdminRequired]
	public function routing($startLat, $startLng, $endLat, $endLng, $profile, bool $fetchElevation): JSONResponse {
		$mapboxApiKey = $this->appConfig->getValueString(Application::APP_ID, self::API_KEY_CONFIG, '');
		if (empty($mapboxApiKey)) {
			return new JSONResponse(['error' => 'Mapbox API key is not set']);
		}

		// sanitize coordinates to ensure valid float strings
		$startLat = strval((float)$startLat);
		$startLng = strval((float)$startLng);
		$endLat = strval((float)$endLat);
		$endLng = strval((float)$endLng);

		$coords = $startLng . ',' . $startLat . ';' . $endLng . ',' . $endLat;

		$params = [
			'access_token' => $mapboxApiKey,
			'geometries' => 'geojson',
			'overview' => 'simplified', // Should be enough for showing routing
			'radiuses' => '50;50', // Allow to offset the start and end points by up to 50 meters to improve snapping to the road network
		];

		$url = "https://api.mapbox.com/directions/v5/mapbox/{$profile}/{$coords}?" . http_build_query($params);

		try {
			$response = $this->client->get($url);
			$body = (string)$response->getBody();
			$data = json_decode($body, true);
			if (json_last_error() !== JSON_ERROR_NONE) {
				return new JSONResponse(['error' => 'Invalid JSON from Mapbox API']);
			}

			if(empty($data['routes']) || empty($data['routes'][0]['geometry']['coordinates'])) {
				return new JSONResponse(['error' => 'No route found']);
			}

			$route = $data['routes'][0]['geometry']['coordinates'];

			// Elevation calculation
			$lastElevation = null;
			$elevationUp = 0;
			$elevationDown = 0;
			if ($fetchElevation) {
				foreach ($route as &$wp) {
					$elevation = $this->terrainElevation->getElevation($wp[1], $wp[0]);

					$wp[2] = $elevation;

					if ($lastElevation !== null) {
						$elevationDiff = $elevation - $lastElevation;
						
						if ($elevationDiff > 0) {
							$elevationUp += $elevationDiff;
						} else {
							$elevationDown += abs($elevationDiff);
						}
					}
					$lastElevation = $elevation;
				}
			}

			return new JSONResponse([
				'route' => $route,
				'waypoints' => $data['waypoints'] ?? [],
				'distance' => $data['routes'][0]['distance'] ?? null,
				'duration' => $data['routes'][0]['duration'] ?? null,
				'elevationUp' => $elevationUp,
				'elevationDown' => $elevationDown,
			]);
		} catch (\Exception $e) {
			$this->logger->error('Error fetching routing data from Mapbox API', ['exception' => $e]);
			return new JSONResponse(['error' => 'Failed to fetch routing data from Mapbox API']);
		}
	}

	#[NoAdminRequired]
	public function elevation($lat, $lng): JSONResponse {
		$mapboxApiKey = $this->appConfig->getValueString(Application::APP_ID, self::API_KEY_CONFIG, '');
		if (empty($mapboxApiKey)) {
			return new JSONResponse(['error' => 'Mapbox API key is not set']);
		}

		// sanitize coordinates to ensure valid float strings
		$lat = strval((float)$lat);
		$lng = strval((float)$lng);

		try {
			$elevation = $this->terrainElevation->getElevation($lat, $lng);
			return new JSONResponse(['elevation' => $elevation]);
		} catch (\Exception $e) {
			$this->logger->error('Error fetching elevation data from Mapbox API', ['exception' => $e]);
			return new JSONResponse(['error' => 'Failed to fetch elevation data from Mapbox API']);
		}
	}

}

