<?php

namespace App\Core;

use App\Core;
use App\Core\Blocks\NotFoundBlock;
use App\Core\Controllers\Api\WrongApiController;
use App\Core\Controllers\Web\ForbiddenController;
use App\Core\Controllers\Web\NotFoundController;
use App\Core\Controllers\Web\WrongController;
use App\Core\Exception\ApiException;
use App\Core\Exception\Exception;
use App\Core\Exception\ForbiddenException;
use App\Core\Exception\SelectionException;
use App\Core\Exception\UserApiException;
use App\Core\Models\DiContainer;
use App\Core\Models\Logger;
use Laminas\Di\Di;
use Laminas\Di\Exception\RuntimeException;

class PageHandler
{
    private $di;

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    public function handlePage()
    {
        $diContainer = $this->di->get(
            DiContainer::class,
            ['di' => $this->di]
        );
        $diContainer->assemble();

        $logger = $this->di->get(Logger::class);

        try {
            /** @var \App\Core\Router\RouterPool $controller */
            $controller = $this->di->get(Core\Router\RouterPool::class);
            $controller = $controller->chooseRouter($_SERVER['REQUEST_URI'] ?? '');

            $controller->execute();
        } catch (Exception $e) {
            $controller = $this->di->get(NotFoundController::class, [
                'block' => NotFoundBlock::class,
            ]);
            $controller->execute();
            $logger->putWarning($e->getMessage());
        } catch (SelectionException $e) {
            $controller = $this->di->get(NotFoundController::class);
            $controller->execute();
            $logger->putWarning($e->getMessage());
        } catch (ForbiddenException $e) {
            $controller = $this->di->get(ForbiddenController::class);
            $controller->execute();
            $logger->putWarning($e->getMessage());
        } catch (ApiException $e) {
            $controller = $this->di->get(WrongApiController::class);
            $controller->execute();
            $logger->putWarning($e->getMessage());
        } catch (UserApiException $e) {
            $controller = $this->di->get(WrongApiController::class);
            $controller->execute(403);
            $logger->putWarning($e->getMessage());
        } catch (RuntimeException $e) {
            $logger->putError($e->getMessage());
        } catch (\Exception $e) {
            $controller = $this->di->get(WrongController::class);
            $controller->execute();
            $logger->putWarning($e->getMessage());
        }
    }
}
