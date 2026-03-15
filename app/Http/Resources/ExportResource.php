<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $downloadUrl = null;
        if ($this->status === 'completed' && $this->file_path) {
            $downloadUrl = route('api.exports.download', $this->resource);
        }

        return [
            'id' => $this->id,
            'type' => $this->type,
            'row_count' => $this->row_count,
            'status' => $this->status,
            'download_url' => $downloadUrl,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
