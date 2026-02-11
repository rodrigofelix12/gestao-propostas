<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Database\Exceptions\DatabaseException;

abstract class BaseApiController extends ResourceController
{
    protected function handle(callable $callback)
    {
        try {

            $result = $callback();

            return $this->respond($result);

        } catch (PageNotFoundException $e) {

            return $this->failNotFound($e->getMessage());

        } catch (\DomainException $e) {

            return $this->fail($e->getMessage(), 409);

        } catch (\InvalidArgumentException $e) {

            return $this->failValidationErrors($e->getMessage());

        } catch (DatabaseException $e) {

            return $this->failServerError('Erro de banco de dados');

        } catch (\Throwable $e) {

            return $this->failServerError($e->getMessage());
        }
    }
}