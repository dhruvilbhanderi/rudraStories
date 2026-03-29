<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminBookController extends Controller
{
    public function booksPage()
    {
        $books = DB::table('books_store')->orderBy('id', 'desc')->get();

        return view('admin.books')->with([
            'books' => $books,
        ]);
    }

    public function ordersPage()
    {
        $orders = DB::table('book_orders')->orderBy('id', 'desc')->limit(100)->get();

        return view('admin.orders')->with([
            'orders' => $orders,
        ]);
    }

    public function addBook(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'access_type' => ['required', 'in:free,paid'],
            'book_type' => ['required', 'in:digital,physical,both'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'resale_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $imageName = null;
        if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $image = $request->file('cover_image');
            $baseName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safe = Str::slug((string) $baseName);
            if ($safe === '') {
                $safe = 'cover';
            }

            $ext = strtolower($image->getClientOriginalExtension() ?: 'jpg');
            $imageName = $safe . '-' . time() . '-' . Str::random(6) . '.' . $ext;
            $dest = public_path('bookImages');
            if (! File::exists($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $image->move($dest, $imageName);
        }

        $pdfFileName = null;
        if ($request->hasFile('pdf_file') && $request->file('pdf_file')->isValid()) {
            $pdf = $request->file('pdf_file');
            $baseName = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
            $safe = Str::slug((string) $baseName);
            if ($safe === '') {
                $safe = 'book';
            }

            $pdfFileName = $safe . '-' . time() . '.pdf';
            $dest = public_path('bookPdfs');
            if (!File::exists($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $pdf->move($dest, $pdfFileName);
        }

        DB::table('books_store')->insert([
            'title' => strip_tags($request->title),
            'author' => strip_tags($request->author),
            'description' => strip_tags((string) $request->description),
            'price' => $request->price,
            'stock' => $request->stock,
            'access_type' => $request->access_type,
            'book_type' => $request->book_type,
            'cover_image' => $imageName,
            'pdf_file' => $pdfFileName,
            'allow_resale' => $request->has('allow_resale') ? 1 : 0,
            'resale_price' => $request->resale_price ?: null,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/books')->with('admin_book_success', 'Book added.');
    }

    public function updateBook(Request $request, $bookId)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'access_type' => ['required', 'in:free,paid'],
            'book_type' => ['required', 'in:digital,physical,both'],
            'description' => ['nullable', 'string'],
            'resale_price' => ['nullable', 'numeric', 'min:0'],
            'cover_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $updateData = [
            'title' => strip_tags($request->title),
            'author' => strip_tags($request->author),
            'description' => strip_tags((string) $request->description),
            'price' => $request->price,
            'stock' => $request->stock,
            'access_type' => $request->access_type,
            'book_type' => $request->book_type,
            'allow_resale' => $request->has('allow_resale') ? 1 : 0,
            'resale_price' => $request->resale_price ?: null,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'updated_at' => now(),
        ];

        if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $image = $request->file('cover_image');
            $baseName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safe = Str::slug((string) $baseName);
            if ($safe === '') {
                $safe = 'cover';
            }

            $ext = strtolower($image->getClientOriginalExtension() ?: 'jpg');
            $imageName = $safe . '-' . time() . '-' . Str::random(6) . '.' . $ext;
            $dest = public_path('bookImages');
            if (! File::exists($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $image->move($dest, $imageName);
            $updateData['cover_image'] = $imageName;
        }

        if ($request->hasFile('pdf_file') && $request->file('pdf_file')->isValid()) {
            $pdf = $request->file('pdf_file');
            $baseName = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
            $safe = Str::slug((string) $baseName);
            if ($safe === '') {
                $safe = 'book';
            }

            $pdfFileName = $safe . '-' . time() . '.pdf';
            $dest = public_path('bookPdfs');
            if (!File::exists($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $pdf->move($dest, $pdfFileName);
            $updateData['pdf_file'] = $pdfFileName;
        }

        DB::table('books_store')->where('id', $bookId)->update($updateData);

        return redirect('/admin/books')->with('admin_book_success', 'Book updated.');
    }

    public function deleteBook($bookId)
    {
        DB::table('books_store')->where('id', $bookId)->delete();
        return redirect('/admin/books')->with('admin_book_success', 'Book deleted.');
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'order_status' => ['required', 'in:processing,shipped,delivered,cancelled'],
            'payment_status' => ['required', 'in:pending,paid,failed,refunded'],
        ]);

        DB::table('book_orders')->where('id', $orderId)->update([
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
            'updated_at' => now(),
        ]);

        return redirect('/admin/orders')->with('admin_book_success', 'Order updated.');
    }
}
