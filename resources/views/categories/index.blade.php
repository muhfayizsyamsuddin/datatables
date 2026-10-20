<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Kategori — Day 12 DataTables</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- DataTables Core CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --success: #059669;
            --success-light: #d1fae5;
            --danger: #dc2626;
            --danger-hover: #b91c1c;
            --warning: #d97706;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px rgba(0,0,0,.05);
            --shadow: 0 1px 3px rgba(0,0,0,.1), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md: 0 4px 6px rgba(0,0,0,.07), 0 2px 4px rgba(0,0,0,.06);
            --shadow-lg: 0 10px 15px rgba(0,0,0,.1), 0 4px 6px rgba(0,0,0,.05);
            --radius: .75rem;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 50%, #f5f3ff 100%);
            color: var(--gray-800);
            min-height: 100vh;
        }

        /* ── Header ─────────────────────────────── */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
        }
        .page-header h1 {
            color: #fff;
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
        }
        .page-header p {
            color: rgba(255,255,255,.8);
            margin: .25rem 0 0;
            font-size: .95rem;
        }

        /* ── Nav links ───────────────────────────── */
        .nav-links a {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: .88rem;
            transition: color .15s;
        }
        .nav-links a:hover { color: #fff; }
        .nav-links .sep { color: rgba(255,255,255,.4); margin: 0 .4rem; }

        /* ── Card ────────────────────────────────── */
        .card-main {
            background: #fff;
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }
        .card-main .card-body { padding: 1.5rem; }

        /* ── Buttons ─────────────────────────────── */
        .btn-primary-custom {
            background: var(--primary);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: .5rem;
            padding: .55rem 1.25rem;
            transition: all .2s;
        }
        .btn-primary-custom:hover {
            background: var(--primary-hover);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        /* ── Alert ───────────────────────────────── */
        .alert-floating {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            min-width: 320px;
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            animation: slideIn .35s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ── DataTables styling ──────────────────── */
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 1rem;
        }
        .dataTables_wrapper .dt-buttons .btn {
            font-size: .85rem;
            font-weight: 500;
            border-radius: .5rem;
            padding: .4rem .85rem;
            margin-right: .35rem;
            border: 1px solid var(--gray-200);
            background: #fff;
            color: var(--gray-700);
            transition: all .15s;
        }
        .dataTables_wrapper .dt-buttons .btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }
        .dataTables_wrapper .dt-buttons .btn i {
            margin-right: .3rem;
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        table.dataTable thead th {
            background: var(--gray-50);
            border-bottom: 2px solid var(--gray-200) !important;
            font-weight: 600;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: var(--gray-500);
            padding: .85rem .75rem !important;
        }
        table.dataTable tbody td {
            padding: .75rem !important;
            vertical-align: middle;
            font-size: .9rem;
            border-bottom: 1px solid var(--gray-100);
        }
        table.dataTable tbody tr:hover {
            background: var(--primary-light) !important;
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            font-size: .85rem;
            color: var(--gray-500);
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: .5rem;
            border: 1px solid var(--gray-200);
            padding: .35rem .75rem;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79,70,229,.15);
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #fff !important;
            border-radius: .4rem;
        }

        /* ── Badge ───────────────────────────────── */
        .badge-products {
            background: var(--success-light);
            color: var(--success);
            font-weight: 500;
            font-size: .8rem;
            padding: .3em .65em;
            border-radius: .4rem;
        }
        .badge-zero {
            background: var(--gray-100);
            color: var(--gray-500);
            font-weight: 500;
            font-size: .8rem;
            padding: .3em .65em;
            border-radius: .4rem;
        }

        /* ── Action buttons ──────────────────────── */
        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .4rem;
            border: none;
            transition: all .15s;
            font-size: .85rem;
        }
        .btn-edit {
            background: #fef3c7;
            color: var(--warning);
        }
        .btn-edit:hover {
            background: #fde68a;
            color: #92400e;
        }
        .btn-delete {
            background: #fee2e2;
            color: var(--danger);
        }
        .btn-delete:hover {
            background: #fca5a5;
            color: #7f1d1d;
        }

        /* ── Modal ───────────────────────────────── */
        .modal-content {
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
        }
        .modal-header {
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            border-radius: var(--radius) var(--radius) 0 0;
        }
        .modal-title {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .modal-footer {
            border-top: 1px solid var(--gray-200);
        }
        .form-label {
            font-weight: 500;
            font-size: .9rem;
            color: var(--gray-700);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.15);
        }

        /* ── Responsive tweaks ───────────────────── */
        @media (max-width: 768px) {
            .page-header h1 { font-size: 1.35rem; }
            .card-main .card-body { padding: 1rem; }
            .dt-buttons .btn { margin-bottom: .35rem; }
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="page-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1><i class="bi bi-tags me-2"></i>Manajemen Kategori</h1>
                <p class="nav-links">
                    <a href="{{ route('products.index') }}"><i class="bi bi-box-seam me-1"></i>Produk</a>
                    <span class="sep">›</span>
                    <span style="color:#fff;">Kategori</span>
                </p>
            </div>
            <button class="btn btn-light fw-semibold" onclick="openAddModal()">
                <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
            </button>
        </div>
    </div>
</div>

<!-- Success Alert -->
@if(session('success'))
<div class="alert alert-success alert-floating d-flex align-items-center" role="alert" id="alertSuccess">
    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Error Alert (hapus ditolak karena masih ada produk) -->
@if(session('error'))
<div class="alert alert-danger alert-floating d-flex align-items-center" role="alert" id="alertError">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
    <div>{{ session('error') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Validation Errors Alert -->
@if($errors->any())
<div class="alert alert-danger alert-floating" role="alert" id="alertValidation">
    <div class="d-flex align-items-center mb-1">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <strong>Validasi gagal:</strong>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    <ul class="mb-0 ps-3 mt-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Main Content -->
<div class="container mb-5">
    <div class="card card-main">
        <div class="card-body position-relative">
            <table id="categoriesTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jml Produk</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="categoryForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputName" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inputName" name="name" required maxlength="255" placeholder="Contoh: Smartphone, Laptop">
                    </div>
                    <div class="mb-3">
                        <label for="inputDescription" class="form-label">Deskripsi <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea class="form-control" id="inputDescription" name="description" rows="3" maxlength="1000" placeholder="Deskripsi singkat kategori ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom" id="btnSubmit">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Hapus Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">Apakah Anda yakin ingin menghapus kategori:</p>
                    <p class="fw-bold" id="deleteCategoryName"></p>
                    <p class="text-muted small mb-0">Kategori yang masih memiliki produk tidak dapat dihapus.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery (single instance) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables Core -->
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.bootstrap5.min.js"></script>

<!-- Button dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.12/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.12/vfs_fonts.js"></script>

<!-- Button types -->
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.colVis.min.js"></script>

<script>
    // ── Server-Side Export Action Handler ──────────
    var isExporting = false;

    function serverSideExportAction(e, dt, button, config) {
        if (isExporting) { return; }
        isExporting = true;

        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        var oldLength = dt.settings()[0]._iDisplayLength;

        var cleanedUp = false;
        function finishExport() {
            if (cleanedUp) { return; }
            cleanedUp = true;
            try { if (self && typeof self.processing === 'function') self.processing(false); } catch(err){}
            try { if (dt && typeof dt.button === 'function') dt.button(button).processing(false); } catch(err){}
            var $btn = $(button);
            $btn.removeClass('processing disabled dt-button-processing');
            $btn.prop('disabled', false);
            $btn.find('.dt-button-processing, .spinner-border, .spinner-grow').remove();
            dt.processing(false);
            $('#categoriesTable_processing').hide();
            $('.dt-processing').hide();
            $('.dataTables_processing').hide();
            isExporting = false;
        }

        dt.one('xhr', function (e, settings, json, xhr) {
            if (!json) { finishExport(); }
        });

        dt.one('preXhr', function (e, s, data) {
            data.start = 0;
            data.length = -1;
            data.export = 1;

            dt.one('draw', function (e, settings) {
                var btn = $(button);
                try {
                    if (btn.hasClass('buttons-csv') || button[0].className.indexOf('buttons-csv') >= 0) {
                        if ($.fn.dataTable.ext.buttons.csvHtml5.available(dt, config)) {
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config);
                        }
                    } else if (btn.hasClass('buttons-excel') || button[0].className.indexOf('buttons-excel') >= 0) {
                        if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
                        }
                    } else if (btn.hasClass('buttons-pdf') || button[0].className.indexOf('buttons-pdf') >= 0) {
                        if ($.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config)) {
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config);
                        }
                    } else if (btn.hasClass('buttons-print') || button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action.call(self, e, dt, button, config);
                        setTimeout(finishExport, 300);
                    }
                } catch(err) {
                    console.error('Export generation error:', err);
                    finishExport();
                }

                settings._iDisplayStart = oldStart;
                settings._iDisplayLength = oldLength;

                dt.one('preXhr', function (e, s, data) {
                    data.start = oldStart;
                    data.length = oldLength;
                    delete data.export;
                });

                dt.one('draw', function () { finishExport(); });
                setTimeout(function () { dt.ajax.reload(null, false); }, 100);
                setTimeout(finishExport, 1000);
            });
        });

        dt.ajax.reload(null, false);
    }

    // ── DataTables Initialization ──────────────────
    let categoriesTable;

    $(document).ready(function () {
        categoriesTable = $('#categoriesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("categories.data") }}',
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id', width: '50px' },
                { data: 'name', name: 'name' },
                {
                    data: 'description',
                    name: 'description',
                    render: function (data) {
                        if (!data) return '<span class="text-muted fst-italic">—</span>';
                        return '<span title="' + escapeHtml(data) + '">' + escapeHtml(data.length > 60 ? data.substring(0, 60) + '…' : data) + '</span>';
                    }
                },
                {
                    data: 'products_count',
                    name: 'products_count',
                    className: 'text-center',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        if (data > 0) {
                            return '<span class="badge-products">' + data + ' produk</span>';
                        }
                        return '<span class="badge-zero">0 produk</span>';
                    }
                },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (data, type, row) {
                        return '<button class="btn-action btn-edit me-1" onclick="openEditModal(' + data + ')" title="Edit">' +
                                   '<i class="bi bi-pencil"></i>' +
                               '</button>' +
                               '<button class="btn-action btn-delete" onclick="confirmDelete(' + data + ', \'' + escapeHtml(row.name).replace(/'/g, "\\'") + '\')" title="Hapus">' +
                                   '<i class="bi bi-trash"></i>' +
                               '</button>';
                    }
                }
            ],
            dom: '<"d-flex flex-wrap justify-content-between align-items-center mb-3"<"dt-buttons"B><"dataTables_filter"f>rtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: '<i class="bi bi-filetype-csv"></i> CSV',
                    className: 'btn btn-sm',
                    exportOptions: { columns: [0, 1, 2, 3, 4] },
                    action: serverSideExportAction
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-sm',
                    exportOptions: { columns: [0, 1, 2, 3, 4] },
                    action: serverSideExportAction
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-sm',
                    exportOptions: { columns: [0, 1, 2, 3, 4] },
                    customize: function (doc) { doc.defaultStyle.font = 'Roboto'; },
                    action: serverSideExportAction
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Print',
                    className: 'btn btn-sm',
                    exportOptions: { columns: [0, 1, 2, 3, 4] },
                    action: serverSideExportAction
                },
                {
                    extend: 'colvis',
                    text: '<i class="bi bi-layout-three-columns"></i> Kolom',
                    className: 'btn btn-sm',
                    columns: [0, 1, 2, 3, 4]
                }
            ],
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            language: {
                processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Memuat...',
                search: '',
                searchPlaceholder: 'Cari kategori...',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ kategori',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(disaring dari _MAX_ total)',
                paginate: { previous: '‹', next: '›' },
                emptyTable: 'Belum ada kategori. Klik "Tambah Kategori" untuk menambahkan.',
                zeroRecords: 'Tidak ada kategori yang cocok dengan pencarian.'
            }
        });

        // Auto-dismiss alerts after 4 seconds
        setTimeout(function () {
            $('#alertSuccess, #alertError').fadeOut('slow', function () { $(this).remove(); });
        }, 4000);
    });

    // ── CRUD Modal Functions ───────────────────────
    function openAddModal() {
        $('#modalTitle').text('Tambah Kategori');
        $('#categoryForm').attr('action', '{{ route("categories.store") }}');
        $('#formMethod').val('POST');
        $('#categoryForm')[0].reset();
        new bootstrap.Modal('#categoryModal').show();
    }

    function openEditModal(id) {
        $.getJSON('/categories/' + id + '/edit', function (category) {
            $('#modalTitle').text('Edit Kategori');
            $('#categoryForm').attr('action', '/categories/' + id);
            $('#formMethod').val('PUT');
            $('#inputName').val(category.name);
            $('#inputDescription').val(category.description || '');
            new bootstrap.Modal('#categoryModal').show();
        }).fail(function () {
            alert('Gagal memuat data kategori.');
        });
    }

    function confirmDelete(id, name) {
        $('#deleteForm').attr('action', '/categories/' + id);
        $('#deleteCategoryName').text(name);
        new bootstrap.Modal('#deleteModal').show();
    }

    // ── Utility ────────────────────────────────────
    function escapeHtml(text) {
        let div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
</script>
</body>
</html>
