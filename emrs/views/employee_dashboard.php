<?php 
session_start(); 
 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') { 
    header("Location: login.php"); 
    exit(); 
} 
 
require_once __DIR__ . '/../models/requestModel.php'; 
$myRequests = getRequestsByEmployee($_SESSION['user_id']); 
?>
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <title>EMRS - Employee Dashboard</title> 
    <link rel="stylesheet" href="../css/style.css"> 
</head> 
<body> 
 
    <header> 
        <h1>Equipment & Maintenance Request System</h1> 
        <div> 
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Employee)</span> 
            <a href="request_form.php" class="btn btn-sm btn-success" style="color: #fff; margin-left: 15px;">+ New Request</a> 
            <a href="../logout.php">Logout</a> 
        </div> 
    </header> 
 
    <div class="container"> 
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;"> 
            <h2>My Maintenance Requests</h2> 
            <a href="request_form.php" class="btn btn-primary" style="width: auto;">Submit New Ticket</a> 
        </div> 
 
        <?php if (isset($_GET['success']) && $_GET['success'] === 'ticket_created'): ?> 
            <p style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 4px; font-size: 13px;"> 
                Your maintenance request was submitted successfully! 
            </p> 
        <?php endif; ?> 
 
        <?php if (empty($myRequests)): ?> 
            <div style="background: #ffffff; padding: 25px; border: 1px solid #e0e0e0; border-radius: 4px; text-align: center; margin-top: 20px;"> 
                <p style="color: #666;">You haven't submitted any maintenance requests yet.</p> 
            </div> 
        <?php else: ?> 
            <table> 
                <thead> 
                    <tr> 
                        <th>Ticket ID</th> 
                        <th>Equipment</th> 
                        <th>Category</th> 
                        <th>Priority</th> 
                        <th>Description</th> 
                        <th>Submitted At</th> 
                        <th>Status</th> 
                        <th>Action</th> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php foreach ($myRequests as $row): ?> 
                        <tr> 
                            <td>#<?php echo $row['RequestID']; ?></td> 
                            <td><strong><?php echo htmlspecialchars($row['ItemName']); ?></strong></td> 
                            <td><?php echo htmlspecialchars($row['Category']); ?></td> 
                            <td> 
                                <span style="font-weight: bold; color: <?php echo ($row['Priority'] === 'High') ? '#e74c3c' : (($row['Priority'] === 'Medium') ? '#f39c12' : '#27ae60'); ?>;"> 
                                    <?php echo htmlspecialchars($row['Priority']); ?> 
                                </span> 
                            </td> 
                            <td><?php echo htmlspecialchars($row['Description']); ?></td> 
                            <td><?php echo date("M d, Y - h:i A", strtotime($row['CreatedAt'])); ?></td> 
                             
                            <td> 
                                <?php 
                                $badgeClass = 'badge-pending'; 
                                if ($row['Status'] === 'Assigned' || $row['Status'] === 'In Progress') { 
                                    $badgeClass = 'badge-progress'; 
                                } elseif ($row['Status'] === 'Resolved' || $row['Status'] === 'Completed') { 
                                    $badgeClass = 'badge-resolved'; 
                                } elseif ($row['Status'] === 'Cancelled') { 
                                    $badgeClass = 'badge-rejected'; 
                                } 
                                ?> 
                                <span class="badge <?php echo $badgeClass; ?>"> 
                                    <?php echo htmlspecialchars($row['Status']); ?> 
                                </span> 
                            </td> 
 
                            <td> 
                                <?php if ($row['Status'] === 'Pending'): ?> 
                                    <form action="../controllers/requestController.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this request?');"> 
                                        <input type="hidden" name="request_id" value="<?php echo $row['RequestID']; ?>"> 
                                        <input type="hidden" name="request_action" value="cancel"> 
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button> 
                                    </form> 
 
                                <?php elseif ($row['Status'] === 'Resolved'): ?> 
                                    <form action="../controllers/requestController.php" method="POST"> 
                                        <input type="hidden" name="request_id" value="<?php echo $row['RequestID']; ?>"> 
                                        <input type="hidden" name="request_action" value="confirm_done"> 
                                        <button type="submit" class="btn btn-success btn-sm">Confirm Done</button> 
                                    </form> 
 
                                <?php else: ?> 
                                    <span style="color: #999; font-size: 13px;">In Tracking</span> 
                                <?php endif; ?> 
                            </td> 
                        </tr> 
                    <?php endforeach; ?> 
                </tbody> 
            </table> 
        <?php endif; ?> 
    </div> 
 
</body> 
</html>