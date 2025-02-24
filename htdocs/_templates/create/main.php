  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <h1>Add Device</h1>
    <form method="post" action="create.php">
      <div class="mb-3">
        <label for="deviceName" class="form-label">Device Name</label>
        <input type="text" name="devicename" class="form-control" id="deviceName" placeholder="Enter device name" required>
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
