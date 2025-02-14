<?php
// include 'libs/include/Device.class.php';

if (Session::isAuthenticated()) {
if(isset($_POST['devicename']) && (isset($_POST['ToggleButton']) || isset($_POST['Slider']) || isset($_POST['Display']) || isset($_POST['Indicator']) || isset($_POST['Timer'])) && isset($_POST['description'])){
  $parameters = ['ToggleButton', 'Slider', 'Display', 'Indicator', 'Timer'];
  $passedParams = [
    'ToggleButton' => 0,
    'Slider' => 0,      
    'Display' => 0,
    'Indicator' => 0,
    'Timer' => 0
  ];

  foreach ($parameters as $param) {
      if (isset($_POST[$param])) {
          $passedParams[$param] = $_POST[$param];
      }
}

$result = Device::addDevice($_POST['devicename'], $_POST['description'], $passedParams['ToggleButton'], $passedParams['Slider'], $passedParams['Display'], $passedParams['Indicator'], $passedParams['Timer']);
if(!$result){
header("Location: /device.php");
die();
}else{
  printf("Error creating device");
}
print($result);
}

}
?>

<!-- Sidebar -->
  <div class="sidebar bg-dark" id="sidebar">
    <button class="sidebar-toggle" id="sidebarToggle">
      <span>&#9776;</span>
    </button>
    <h5 class="text-center mt-4">Properties</h5>
    <div class="sidebar-menu-item" id="spacehold" draggable="false" data-type="toggle">
      <span class="icon">.</span>
      <span class="label">.</span>
    </div>
    <div class="sidebar-menu-item" draggable="true" data-type="toggle">
      <span class="icon">🖲️</span>
      <span class="label">ToggleButton</span>
    </div>
    <div class="sidebar-menu-item" draggable="true" data-type="slider">
      <span class="icon">🎚️</span>
      <span class="label">Slider</span>
    </div>
    <div class="sidebar-menu-item" draggable="true" data-type="display">
      <span class="icon">📟</span>
      <span class="label">Display</span>
    </div>
    <div class="sidebar-menu-item" draggable="true" data-type="indicator">
      <span class="icon">💡</span>
      <span class="label">Indicator</span>
    </div>
    <div class="sidebar-menu-item" draggable="true" data-type="timer">
      <span class="icon">⏳</span>
      <span class="label">Timer</span>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <h1>Add Device</h1>
    <form method="post" action="create.php">
      <div class="mb-3">
        <label for="deviceName" class="form-label">Device Name</label>
        <input type="text" name="devicename" class="form-control" id="deviceName" placeholder="Enter device name">
      </div>
      <div class="mb-3">
        <label for="deviceProperties" class="form-label">Properties</label>
        <!-- Drop Zone -->
        <div class="dropzone" id="dropzone">
          Drag and drop elements here
        </div>
      </div>
      <div class="mb-3">
        <label for="deviceDescription" class="form-label">Description</label>
        <textarea class="form-control" name="description" type="text" id="deviceDescription" rows="3" placeholder="Enter description"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Device</button>
    </form>
  </div>

  <script>
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
  </script>


