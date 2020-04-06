<?php

declare(strict_types = 1);

namespace App\Controller;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class HomepageController extends AbstractController
{

	/**
	 * @Route("/", name="homepage")
	 */
	public function renderDefault(): Response
	{
		return $this->renderTemplate('homepage/default.twig', [
			'logs' => $this->getLogs($this->getLogsDir())
		]);
	}


	/**
	 * @Route("/delete-log", name="delete-log")
	 */
	public function handleDeleteLog(Request $request): Response
	{
		$filePath = $request->get('filePath');
		$response = 'error';

		if ($filePath !== null) {
			$response = 'ok';
			$this->getFileSystem()->remove($this->getLogFilePath($filePath));
		}

		return new Response($response);
	}


	/**
	 * @Route("/get-logs", name="get-logs")
	 */
	public function handleGetLogs(Request $request): Response
	{
		$directoryPath = $request->get('directoryPath');

		if ($directoryPath === null) {
			return new Response('error');
		}

		return new JsonResponse($this->getLogs($this->getLogFilePath($directoryPath)));
	}


	/**
	 * @Route("/view-log", name="view-log")
	 */
	public function handleViewLog(Request $request): Response
	{
		$file = $request->get('filePath');
		$response = 'error';
		$headers = [];

		if ($file !== null) {
			$file = $this->getLogFilePath($file);

			if (file_exists($file)) {
				$response = FileSystem::read($file);
				if (strpos($file, '.html') !== false) {
					$headers['Content-Type'] = 'text/html';

				} else {
					$headers['Content-Type'] = 'text/plain';
				}
			}
		}

		return new Response($response, 200, $headers);
	}


	private function getLogFilePath(string $filePath): string
	{
		$filePath = str_replace('..' . DIRECTORY_SEPARATOR, '', ltrim($filePath, DIRECTORY_SEPARATOR));
		return $this->getLogsDir() . '/' . $filePath;
	}


	private function getLogs(string $directory): string
	{
		if ( ! file_exists($this->getLogsDir())) {
			$this->getFileSystem()->mkdir($this->getLogsDir());
		}

		$logs = [];

		/** @var SplFileInfo $file */
		foreach($this->getFinder()->in($directory)->depth(0)->sortByType() as $file) {
			$isDir = $file->isDir();
			$fileRealPath = $file->getRealPath();

			if ( ! is_string($fileRealPath)) {
				continue;
			}

			$fileSize = filesize($fileRealPath);

			if ($fileSize === false) {
				continue;
			}

			$logs[] = [
				'directoryLogsCount' => $isDir ? $this->getLogsCount($fileRealPath) : null,
				'fileSize' => $isDir
					? $this->getLogsDirSize($fileRealPath)
					: $this->fileSizeIntoMegabytes($fileSize),
				'fileName' => $file->getRelativePathname(),
				'filePath' => str_replace($this->getLogsDir(), '', $fileRealPath),
				'isDir' => $isDir,
				'nestedLogs' => []
			];
		}

		return Json::encode($logs);
	}

}
