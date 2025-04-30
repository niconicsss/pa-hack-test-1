<form action="../actions/payment_handler.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="order_id" value="1"> <!-- Replace with actual order ID -->
    
    <label>Payment Method:</label>
    <select name="payment_method" required>
        <option value="GCash">GCash</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Credit/Debit Card">Credit/Debit Card</option>
    </select><br>

    <label>Amount:</label>
    <input type="number" step="0.01" name="amount" required><br>

    <label>Status:</label>
    <select name="payment_status" required>
        <option value="Pending">Pending</option>
        <option value="Completed">Completed</option>
        <option value="Failed">Failed</option>
    </select><br>

    <label>Upload Payment Proof (optional):</label>
    <input type="file" name="payment_proof"><br><br>

    <button type="submit">Submit Payment</button>
</form>
