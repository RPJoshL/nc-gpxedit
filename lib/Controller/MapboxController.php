<?php

namespace OCA\GpxEdit\Controller;

use OCA\GpxEdit\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\JSONResponse;
use OCP\Http\Client\IClient;
use OCP\Http\Client\IClientService;
use OCP\IAppConfig;
use OCP\IRequest;

class MapboxController extends Controller {

	private IClient $client;

	public const API_KEY_CONFIG = "mapbox_api_key";

	public function __construct(
        string $appName, 
        IRequest $request, 
        private ?string $userId,
        private IAppConfig $appConfig,
		private IClientService $clientService
	) {
		parent::__construct($appName, $request);

		$this->client = $clientService->newClient();
	}

	#[NoAdminRequired]
	public function routing($startLat, $startLng, $endLat, $endLng, $profile) {
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

			return new JSONResponse([
				'route' => $data['routes'][0]['geometry']['coordinates'],
				'waypoints' => $data['waypoints'] ?? [],
				'distance' => $data['routes'][0]['distance'] ?? null,
				'duration' => $data['routes'][0]['duration'] ?? null,
			]);
		} catch (\Exception $e) {
			return new JSONResponse(['error' => 'Failed to fetch routing data from Mapbox API']);
		}
	}

}

