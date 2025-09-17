document.addEventListener("DOMContentLoaded", function () {
    // Load members for dashboard
    if (document.getElementById("birthdayList")) {
        loadMembers();
        document.getElementById("search").addEventListener("input", function () {
            loadMembers(this.value);
        });
    }

    // Handle create form submission
    const form = document.getElementById("birthdayForm");
    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(form);
            fetch("../backend/api/index.php/create", {
                method: "POST",
                body: formData,
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.message) {
                        Swal.fire('Success!', data.message, 'success');
                        form.reset();
                    } else {
                        Swal.fire('Error!', data.error || "Error occurred", 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error!', 'Network error', 'error');
                });
        });
    }
});

// Load members and render table
function loadMembers(search = "") {
    fetch("../backend/api/index.php/view-all")
        .then((res) => res.json())
        .then((members) => {
            const tbody = document.getElementById("birthdayList");
            tbody.innerHTML = "";
            members
                .filter((m) =>
                    m.member_name.toLowerCase().includes(search.toLowerCase()) ||
                    m.department.toLowerCase().includes(search.toLowerCase())
                )
                .forEach((m) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>
                                ${m.picture ? `<img src="../backend/api/${m.picture}" alt="pic" style="width:40px;height:40px;border-radius:50%;">` : ""}
                            </td>
                            <td>${m.member_name}</td>
                            <td>${m.email}</td>
                            <td>${m.dob}</td>
                            <td>${m.department}</td>
                            <td>${m.branch}</td>
                            <td>
                              <button class="btn btn-sm btn-warning" onclick="editMember(${m.id})">
                                <i class="bi bi-pencil-square"></i> Edit
                              </button>
                              <button class="btn btn-sm btn-outline-danger" onclick="deleteMember(${m.id})" title="Delete">
                                <i class="bi bi-trash"></i>
                              </button>
                            </td>
                        </tr>
                    `;
                });
        });
}

// SweetAlert delete confirmation
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the record.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteMember(id);
        }
    });
}

// Delete member
function deleteMember(id) {
    fetch(`../backend/api/index.php/delete/${id}`, {
        method: "DELETE",
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.message) {
                Swal.fire('Deleted!', data.message, 'success');
            } else {
                Swal.fire('Error!', data.error || "Error occurred", 'error');
            }
            loadMembers();
        });
}

// Edit member
function editMember(id) {
    window.location.href = `edit.html?id=${id}`;
}