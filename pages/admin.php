<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();}
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/no_uderline.css">
    <title>Admin Dashboard</title>
    <style></style>
</head>

<body>
    <a href="../index.php"><h1>Admin Dashboard</h1></a>
    <a href="logout.php">logout</a>

    <div id="tableSelector">
        <h2>Select a table to manage:</h2>
        <select id="tableSelect">
        </select>
    </div>

    <div id="formContainer">
    </div>

    <div id="editFormContainer" style="display: none;">
        <h2>Edit Item</h2>
        <form id="editForm">
        </form>
    </div>

    <div id="tableData" class="table-container">
    </div>

    <script>
        function createForm() {
            const formContainer = document.getElementById('formContainer');
            formContainer.innerHTML = `
                <h2>Add New Item</h2>
                <form id="addForm">
                </form>
            `;
        
        }

        let currentTable = '';

        function loadTableNames() {
    fetch('http://localhost:8000/table_names')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('tableSelect');
            select.innerHTML = '';
            data.table_names.forEach(tableName => {
                if (tableName.toLowerCase() !== 'admin') {
                    const option = document.createElement('option');
                    option.value = tableName;
                    option.textContent = tableName;
                    select.appendChild(option);
                }
            });
            loadTableData();
        });
}

        function loadTableData() {
            currentTable = document.getElementById('tableSelect').value;
            fetch(`http://localhost:8000/${currentTable}`)
                .then(response => response.json())
                .then(data => {
                    const tableContainer = document.getElementById('tableData');
                    tableContainer.innerHTML = '';
        
                    createAddForm(data[currentTable][0]);
        
                    const table = document.createElement('table');
                    const thead = document.createElement('thead');
                    const tbody = document.createElement('tbody');
        
                    const headerRow = document.createElement('tr');
                    Object.keys(data[currentTable][0]).forEach(key => {
                        const th = document.createElement('th');
                        th.textContent = key;
                        headerRow.appendChild(th);
                    });
                    const actionsTh = document.createElement('th');
                    actionsTh.textContent = 'Actions';
                    headerRow.appendChild(actionsTh);
                    thead.appendChild(headerRow);
        
                    data[currentTable].forEach((item, index) => {
                        const row = document.createElement('tr');
                        Object.entries(item).forEach(([key, value]) => {
                            const cell = document.createElement('td');
                            if (key === "password") {
                                const passwordSpan = document.createElement('span');
                                passwordSpan.textContent = '••••••••';
                                passwordSpan.className = 'password-field';
                                passwordSpan.dataset.password = value;
                                passwordSpan.style.cursor = 'pointer';
                                passwordSpan.onclick = function() {
                                    if (this.textContent === '••••••••') {
                                        this.textContent = this.dataset.password;
                                    } else {
                                        this.textContent = '••••••••';
                                    }
                                };
                                cell.appendChild(passwordSpan);
                            } else if (currentTable === 'job_ads' && key === 'description') {
                                const shortDesc = value.substring(0, 50) + '...';
                                cell.textContent = shortDesc;
                                cell.title = value;
                                cell.style.cursor = 'pointer';
                                cell.onclick = function() {
                                    if (this.textContent === shortDesc) {
                                        this.textContent = value;
                                    } else {
                                        this.textContent = shortDesc;
                                    }
                                };
                            } else {
                                cell.textContent = value;
                            }
                            row.appendChild(cell);
                        });
        
                        const actionsCell = document.createElement('td');
                        const editButton = document.createElement('button');
                        editButton.textContent = 'Edit';
                        editButton.onclick = () => editItem(item.id);
                        const deleteButton = document.createElement('button');
                        deleteButton.textContent = 'Delete';
                        deleteButton.onclick = () => deleteItem(item.id);
                        actionsCell.appendChild(editButton);
                        actionsCell.appendChild(deleteButton);
                        row.appendChild(actionsCell);
        
                        tbody.appendChild(row);
                    });
        
                    table.appendChild(thead);
                    table.appendChild(tbody);
                    tableContainer.appendChild(table);
                });
        }
        
        function toggleAllPasswords() {
            const passwordFields = document.querySelectorAll('.password-field');
            const arePasswordsVisible = passwordFields[0].textContent !== '••••••••';
        
            passwordFields.forEach(field => {
                if (arePasswordsVisible) {
                    field.textContent = '••••••••';
                } else {
                    field.textContent = field.dataset.password;
                }
            });
        }

        function editItem(id) {
            fetch(`http://localhost:8000/${currentTable}/${id}`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('editForm');
                    form.innerHTML = '';
        
                    Object.entries(data).forEach(([key, value]) => {
                        if (key !== 'id') {
                            const label = document.createElement('label');
                            label.textContent = key;
        
                            if (key === 'contract_type') {
                                const select = document.createElement('select');
                                select.name = key;
                                select.id = 'contract';
                                select.required = true;
        
                                const options = [
                                    {id: 'cdi', value: 'CDI', text: 'CDI'},
                                    {id: 'cdd', value: 'CDD', text: 'CDD'},
                                    {id: 'half-time', value: 'Half-time', text: 'Half-time'},
                                    {id: 'internship', value: 'Internship', text: 'Internship'},
                                    {id: 'apprenticeship', value: 'Apprenticeship', text: 'Apprenticeship'},
                                    {id: 'freelance', value: 'Freelance', text: 'Freelance'}
                                ];
        
                                options.forEach(opt => {
                                    const option = document.createElement('option');
                                    option.id = opt.id;
                                    option.value = opt.value;
                                    option.textContent = opt.text;
                                    if (opt.value === value) {
                                        option.selected = true;
                                    }
                                    select.appendChild(option);
                                });
        
                                label.appendChild(select);
                            } else if (key === 'password') {
                                const input = document.createElement('input');
                                input.type = 'password';
                                input.name = key;
                                input.placeholder = 'Enter new password (leave empty to keep current)';
                                label.appendChild(input);
                            } else {
                                const input = document.createElement('input');
                                input.type = 'text';
                                input.name = key;
                                input.value = value;
                                input.placeholder = key;
                                label.appendChild(input);
                            }
        
                            form.appendChild(label);
                        }
                    });
        
                    const submitButton = document.createElement('button');
                    submitButton.textContent = 'Update';
                    submitButton.onclick = (e) => {
                        e.preventDefault();
                        updateItem(id);
                    };
                    form.appendChild(submitButton);
        
                    const cancelButton = document.createElement('button');
                    cancelButton.textContent = 'Cancel';
                    cancelButton.onclick = (e) => {
                        e.preventDefault();
                        document.getElementById('editFormContainer').style.display = 'none';
                    };
                    form.appendChild(cancelButton);
        
                    document.getElementById('editFormContainer').style.display = 'block';
        
                    document.getElementById('editFormContainer').scrollIntoView({behavior: "smooth"});
                });
        }
        
        function updateItem(id) {
            const form = document.getElementById('editForm');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
        
            if (data.password === '') {
                delete data.password;
            }
        
            fetch(`http://localhost:8000/${currentTable}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                document.getElementById('editFormContainer').style.display = 'none';
                loadTableData();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the item.');
            });
        }

        function deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                fetch(`http://localhost:8000/${currentTable}/${id}`, {
                    method: 'DELETE',
                })
                .then(response => response.json())
                .then(result => {
                    loadTableData();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the item.');
                });
            }
        }

        function createAddForm(sampleItem) {
            const formContainer = document.createElement('div');
            formContainer.id = 'addFormContainer';
            
            const formTitle = document.createElement('h3');
            formTitle.textContent = 'Add New Item';
            formContainer.appendChild(formTitle);
        
            const form = document.createElement('form');
            form.id = 'addForm';
            
            Object.keys(sampleItem).forEach(field => {
                if (field !== 'id') {
                    const label = document.createElement('label');
                    label.textContent = field;
                    let input;
        
                    if (field === 'is_applier' && currentTable === 'people') {
                        input = document.createElement('select');
                        const option0 = document.createElement('option');
                        option0.value = "0";
                        option0.textContent = "No";
                        const option1 = document.createElement('option');
                        option1.value = "1";
                        option1.textContent = "Yes";
                        input.appendChild(option0);
                        input.appendChild(option1);
                    } else if (field === 'password') {
                        input = document.createElement('input');
                        input.type = 'password';
                    } else {
                        input = document.createElement('input');
                        input.type = 'text';
                    }
        
                    input.name = field;
                    input.placeholder = field;
                    label.appendChild(input);
                    form.appendChild(label);
                }
            });
        
            const submitButton = document.createElement('button');
            submitButton.type = 'submit';
            submitButton.textContent = 'Add New Item';
            form.appendChild(submitButton);
            form.onsubmit = addNewItem;
        
            formContainer.appendChild(form);
            document.getElementById('tableData').insertBefore(formContainer, document.getElementById('tableData').firstChild);
        }
        
        async function addNewItem(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const newItem = Object.fromEntries(formData.entries());
        
            if (currentTable === 'people') {
                newItem.is_applier = parseInt(newItem.is_applier) || 0;
            }
        
            fetch(`http://localhost:8000/${currentTable}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(newItem),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                loadTableData();
                document.getElementById('addForm').reset();
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred while adding the item.');
            });
        }
        
        loadTableNames();
        document.getElementById('tableSelect').addEventListener('change', loadTableData);

    </script>
</body>
</html>