<?php

namespace App;

use App\Controllers\Api\WrongApiController;
use App\Controllers\Web\ForbiddenController;
use App\Controllers\Web\NotFoundController;
use App\Controllers\Web\WrongController;
use App\Exception\ApiException;
use App\Exception\Exception;
use App\Exception\ForbiddenException;
use App\Exception\SelectionException;
use App\Exception\UserApiException;
use App\Router\AbstractRouter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class PageHandler
{
    private static $instance;

    public function handlePage()
    {
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler(
            APP_ROOT . '/var/log/warning.log',
            Logger::WARNING
        ));
        $log->pushHandler(new StreamHandler(
            APP_ROOT . '/var/log/errors.log',
            Logger::ERROR
        ));

        try {
            $controller = AbstractRouter::chooseRouter($_SERVER['REQUEST_URI'] ?? '');

            $controller->execute();
        } catch (Exception $e) {
            $controller = new NotFoundController();
            $controller->execute();
            $log->warning($e->__toString());
        } catch (SelectionException $e) {
            $controller = new NotFoundController();
            $controller->execute();
            $log->warning($e->__toString());
        } catch (ForbiddenException $e) {
            $controller = new ForbiddenController();
            $controller->execute();
            $log->warning($e->__toString());
        } catch (ApiException $e) {
            $controller = new WrongApiController();
            $controller->execute();
            $log->warning($e->__toString());
        } catch (UserApiException $e) {
            $controller = new WrongApiController();
            $controller->execute(403);
            $log->warning($e->__toString());
        } catch (\Exception $e) {
            $controller = new WrongController();
            $controller->execute();
            $log->warning($e->__toString());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = new self();
    }
}
