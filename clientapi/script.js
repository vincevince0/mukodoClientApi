document.getElementById('btn-ok-county').addEventListener('click', function() {
    const selectedCountyId = document.getElementById('county-dropdown').value;
    
    // Perform an AJAX request to fetch cities for the selected county
    fetch(`/get-cities?county_id=${selectedCountyId}`)
        .then(response => response.json())
        .then(data => {
            // Update the cities table with the retrieved data
            updateCitiesTable(data);
        })
        .catch(error => console.error('Error fetching cities:', error));
});

// Function to update the cities table with retrieved data
function updateCitiesTable(cities) {
    const tbody = document.querySelector('#cities-table');
    tbody.innerHTML = ''; // Clear the current table content
    
    cities.forEach(city => {
        const row = `<tr>
            <td>${city.id}</td>
            <td>${city.name}</td>
        </tr>`;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}
