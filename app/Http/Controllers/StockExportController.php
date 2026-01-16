<?php

namespace App\Http\Controllers;

use App\Actions\Stock\ExportStockOverviewAction;
use App\Services\StockOverviewService;
use App\Dtos\Stock\StockOverviewDto;
use Illuminate\Http\Request;

class StockExportController extends Controller
{
    /**
     * Export stock overview to Excel.
     */
    public function exportExcel(
        Request $request,
        StockOverviewService $overviewService,
        ExportStockOverviewAction $exportAction
    ) {
        // Get filters from query parameters
        $filtersDto = StockOverviewDto::fromLivewire([
            'search' => $request->input('search', ''),
            'categoryId' => $request->input('categoryId', ''),
            'stockLevel' => $request->input('stockLevel', ''),
            'sortField' => $request->input('sortField', 'stock_quantity'),
            'sortDirection' => $request->input('sortDirection', 'asc'),
            'perPage' => 999999, // Get all records for export
        ]);

        $variants = $overviewService->getInventoryVariants($filtersDto->toRepositoryParams());
        $kpis = $overviewService->calculateKPIs();

        return $exportAction->toExcel($variants, $kpis);
    }

    /**
     * Export stock overview to PDF.
     */
    public function exportPdf(
        Request $request,
        StockOverviewService $overviewService,
        ExportStockOverviewAction $exportAction
    ) {
        // Get filters from query parameters
        $filtersDto = StockOverviewDto::fromLivewire([
            'search' => $request->input('search', ''),
            'categoryId' => $request->input('categoryId', ''),
            'stockLevel' => $request->input('stockLevel', ''),
            'sortField' => $request->input('sortField', 'stock_quantity'),
            'sortDirection' => $request->input('sortDirection', 'asc'),
            'perPage' => 999999, // Get all records for export
        ]);

        $variants = $overviewService->getInventoryVariants($filtersDto->toRepositoryParams());
        $kpis = $overviewService->calculateKPIs();

        return $exportAction->toPdf($variants, $kpis);
    }
}
