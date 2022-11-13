<?php

namespace App;

use App\Blocks\NotFoundBlock;
use App\Controllers\Api\WrongApiController;
use App\Controllers\Web\ForbiddenController;
use App\Controllers\Web\NotFoundController;
use App\Controllers\Web\WrongController;
use App\Exception\ApiException;
use App\Exception\Exception;
use App\Exception\ForbiddenException;
use App\Exception\SelectionException;
use App\Exception\UserApiException;
use App\Models\DiContainer;
use App\Models\Logger;
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
            /** @var \App\Router\RouterPool $controller */
            $controller = $this->di->get(\App\Router\RouterPool::class);
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
