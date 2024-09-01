<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel AJAX CRUD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Laravel AJAX CRUD</h2>
    <button class="btn btn-success mb-3" id="createNewProduct">Add Product</button>
    <table class="table table-bordered" id="productsTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="ajaxProductModal" tabindex="-1" aria-labelledby="ajaxProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxProductModalLabel">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Fetch Products
    function fetchProducts() {
        $.ajax({
            url: "{{ route('products.get') }}",
            method: "GET",
            success: function (data) {
                $('#productsTable tbody').html('');
                $.each(data, function (key, product) {
                    $('#productsTable tbody').append('<tr><td>' + product.name + '</td><td>' + product.description + '</td><td>' + product.price + '</td><td><button class="btn btn-info btn-sm editProduct" data-id="' + product.id + '">Edit</button> <button class="btn btn-danger btn-sm deleteProduct" data-id="' + product.id + '">Delete</button></td></tr>');
                });
            }
        });
    }
    fetchProducts();

    // Show Create Product Modal
    $('#createNewProduct').click(function () {
        $('#productForm').trigger("reset");
        $('#ajaxProductModalLabel').text("Add New Product");
        $('#ajaxProductModal').modal('show');
    });

    // Save Product
    $('#productForm').on('submit', function (e) {
        e.preventDefault();
        let formData = $(this).serialize();
        let productId = $('#product_id').val();

        $.ajax({
            url: productId ? "/products/" + productId : "{{ route('products.store') }}",
            type: productId ? "PUT" : "POST",
            data: formData,
            success: function (response) {
                $('#ajaxProductModal').modal('hide');
                fetchProducts();
                alert(response.success);
            },
            error: function (error) {
                console.log(error);
                alert('Something went wrong!');
            }
        });
    });

    // Edit Product
    $('body').on('click', '.editProduct', function () {
        var productId = $(this).data('id');
        $.get("/products/" + productId, function (data) {
            $('#ajaxProductModalLabel').text("Edit Product");
            $('#ajaxProductModal').modal('show');
            $('#product_id').val(data.id);
            $('#name').val(data.name);
            $('#description').val(data.description);
            $('#price').val(data.price);
        });
    });

    // Delete Product
    $('body').on('click', '.deleteProduct', function () {
        var productId = $(this).data('id');
        if (confirm("Are you sure you want to delete this product?")) {
            $.ajax({
                url: "/products/" + productId,
                type: "DELETE",
                success: function (response) {
                    fetchProducts();
                    alert(response.success);
                },
                error: function (error) {
                    console.log(error);
                    alert('Something went wrong!');
                }
            });
        }
    });
});
</script>
</body>
</html>
