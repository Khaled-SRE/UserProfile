<?php

namespace App\Services;

interface BaseServiceInterface
{
    public function index();

    public function show($id);

    public function store(array $data);

    public function update(array $data, $id, $resource = true);

    public function delete($id);

    public function setResource($resource);

    public function setPagination($pagination = 20);

    public function setRelations($relations = []);
}
