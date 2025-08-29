<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Tracker - Add Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4>Add Team Member Birthday</h4>
            </div>
            <div class="card-body">
                <form action="/api/create" method="POST" enctype="multipart/form-data">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="member_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="member_name" name="member_name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Date of Birth -->
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>

                    <!-- Department -->
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" id="department" name="department" required>
                            <option value="">Select Department</option>
                            <option value="Operations">Operations</option>
                            <option value="HR">HR</option>
                            <option value="IT">IT</option>
                        </select>
                    </div>

                    <!-- Branch -->
                    <div class="mb-3">
                        <label for="branch" class="form-label">Branch</label>
                        <select class="form-select" id="branch" name="branch" required>
                            <option value="">Select Branch</option>
                            <option value="Ndola, Zambia">Ndola, Zambia</option>
                            <option value="Kampala, Uganda">Kampala, Uganda</option>
                        </select>
                    </div>

                    <!-- Picture -->
                    <div class="mb-3">
                        <label for="picture" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-success">Save Birthday</button>
                    <a href="index.html" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>