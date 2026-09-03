document.addEventListener("DOMContentLoaded", function () {

    const regEmailInput = document.getElementById("regEmail");
    const regEmailError = document.getElementById("regEmailError");

    if (regEmailInput) {
        let debounceTimer;
        regEmailInput.addEventListener("input", function () {
            clearTimeout(debounceTimer);
            const email = regEmailInput.value.trim();

            if (email.length < 5 || !email.includes("@")) {
                regEmailError.innerText = "";
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`../controllers/AjaxController.php?action=check_email&email=${encodeURIComponent(email)}`, {
                    method: "GET"
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "taken") {
                        regEmailError.style.color = "#dc2626";
                        regEmailError.innerText = data.message;
                    } else if (data.status === "available") {
                        regEmailError.style.color = "#16a34a";
                        regEmailError.innerText = data.message;
                    }
                })
                .catch(err => console.error("AJAX GET Error:", err));
            }, 300);
        });
    }

    const equipSelect = document.getElementById("reqEquipment");
    const equipInfoBox = document.getElementById("equipmentInfoBox");

    if (equipSelect && equipInfoBox) {
        equipSelect.addEventListener("change", function () {
            const equipmentId = this.value;

            if (!equipmentId) {
                equipInfoBox.style.display = "none";
                equipInfoBox.innerHTML = "";
                return;
            }

            fetch(`../controllers/AjaxController.php?action=get_equipment_info&equipment_id=${equipmentId}`, {
                method: "GET"
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    equipInfoBox.style.display = "block";
                    equipInfoBox.innerHTML = `
                        <div style="background:#f1f5f9; padding:10px; border-radius:4px; font-size:13px; border-left:4px solid #2563eb;">
                            <strong>Category:</strong> ${data.category} | 
                            <strong>Stock:</strong> ${data.stock} units | 
                            <strong>Condition:</strong> ${data.condition}
                        </div>
                    `;
                }
            })
            .catch(err => console.error("AJAX GET Error:", err));
        });
    }

    const requestForm = document.getElementById("requestForm");
    if (requestForm) {
        requestForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(requestForm);
            formData.set("action", "create_request");

            fetch("../controllers/AjaxController.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    showToast(data.message, "success");
                    requestForm.reset();
                    if (equipInfoBox) equipInfoBox.style.display = "none";
                    setTimeout(() => {
                        window.location.href = "employee_dashboard.php";
                    }, 1200);
                } else {
                    showToast(data.message, "error");
                }
            })
            .catch(err => console.error("AJAX POST Error:", err));
        });
    }

    document.querySelectorAll(".manager-action-btn").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const row = this.closest("tr");
            const requestId = this.dataset.requestId;
            const operation = this.dataset.operation;

            const formData = new FormData();
            formData.append("action", "update_status_ajax");
            formData.append("request_id", requestId);
            formData.append("operation", operation);

            if (operation === "assign_and_approve") {
                const selectTech = row.querySelector("select[name='technician_id']");
                if (!selectTech || !selectTech.value) {
                    alert("Please select a technician first.");
                    return;
                }
                formData.append("technician_id", selectTech.value);
            }

            fetch("../controllers/AjaxController.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    const statusCell = row.querySelector(".status-cell");
                    if (data.new_status === "Assigned") {
                        statusCell.innerHTML = `<span class="badge badge-progress">Assigned</span>`;
                        row.querySelector(".assign-cell").innerHTML = `<span class="badge badge-tech">${data.tech_name}</span>`;
                    } else if (data.new_status === "Rejected") {
                        statusCell.innerHTML = `<span class="badge badge-pending" style="background:#fee2e2;color:#991b1b;">Rejected</span>`;
                    }
                    row.querySelector(".action-cell").innerHTML = `<span class="text-muted">Processed</span>`;
                }
            })
            .catch(err => console.error("AJAX POST Error:", err));
        });
    });

    document.querySelectorAll(".tech-action-btn").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const row = this.closest("tr");
            const requestId = this.dataset.requestId;
            const operation = this.dataset.operation;

            const formData = new FormData();
            formData.append("action", "update_status_ajax");
            formData.append("request_id", requestId);
            formData.append("operation", operation);

            fetch("../controllers/AjaxController.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    const statusCell = row.querySelector(".tech-status-cell");
                    const actionCell = row.querySelector(".tech-action-cell");

                    if (data.new_status === "In Progress") {
                        statusCell.innerHTML = `<span class="badge badge-progress">In Progress</span>`;
                        actionCell.innerHTML = `<button type="button" class="btn-success btn-sm tech-action-btn" data-request-id="${requestId}" data-operation="mark_resolved">Mark Resolved</button>`;
                        location.reload();
                    } else if (data.new_status === "Resolved") {
                        statusCell.innerHTML = `<span class="badge badge-resolved">Resolved</span>`;
                        actionCell.innerHTML = `<span class="text-muted">Task Finished</span>`;
                    }
                }
            })
            .catch(err => console.error("AJAX POST Error:", err));
        });
    });

    function showToast(message, type = "success") {
        let toast = document.getElementById("toastNotification");
        if (!toast) {
            toast = document.createElement("div");
            toast.id = "toastNotification";
            document.body.appendChild(toast);
        }
        toast.className = `toast ${type === "success" ? "toast-success" : "toast-error"}`;
        toast.innerText = message;
        toast.style.display = "block";

        setTimeout(() => {
            toast.style.display = "none";
        }, 2500);
    }
});