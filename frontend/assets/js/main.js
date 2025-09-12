document.addEventListener("DOMContentLoaded", function () {
    // Load members for dashboard
    if (document.getElementById("birthdayList")) {
        loadMembers();

        // Search functionality
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
                    const alertDiv = document.getElementById("formAlert");
                    if (data.message) {
                        alertDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        form.reset();
                    } else {
                        alertDiv.innerHTML = `<div class="alert alert-danger">${data.error || "Error occurred"}</div>`;
                    }
                })
                .catch(() => {
                    document.getElementById("formAlert").innerHTML =
                        `<div class="alert alert-danger">Network error</div>`;
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
                                <button class="btn btn-danger btn-sm" onclick="deleteMember(${m.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
        });
}

// Delete member
function deleteMember(id) {
    if (!confirm("Are you sure you want to delete this record?")) return;
    fetch(`../backend/api/index.php/delete/${id}`, {
        method: "DELETE",
    })
        .then((res) => res.json())
        .then((data) => {
            const alertDiv = document.getElementById("dashboardAlert");
            if (data.message) {
                alertDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            } else {
                alertDiv.innerHTML = `<div class="alert alert-danger">${data.error || "Error occurred"}</div>`;
            }
            loadMembers();
        });
}