<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->has('status') && !$this->has('title')) {
            return [
                'status' => 'required|in:draft,published'
            ];
        }

        $rules = [
            'title' => 'required|string|min:10|max:255',
            'content' => 'required|string|min:100',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published'
        ];

        // Validasi gambar
        if ($this->isMethod('POST')) {
            
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=600,min_height=400';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=600,min_height=400';
        }
        return $rules;
    }

    // Tambahkan pesan error kustom
    public function messages()
    {
        return [
            'image.required' => 'Gambar berita wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'image.dimensions' => 'Dimensi gambar minimal 600x400 pixel.'
        ];
    }
}