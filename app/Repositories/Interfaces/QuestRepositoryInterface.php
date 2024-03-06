<?php

namespace App\Repositories\Interfaces;

use App\Repositories\RepositoryInterface;

interface QuestRepositoryInterface extends RepositoryInterface
{
    public function getAllChapter();
    public function getAllQuestByChapter( array $chapter );
    public function createQuestByUser( array $params );
    public function updateQuestByUser( array $params );
}