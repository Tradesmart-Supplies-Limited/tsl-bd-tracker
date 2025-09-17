<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Birthday Tracker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4>🎂 Birthday Tracker Dashboard</h4>
                <a href="create.html" class="btn btn-light btn-sm">+ Add New Birthday</a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div classs="col-md-6">
                        <input type="text" id="search" class="form-control" placeholder="Search by name or department">
                    </div>
                </div>
                <div id="dashboardAlert"></div>
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
                            <tr>
                                <td><img src="path/to/image.jpg" alt="Profile Picture" class="img-thumbnail" width="50"></td>
                                <td>John Doe</td>
                                <td>john.doe@example.com</td>
                                <td>1990-01-01</td>
                                <td>Engineering</td>
                                <td>Head Office</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editMember(1)">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteMember(1)" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Repeat <tr>...</tr> for more members -->
                        </tbody>
                    </table>
                </div>
    
            </div>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>