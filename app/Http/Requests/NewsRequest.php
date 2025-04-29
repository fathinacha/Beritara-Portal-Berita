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
        return [
            'title' => 'required|string|min:10|max:255',
            'content' => 'required|string|min:100',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Judul berita wajib diisi',
            'title.min' => 'Judul berita minimal 10 karakter',
            'title.max' => 'Judul berita maksimal 255 karakter',
            'content.required' => 'Konten berita wajib diisi',
            'content.min' => 'Konten berita minimal 100 karakter',
            'category_id.required' => 'Kategori berita wajib dipilih',
            'category_id.exists' => 'Kategori yang dipilih tidak valid',
            'image.required' => 'Gambar berita wajib diupload',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 2MB'
        ];
    }
}