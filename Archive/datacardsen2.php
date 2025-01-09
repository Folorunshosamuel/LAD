<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Senate Members</title>
    <link rel="stylesheet" href="lad.css">
    <style>
        .search-bar {
            width: 100%;
            max-width: 500px;
            margin-bottom: 20px;
            font-size: 1.1em;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Senate Members</h2>

    <!-- Search Input -->
    <input type="text" id="search" class="search-bar" placeholder="Search by name or constituency">
    
    <!-- Members List -->
    <div class="row" id="membersList"></div>

    <!-- Pagination Links -->
    <nav>
        <ul class="pagination" id="paginationLinks"></ul>
    </nav>
</div>

<script>
$(document).ready(function () {
    let currentPage = 1;
    const recordsPerPage = 8;

    // Fetch data with optional search and page params
    function fetchData(search = '', page = 1) {
        $.ajax({
            url: 'senMembers_ajax.php',
            method: 'GET',
            data: { search: search, page: page },
            dataType: 'json',
            success: function (data) {
                renderMembers(data.senMembers);
                renderPagination(data.totalPages, page);
            }
        });
    }

    // Render members as cards
    function renderMembers(members) {
        const container = $('#membersList');
        container.empty();
        members.forEach(member => {
            container.append(`
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="${member.image || 'uploads/avatar.webp'}" class="mb-3" style="width: 100px; height: 100px;" alt="">
                            <h6 class="tx-13 tx-inverse tx-semibold mg-b-0">${member.name}</h6>
                            <span class="d-block tx-11 text-muted">${member.constituency}</span>
                            <a href="profile.php?id=${member.id}" class="btn btn-primary mt-2">View Profile</a>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    // Render pagination links with max 5 visible pages
    function renderPagination(totalPages, currentPage) {
        const paginationContainer = $('#paginationLinks');
        paginationContainer.empty();

        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);

        if (currentPage > 1) {
            paginationContainer.append(`<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>`);
        }

        for (let i = startPage; i <= endPage; i++) {
            paginationContainer.append(`<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`);
        }

        if (currentPage < totalPages) {
            paginationContainer.append(`<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>`);
        }
    }

    // Search bar event listener for dynamic searching
    $('#search').on('input', function () {
        const searchValue = $(this).val();
        currentPage = 1;
        fetchData(searchValue, currentPage);
    });

    // Pagination link click event listener
    $('#paginationLinks').on('click', '.page-link', function (e) {
        e.preventDefault();
        currentPage = $(this).data('page');
        fetchData($('#search').val(), currentPage);
    });

    // Initial fetch
    fetchData();
});
</script>
</body>
</html>
