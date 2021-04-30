<?php
/**
 * Created date 26.03.2021
 * @author Sergey Tyrgola <ts@goldcarrot.ru>
 */

namespace Goldcarrot\Base\Traits;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

trait PaginationTrait
{
    public function jsonPagination(LengthAwarePaginator $paginator, $items = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' =>  $items ?: $paginator->items(),
            'pagination' => [
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
                'firstPageUrl' => $paginator->url(1),
                'lastPageUrl' => $paginator->url($paginator->lastPage()),
                'nextPageUrl' => $paginator->nextPageUrl(),
                'prevPageUrl' => $paginator->previousPageUrl(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ]);
    }
}
