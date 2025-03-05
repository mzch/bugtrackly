<?php

namespace App\Repositories\TicketCategory;

use App\Models\TicketCategory;

class TicketCategoriesRepository implements TicketCategoriesRepositoryInterface
{

    public function getTicketCategoryById($id): ?TicketCategory
    {
        return TicketCategory::find($id);
    }
}
