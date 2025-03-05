<?php

namespace App\Repositories\TicketCategory;

use App\Models\TicketCategory;

interface TicketCategoriesRepositoryInterface
{
    public function getTicketCategoryById($id) :?TicketCategory;
}
