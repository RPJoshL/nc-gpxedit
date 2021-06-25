<?php

declare(strict_types=1);

namespace OCA\GpxEdit\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

/**
 * Auto-generated migration step: Please modify to your needs!
 */
class Version000014Date20210625132243 extends SimpleMigrationStep {

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 */
	public function preSchemaChange(IOutput $output, Closure $schemaClosure, array $options) {
	}

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options) {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable('gpxedit_tile_servers')) {
			$table = $schema->createTable('gpxedit_tile_servers');
			$table->addColumn('id', 'integer', [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 4,
			]);
			$table->addColumn('user', 'string', [
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('type', 'string', [
				'notnull' => true,
				'length' => 20,
				'default' => 'tile',
			]);
			$table->addColumn('servername', 'string', [
				'notnull' => true,
				'length' => 300,
			]);
			$table->addColumn('url', 'string', [
				'notnull' => true,
				'length' => 300,
			]);
			$table->addColumn('format', 'string', [
				'notnull' => true,
				'length' => 300,
				'default' => 'image/jpeg',
			]);
			$table->addColumn('layers', 'string', [
				'notnull' => true,
				'length' => 300,
				'default' => '',
			]);
			$table->addColumn('version', 'string', [
				'notnull' => true,
				'length' => 30,
				'default' => '1.1.1',
			]);
			$table->addColumn('opacity', 'string', [
				'notnull' => true,
				'length' => 10,
				'default' => '0.4',
			]);
			$table->addColumn('transparent', 'string', [
				'notnull' => true,
				'length' => 10,
				'default' => 'true',
			]);
			$table->addColumn('minzoom', 'integer', [
				'notnull' => true,
				'length' => 4,
				'default' => 1,
			]);
			$table->addColumn('maxzoom', 'integer', [
				'notnull' => true,
				'length' => 4,
				'default' => 18,
			]);
			$table->addColumn('attribution', 'string', [
				'notnull' => true,
				'length' => 300,
				'default' => '???',
			]);
			$table->setPrimaryKey(['id']);
		}

		if (!$schema->hasTable('gpxedit_options')) {
			$table = $schema->createTable('gpxedit_options');
			$table->addColumn('id', 'integer', [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 4,
			]);
			$table->addColumn('user', 'string', [
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('jsonvalues', 'text', [
				'notnull' => true,
			]);
			$table->setPrimaryKey(['id']);
		}
		return $schema;
	}

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 */
	public function postSchemaChange(IOutput $output, Closure $schemaClosure, array $options) {
	}
}
