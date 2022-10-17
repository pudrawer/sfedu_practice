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

class PageHandler
{
    private static $instance;

    public function handlePage()
    {
        try {
            $controller = AbstractRouter::chooseRouter($_SERVER['REQUEST_URI'] ?? '');

            $controller->execute();
        } catch (Exception $e) {
            $controller = new NotFoundController();
            $controller->execute();
        } catch (SelectionException $e) {
            $controller = new NotFoundController();
            $controller->execute();
        } catch (ForbiddenException $e) {
            $controller = new ForbiddenController();
            $controller->execute();
        } catch (ApiException $e) {
            $controller = new WrongApiController();
            $controller->execute();
        } catch (UserApiException $e) {
            $controller = new WrongApiController();
            $controller->execute(403);
        } catch (\Exception $e) {
            $controller = new WrongController();
            $controller->execute();
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
