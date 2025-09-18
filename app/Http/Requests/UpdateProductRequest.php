<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin() || $this->user()->isStaff();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori harus dipilih.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
            'name.required' => 'Nama produk harus diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'sku.unique' => 'SKU sudah digunakan oleh produk lain.',
            'cost_price.required' => 'Harga beli harus diisi.',
            'cost_price.min' => 'Harga beli tidak boleh negatif.',
            'selling_price.required' => 'Harga jual harus diisi.',
            'selling_price.min' => 'Harga jual tidak boleh negatif.',
            'stock_quantity.required' => 'Jumlah stok harus diisi.',
            'stock_quantity.min' => 'Jumlah stok tidak boleh negatif.',
            'minimum_stock.required' => 'Minimal stok harus diisi.',
            'minimum_stock.min' => 'Minimal stok tidak boleh negatif.',
            'unit.required' => 'Satuan harus diisi.',
        ];
    }
}