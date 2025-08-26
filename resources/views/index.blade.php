<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Tracker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4>🎂 Birthday Tracker Dashboard</h4>
                <a href="create.html" class="btn btn-light btn-sm">+ Add New Birthday</a>
            </div>
            <div class="card-body">

                <!-- Search -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="search" class="form-control" placeholder="Search by name or department">
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Date of Birth</th>
                                <th>Department</th>
                                <th>Branch</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="birthdayList">
                            <!-- Sample data (replace with Laravel API data) -->
                            <tr>
                                <td><img src="https://via.placeholder.com/50" class="rounded-circle" alt="Profile"></td>
                                <td>John Doe</td>
                                <td>john@example.com</td>
                                <td>1990-05-15</td>
                                <td>Sales</td>
                                <td>Kampala</td>
                                <td>
                                    <a href="edit.html?id=1" class="btn btn-warning btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm" onclick="deleteMember(1)">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="https://via.placeholder.com/50" class="rounded-circle" alt="Profile"></td>
                                <td>Jane Smith</td>
                                <td>jane@example.com</td>
                                <td>1988-09-22</td>
                                <td>IT</td>
                                <td>Entebbe</td>
                                <td>
                                    <a href="edit.html?id=2" class="btn btn-warning btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm" onclick="deleteMember(2)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Placeholder delete function
        function deleteMember(id) {
            if (confirm("Are you sure you want to delete this record?")) {
                alert("Deleting member with ID: " + id);
                // In Laravel: send DELETE request to /api/delete/{id}
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>