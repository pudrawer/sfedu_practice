<?php

namespace App;

use App\Api\Controllers\WrongApiController;
use App\Controllers\ForbiddenWebController;
use App\Controllers\NotFoundWebController;
use App\Controllers\WrongWebController;
use App\Exception\ApiException;
use App\Exception\ForbiddenException;
use App\Exception\Exception;
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
            $controller = new NotFoundWebController();
            $controller->execute();
        } catch (SelectionException $e) {
            $controller = new NotFoundWebController();
            $controller->execute();
        } catch (ForbiddenException $e) {
            $controller = new ForbiddenWebController();
            $controller->execute();
        } catch (ApiException $e) {
            $controller = new WrongApiController();
            $controller->execute();
        } catch (UserApiException $e) {
            $controller = new WrongApiController();
            $controller->execute(403);
        } catch (\Exception $e) {
            $controller = new WrongWebController();
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
