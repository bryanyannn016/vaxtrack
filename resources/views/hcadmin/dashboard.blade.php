@extends('layouts.hcadmin-sidebar')

@section('title', 'HCAdmin Dashboard')

<style>
    /* Modal Styles */
    .modal-container {
        display: none; /* Initially hidden */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Dim background */
        z-index: 1050; /* Ensure it's above other elements */
    }

    .modal-container.modal-open {
        display: flex; /* Flex to center the modal */
        justify-content: center;
        align-items: center;
    }

    .modal {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 500px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        height: 200px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        height:30px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .close-button {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
    }

    .modal-button {
        padding: 8px 16px;
        border: none;
        cursor: pointer;
    }

    .modal-confirm-button {
        background-color: #007bff;
        color: #fff;
        border-radius: 4px;
    }
    
    .add-vaccine-btn {
        background-color: #C6E0FF; 
        border-color: #C6E0FF; 
        color: #000; 
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-right:120px;
    }
</style>

@section('content')

<!-- Add New Vaccine Button -->
<div style="display: flex; justify-content: flex-end; margin-top:20px;">
    <button class="add-vaccine-btn" onclick="openAddVaccineModal()">Add New Vaccine</button>
</div>

<div class="container">
    <table class="account-table">
        <thead>
            <tr>
                <th>Vaccine Name</th>
                <th style="width:200px;">Current Quantity</th>
                <th style="width:200px;">Action</th> <!-- New Action Column -->
            </tr>
        </thead>
        <tbody>
            @foreach($vaccineStocks as $stock)
            <tr>
                <td>{{ $stock->vaccine_name }}</td>
                <td>{{ $stock->current_quantity }}</td>
                <td>
                    <!-- Add Stock Button -->
                    <button 
                    class="btn btn-primary add-stock-btn" 
                    data-vaccine-name="{{ $stock->vaccine_name }}" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">
                    Add Stock 
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Stock Modal HTML -->
<div id="addStockModal" class="modal-container">
    <div class="modal">
        <header class="modal-header">
            <h2>Add Stock</h2>
            <button class="close-button" onclick="closeAddStockModal()">X</button>
        </header>
        <section class="modal-content" style="margin-top:40px;">
            <form id="addStockForm" action="{{ route('hcadmin.addStock') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="vaccineName" class="label-color" style="margin-right:20px; color: #286187;"><strong>Vaccine Name:</strong></label>
                    <input type="text" id="vaccineName" name="vaccineName" style="width:300px;" class="form-control" readonly>
                </div>
                <div class="form-group" style="margin-top:10px;">
                    <label for="quantityToAdd" class="label-color" style="color: #286187;" ><strong>Quantity to Add:</strong></label>
                    <input type="number" id="quantityToAdd" name="quantityToAdd" style="margin-left:10px; width:100px;" class="form-control" min="1" required oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>
                <footer class="modal-footer">
                    <button type="submit" class="modal-button modal-confirm-button">Confirm</button>
                    <button type="button" class="modal-button" onclick="closeAddStockModal()">Cancel</button>
                </footer>
            </form>
        </section>
    </div>
</div>

<!-- Add New Vaccine Modal HTML -->
<div id="addVaccineModal" class="modal-container">
    <div class="modal">
        <header class="modal-header">
            <h2>Add New Vaccine</h2>
            <button class="close-button" onclick="closeAddVaccineModal()">X</button>
        </header>
        <section class="modal-content" style="margin-top:40px;">
            <form id="addVaccineForm" action="{{ route('hcadmin.addNewVaccine') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="newVaccineName" class="label-color" style="margin-right:20px; color: #286187;"><strong>Vaccine Name:</strong></label>
                    <input type="text" id="newVaccineName" name="vaccineName" style="width:300px;" class="form-control" required>
                </div>
                <div class="form-group" style="margin-top:10px;">
                    <label for="newQuantity" class="label-color" style="color: #286187;" ><strong>Quantity:</strong></label>
                    <input type="number" id="newQuantity" name="quantity" style="margin-left:65px; width:100px;" class="form-control" min="1" required oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>
                <footer class="modal-footer">
                    <button type="submit" class="modal-button modal-confirm-button">Confirm</button>
                    <button type="button" class="modal-button" onclick="closeAddVaccineModal()">Cancel</button>
                </footer>
            </form>
        </section>
    </div>
</div>

<script>
    let selectedVaccine = null;

    // Ensure that the DOM is fully loaded before attaching event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Open modal
        document.querySelectorAll('.add-stock-btn').forEach(button => {
            button.addEventListener('click', openAddStockModal);
        });
    });

    function closeAddStockModal() {
        // Hide modal
        document.getElementById('addStockModal').classList.remove('modal-open');

        // Clear inputs
        document.getElementById('vaccineName').value = '';
        document.getElementById('quantityToAdd').value = '';
    }

    // Function to open the Add Stock Modal
    function openAddStockModal(event) {
        // Fetch the vaccine name from the clicked button
        const vaccineName = event.target.getAttribute('data-vaccine-name');

        selectedVaccine = vaccineName;

        // Set values in the modal
        document.getElementById('vaccineName').value = selectedVaccine;

        // Show modal
        document.getElementById('addStockModal').classList.add('modal-open');
    }

    // Function to open the Add New Vaccine Modal
    function openAddVaccineModal() {
        // Show modal
        document.getElementById('addVaccineModal').classList.add('modal-open');
    }

    function closeAddVaccineModal() {
        // Hide modal
        document.getElementById('addVaccineModal').classList.remove('modal-open');

        // Clear inputs
        document.getElementById('newVaccineName').value = '';
        document.getElementById('newQuantity').value = '';
    }
</script>

@endsection
