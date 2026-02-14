<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Adoption Search</title>
    <style>
        #suggestions {
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }
        #suggestions div {
            padding: 10px;
            cursor: pointer;
        }
        #suggestions div:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <h1>Dog Adoption System</h1>
    <p>Search for dogs by breed:</p>
    <input type="text" id="searchInput" placeholder="Search by breed..." onkeyup="getSuggestions()">
    <div id="suggestions"></div>

    <script>
        // Function to fetch suggestions from PHP
        function getSuggestions() {
            const searchQuery = document.getElementById('searchInput').value;

            // Check if input is not empty
            if (searchQuery.length === 0) {
                document.getElementById('suggestions').style.display = 'none';
                return;
            }

            // Create the AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "search_suggestions.php?query=" + searchQuery, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const suggestions = JSON.parse(xhr.responseText);
                    showSuggestions(suggestions);
                }
            };
            xhr.send();
        }

        // Function to display suggestions
        function showSuggestions(suggestions) {
            const suggestionsBox = document.getElementById('suggestions');
            suggestionsBox.innerHTML = '';  // Clear previous suggestions

            if (suggestions.length > 0) {
                suggestionsBox.style.display = 'block';
                suggestions.forEach(function (suggestion) {
                    const div = document.createElement('div');
                    div.textContent = suggestion.breed;  // Display breed only
                    div.onclick = function () {
                        // Redirect to dogs_by_breed.php with the selected breed as a query parameter
                        window.location.href = 'dogs_by_breed.php?breed=' + suggestion.breed;
                    };
                    suggestionsBox.appendChild(div);
                });
            } else {
                suggestionsBox.style.display = 'none';  // Hide if no suggestions
            }
        }
    </script>

</body>
</html>
