
        function toggleDropdown() {
            const dropdown = document.querySelector('.dropdown-content');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        function toggleEditProfile() {
            const editSection = document.getElementById('editProfileSection');
            editSection.style.display = editSection.style.display === 'none' ? 'block' : 'none';
        }

        document.getElementById('profileForm').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Profile updated successfully!');
        });
    