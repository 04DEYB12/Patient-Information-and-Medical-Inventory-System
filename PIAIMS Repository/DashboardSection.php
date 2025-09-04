<section class="content-section active" id="dashboardSection">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <h3>No. of Students</h3>
                <i class='bx bx-user-plus'></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $student_count; ?></div>
                <p class="stat-change">Student as patient</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <h3>Daily Check In Counts</h3>
                <i class='bx bx-calendar-check'></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $clinicPersonnel_count; ?></div>
                <p class="stat-change">Students checked in today</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <h3>No. of Admin(s)</h3>
                <i class='bx bx-error'></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo $clinicPersonnel_count; ?></div>
                <p class="stat-change">Total of Admin</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <h3>No. of Staff(s)</h3>
                <i class='bx bx-receipt'></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">15</div>
                <p class="stat-change">Total of Staff</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h2>Common Reason For Visit</h2>
        </div>
        <div class="card-content" style="padding: 20px;">
            <div style="height: 300px; position: relative;">
                <canvas id="visitReasonsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Recent Clinic Visits</h2>
            <div style="display: flex; gap: 1rem; align-items: center;">
                    <!-- Search Bar -->
                    <div class="search-container" style="position: relative;">
                        <i class='bx bx-search' style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
                        <input type="text" id="studentSearch" placeholder="Search students..." style="padding: 0.5rem 1rem 0.5rem 2rem; border: 1px solid #d1d5db; border-radius: 0.375rem; width: 250px;">
                    </div>
                    
                    <!-- Entries Filter -->
                    <div class="entries-filter" style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 0.875rem; color: #4b5563;">Show</span>
                        <select id="entriesFilter" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span style="font-size: 0.875rem; color: #4b5563;">entries</span>
                    </div>
                </div>
        </div>
        <div class="card-content">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Time</th>    
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Reason for Visit</th>
                            <th>Status/Outcome</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT sc.DateTime as DT, s.School_ID as SID, s.FirstName as FN, s.LastName as LN, sc.Reason as R, sc.Status as S FROM studentcheckins sc JOIN student s ON sc.StudentID = s.School_ID ORDER BY ID DESC";
                        $result = $con->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["DT"] . "</td>";
                                echo "<td>" . $row["SID"] . "</td>";
                                echo "<td>" . $row["FN"] . " " . $row["LN"] . "</td>";
                                echo "<td>" . $row["R"] . "</td>";
                                echo "<td>" . $row["S"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No recent clinic visits</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>