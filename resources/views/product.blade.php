@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Produk</span>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createProductModal">
                        Tambah Produk
                    </button>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Gambar</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            @if($product->item_image)
                                                <img src="{{ asset('storage/' . $product->item_image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-thumbnail" 
                                                     style="height: 50px;">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'Tidak ada kategori' }}</td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-warning me-2" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editProductModal{{ $product->id }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Edit Product Modal for each product -->
                                    <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Produk</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="edit_name{{ $product->id }}" class="form-label">Nama Produk</label>
                                                            <input type="text" class="form-control" id="edit_name{{ $product->id }}" name="name" value="{{ $product->name }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit_category_id{{ $product->id }}" class="form-label">Kategori</label>
                                                            <select class="form-select" id="edit_category_id{{ $product->id }}" name="category_id" required>
                                                                <option value="">Pilih Kategori</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit_price{{ $product->id }}" class="form-label">Harga (Rp)</label>
                                                            <input type="number" class="form-control" id="edit_price{{ $product->id }}" name="price" value="{{ $product->price }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit_description{{ $product->id }}" class="form-label">Deskripsi</label>
                                                            <textarea class="form-control" id="edit_description{{ $product->id }}" name="description" rows="3" required>{{ $product->description }}</textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit_item_image{{ $product->id }}" class="form-label">Gambar Produk</label>
                                                            <div class="mb-2">
                                                                @if($product->item_image)
                                                                    <img src="{{ asset('storage/' . $product->item_image) }}" 
                                                                        alt="Current image" 
                                                                        style="height: 100px;" 
                                                                        class="img-thumbnail">
                                                                @else
                                                                    <span class="badge bg-secondary">No Image</span>
                                                                @endif
                                                            </div>
                                                            <input type="file" class="form-control" id="edit_item_image{{ $product->id }}" name="item_image" accept="image/*">
                                                            <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Perbarui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data produk</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="item_image" class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control @error('item_image') is-invalid @enderror" id="item_image" name="item_image" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                        @error('item_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection