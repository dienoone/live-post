<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;

trait ApiResponse
{
    protected function success(mixed $data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'error'   => null,
            'meta'    => null,
        ], $status);
    }

    protected function paginated(ResourceCollection $resource, string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $resource->resolve(),
            'error'   => null,
            'meta'    => self::buildMeta($resource->resource),
        ], $status);
    }

    protected function error(string $message = 'An error occurred', string $code = 'ERROR', int $status = 400, array $errors = [], ?string $errorId = null): JsonResponse
    {
        $error = [
            'code'      => $code,
            'message'   => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($errorId !== null) {
            $error['error_id'] = $errorId;
        }

        if (!empty($errors)) {
            $error['errors'] = $errors;
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
            'error'   => $error,
        ], $status);
    }

    private static function buildMeta(AbstractPaginator|AbstractCursorPaginator $paginator): array
    {
        // Cursor paginator — no total/page numbers
        if ($paginator instanceof AbstractCursorPaginator) {
            return [
                'type'          => 'cursor',
                'per_page'      => $paginator->perPage(),
                'next_cursor'   => $paginator->nextCursor()?->encode(),
                'prev_cursor'   => $paginator->previousCursor()?->encode(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
                'has_more'      => $paginator->hasMorePages(),
            ];
        }

        // Length-aware paginator (paginate())
        if (method_exists($paginator, 'total')) {
            return [
                'type'          => 'length_aware',
                'total'         => $paginator->total(),
                'per_page'      => $paginator->perPage(),
                'current_page'  => $paginator->currentPage(),
                'last_page'     => $paginator->lastPage(),
                'from'          => $paginator->firstItem(),
                'to'            => $paginator->lastItem(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
                'has_more'      => $paginator->hasMorePages(),
            ];
        }

        // Simple paginator (simplePaginate())
        return [
            'type'          => 'simple',
            'per_page'      => $paginator->perPage(),
            'current_page'  => $paginator->currentPage(),
            'from'          => $paginator->firstItem(),
            'to'            => $paginator->lastItem(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'has_more'      => $paginator->hasMorePages(),
        ];
    }
}
