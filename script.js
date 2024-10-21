const complaintForm = document.getElementById('complaintForm');
const complaintList = document.getElementById('complaintList');

// Register a complaint
if (complaintForm) {
    complaintForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const category = document.getElementById('category').value;

        const complaintData = {
            title,
            description,
            category,
        };

        // Send the complaint to the backend
        await fetch('http://localhost/complaint_management/Backend/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(complaintData),
        });

        // Reset the form
        complaintForm.reset();
    });
}

// Function to fetch complaints and display them
async function fetchComplaints() {
    try {
        const response = await fetch('http://localhost/complaint_management/Backend/api.php');

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const complaints = await response.json();
        complaintList.innerHTML = '';

        complaints.forEach(complaint => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${complaint.title}</td>
                <td>${complaint.description}</td>
                <td>${complaint.category}</td>
                <td>${new Date(complaint.created_at).toLocaleString()}</td>
            `;
            complaintList.appendChild(row);
        });
    } catch (error) {
        console.error('Error fetching complaints:', error);
    }
}

// Fetch complaints on page load for the view complaints page
if (complaintList) {
    fetchComplaints();
}