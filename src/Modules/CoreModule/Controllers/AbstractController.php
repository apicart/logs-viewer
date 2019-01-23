<?php

declare(strict_types = 1);

namespace App\Modules\CoreModule\Controllers;

use Machy8\SmartController\SmartController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;


abstract class AbstractController extends SmartController
{

	public function beforeRender(): void
	{
		$this->setTemplateParameters([
			'layoutPath' => $this->getTemplatePath('layout', self::class),
			'logFilesCount' => $this->getLogsCount($this->getLogsDir()),
			'logsSize' => $this->getLogsDirSize($this->getLogsDir())
		]);

		parent::beforeRender();
	}


	protected function getFileSystem(): Filesystem
	{
		return new Filesystem();
	}


	protected function getFinder(): Finder
	{
		return new Finder();
	}


	protected function getLogsCount(string $directory): int
	{
		return $this->getFinder()->files()->in($directory)->count();
	}


	protected function getLogsDir(): string
	{
		return $this->getParameter('logs_dir');
	}


	protected function getLogsDirSize(string $directory): float
	{
		$size = 0;

		/** @var SplFileInfo $item */
		foreach ($this->getFinder()->in($directory) as $item) {
			$itemRealPath = $item->getRealPath();

			if ( ! is_string($itemRealPath)) {
				continue;
			}

			$size += is_file($itemRealPath) ? filesize($itemRealPath) : $this->getLogsDirSize($itemRealPath);
		}

		return $this->fileSizeIntoMegabytes($size);
	}


	/**
	 * @param int|float $size
	 */
	protected function fileSizeIntoMegabytes($size): float
	{
		return round($size / 1048576, 2);
	}

}
