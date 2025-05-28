
function openCreateModal() {
    $('#createModal').removeClass('hidden');
    $('body').css('overflow', 'hidden');
}

function closeCreateModal() {
    $('#createModal').addClass('hidden');
    $('body').css('overflow', 'auto');
}

function openEditModal(id, name, description, isActive, sortOrder) {
    $('#editForm').attr('action', `/admin/help-center-categories/${id}`);
    $('#edit_name').val(name);
    $('#edit_description').val(description);
    $('#edit_is_active').prop('checked', isActive === 'true');
    $('#edit_sort_order').val(sortOrder);
    $('#editModal').removeClass('hidden');
    $('body').css('overflow', 'hidden');
}

function closeEditModal() {
    $('#editModal').addClass('hidden');
    $('body').css('overflow', 'auto');
}

$(document).ready(function () {
    $('.edit-category-btn').on('click', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const description = $(this).data('description');
        const isActive = $(this).data('active');
        const sortOrder = $(this).data('sort');
        openEditModal(id, name, description, isActive, sortOrder);
    });


    $('#openCreateModel').on('click', function (e) {
        console.log("clicked")
        openCreateModal();
    });
    $('#createModal').on('click', function (e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });

    $('#editModal').on('click', function (e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });
});
