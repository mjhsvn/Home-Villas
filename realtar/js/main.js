function updatePaginationInfo(currentPage, totalPages) {
    $('#pagination-info').text('Showing page ' + currentPage + ' out of ' + totalPages);
}

updatePaginationInfo(2, 5);