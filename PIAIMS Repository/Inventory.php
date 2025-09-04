<section class="content-section" id="restockSection">
    <div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <h3>Total Medicines</h3>
            <i class='bx bx-package'></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">156</div>
            <p class="stat-change">In inventory</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <h3>Low Stock Items</h3>
            <i class='bx bx-error'></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">8</div>
            <p class="stat-change">Requires restocking</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <h3>Pending Requests</h3>
            <i class='bx bx-time'></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">3</div>
            <p class="stat-change">Awaiting approval</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <h3>This Month Usage</h3>
            <i class='bx bx-trending-up'></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">245</div>
            <p class="stat-change">Items dispensed</p>
        </div>
    </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Medicine Inventory</h2>
            <div class="card-actions">
                <button class="btn btn-primary" onclick="submitRestockRequest()">
                    <i class='bx bx-send'></i> Submit Request
                </button>
                <button class="btn btn-outline" onclick="clearRestockList()">
                    <i class='bx bx-x'></i> Clear List
                </button>
            </div>
        </div>
    <div class="card-content">
        <div class="medicine-grid" id="medicineGrid">
        <div class="medicine-card">
            <div class="medicine-header">
                <div class="medicine-name">Paracetamol 500mg</div>
                <div class="stock-level">Stock: 45</div>
            </div>
            <p>Pain reliever and fever reducer</p>
            <div class="restock-controls">
                <input type="number" class="quantity-input" placeholder="Qty" min="1" value="0">
                <button class="btn btn-outline btn-sm" onclick="addToRestockList(this, 'Paracetamol 500mg')">
                    <i class='bx bx-plus'></i> Add
                </button>
            </div>
        </div>

        <div class="medicine-card low-stock">
            <div class="medicine-header">
            <div class="medicine-name">Amoxicillin 250mg</div>
            <div class="stock-level low">Stock: 8</div>
            </div>
            <p>Antibiotic for bacterial infections</p>
            <div class="restock-controls">
                <input type="number" class="quantity-input" placeholder="Qty" min="1" value="50">
                <button class="btn btn-primary btn-sm" onclick="addToRestockList(this, 'Amoxicillin 250mg')">
                    <i class='bx bx-plus'></i> Add
                </button>
            </div>
        </div>

        <div class="medicine-card">
            <div class="medicine-header">
                <div class="medicine-name">Ibuprofen 400mg</div>
                <div class="stock-level">Stock: 32</div>
            </div>
            <p>Anti-inflammatory pain reliever</p>
            <div class="restock-controls">
                <input type="number" class="quantity-input" placeholder="Qty" min="1" value="0">
                <button class="btn btn-outline btn-sm" onclick="addToRestockList(this, 'Ibuprofen 400mg')">
                    <i class='bx bx-plus'></i> Add
                </button>
            </div>
        </div>

        <div class="medicine-card low-stock">
            <div class="medicine-header">
                <div class="medicine-name">Cetirizine 10mg</div>
                <div class="stock-level low">Stock: 5</div>
            </div>
            <p>Antihistamine for allergies</p>
            <div class="restock-controls">
                <input type="number" class="quantity-input" placeholder="Qty" min="1" value="30">
                <button class="btn btn-primary btn-sm" onclick="addToRestockList(this, 'Cetirizine 10mg')">
                    <i class='bx bx-plus'></i> Add
                </button>
            </div>
        </div>

        <div class="medicine-card">
            <div class="medicine-header">
                <div class="medicine-name">Omeprazole 20mg</div>
                <div class="stock-level">Stock: 28</div>
            </div>
            <p>Proton pump inhibitor for acid reflux</p>
            <div class="restock-controls">
                <input type="number" class="quantity-input" placeholder="Qty" min="1" value="0">
                <button class="btn btn-outline btn-sm" onclick="addToRestockList(this, 'Omeprazole 20mg')">
                    <i class='bx bx-plus'></i> Add
                </button>
            </div>
        </div>

        <div class="medicine-card low-stock">
            <div class="medicine-header">
                <div class="medicine-name">Metformin 500mg</div>
                <div class="stock-level low">Stock: 12</div>
            </div>
            <p>Diabetes medication</p>
            <div class="restock-controls">
                <input type="number" class="quantity-input" placeholder="Qty" min="1" value="40">
                <button class="btn btn-primary btn-sm" onclick="addToRestockList(this, 'Metformin 500mg')">
                    <i class='bx bx-plus'></i> Add
                </button>
            </div>
        </div>
    </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Restock Request List</h2>
        <div class="card-actions">
            <span id="restockCount">0 items selected</span>
        </div>
    </div>
    <div class="card-content">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Current Stock</th>
                        <th>Requested Quantity</th>
                        <th>Priority</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="restockList">
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--color-nature-green-600);">
                            No items selected for restocking
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>