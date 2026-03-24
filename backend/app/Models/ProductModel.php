<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';

    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;
    protected $dateFormat     = 'datetime';

    protected $allowedFields = [
        'name',
        'description',
        'price',
        'category',
        'stock',
        'image',
        'status',
    ];

    // ── Get all active products ───────────────────────────────────────
    public function getActive(): array
    {
        return $this->where('status', 1)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    // ── Get active products by category ──────────────────────────────
    public function getByCategory(string $category): array
    {
        return $this->where('status', 1)
                    ->where('category', $category)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}