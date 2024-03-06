<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface UserEquipmentRepositoryInterface extends RepositoryInterface
{
    public function addModel( $model );

    public function exists( array $params );

    public function getInventoryItemsByUserId(array $params);

    public function getEquippedItemsByUserId(int $userId);

    public function removeEquippedItemById(int $ueId);

    public function useEquipmentById(int $ueId, int $position);
}