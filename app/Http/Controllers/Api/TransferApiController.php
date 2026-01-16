<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StoreTransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransferApiController extends Controller
{
    public function __construct(
        private StoreTransferService $transferService
    ) {}

    /**
     * Get all transfers
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $storeId = $request->query('store_id');
        $status = $request->query('status');
        $perPage = (int) $request->query('per_page', 15);

        // This would need to be implemented in StoreTransferRepository
        // For now, return a basic response
        return response()->json([
            'success' => true,
            'message' => 'Transfers list (implementation needed in repository)',
        ]);
    }

    /**
     * Get a specific transfer
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $transfer = $this->transferService->findTransfer($id);

            if (!$transfer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transfer not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $transfer->load(['fromStore', 'toStore', 'items.variant.product', 'requester', 'approver', 'receiver']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new transfer
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'from_store_id' => 'required|exists:stores,id',
            'to_store_id' => 'required|exists:stores,id|different:from_store_id',
            'expected_arrival_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();
            $data['requested_by'] = auth()->id();

            $transfer = $this->transferService->createTransfer($data);

            return response()->json([
                'success' => true,
                'message' => 'Transfer created successfully',
                'data' => $transfer,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve a transfer
     *
     * @param int $id
     * @return JsonResponse
     */
    public function approve(int $id): JsonResponse
    {
        try {
            $transfer = $this->transferService->approveTransfer($id, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Transfer approved successfully',
                'data' => $transfer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Receive a transfer
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function receive(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $transfer = $this->transferService->receiveTransfer(
                $id,
                $request->quantities,
                auth()->id(),
                $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Transfer received successfully',
                'data' => $transfer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel a transfer
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $transfer = $this->transferService->cancelTransfer($id, auth()->id(), $request->reason);

            return response()->json([
                'success' => true,
                'message' => 'Transfer cancelled successfully',
                'data' => $transfer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
