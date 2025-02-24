    // Sidebar collapse functionality
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const spaceHold = document.getElementById('spacehold');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const elementsHeading = sidebar.querySelector('h5.text-center.mt-4');

    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('collapsed');
      if (sidebar.classList.contains('collapsed')) {
        elementsHeading.style.display = 'none'; // Hide the heading
        spaceHold.style.display = 'block';
      } else {
        elementsHeading.style.display = 'block'; // Show the heading
        spaceHold.style.display = 'none';
      }
    });

    // Drag-and-Drop functionality
    const draggableItems = document.querySelectorAll('.sidebar-menu-item');
    const dropzone = document.getElementById('dropzone');

    draggableItems.forEach((item) => {
      item.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('type', item.getAttribute('data-type'));
        e.dataTransfer.setData('label', item.querySelector('.label').textContent);
      });
    });

    dropzone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropzone.classList.add('active');
    });

    dropzone.addEventListener('dragleave', () => {
      dropzone.classList.remove('active');
    });

    dropzone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropzone.classList.remove('active');

      const type = e.dataTransfer.getData('type');
      const label = e.dataTransfer.getData('label');

      const propertyItem = document.createElement('div');
      propertyItem.classList.add('property-item');
      propertyItem.innerHTML = `
        <span>${label}</span>
        <input name="${label}" type="hidden" id="${label}" value="1">	
        <button class="btn btn-sm btn-danger remove-btn">Remove</button>
      `;

      propertyItem.querySelector('.remove-btn').addEventListener('click', () => {
        propertyItem.remove();
      });

      dropzone.appendChild(propertyItem);
    });